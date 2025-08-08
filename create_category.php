<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$categoryname = "";
$categoryname_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category name (รองรับภาษาไทย)
    $input_categoryname = trim($_POST["categoryname"]);
    if (empty($input_categoryname)) {
        $categoryname_err = "กรุณากรอกชื่อหมวดหมู่";
    } elseif (!preg_match("/^[\p{Thai}a-zA-Z0-9\s]+$/u", $input_categoryname)) {
        $categoryname_err = "ชื่อหมวดหมู่ควรเป็นตัวอักษรไทย อังกฤษ หรือเลขเท่านั้น";
    } else {
        $categoryname = $input_categoryname;
    }

    // Check input errors before inserting in database
    if (empty($categoryname_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO categories (categoryname) VALUES (?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_categoryname);

            // Set parameter
            $param_categoryname = $categoryname;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: categories.php");
                exit();
            } else {
                echo "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง.";
            }

            // Close statement (ภายใน if เตรียม stmt ได้สำเร็จ)
            mysqli_stmt_close($stmt);
        }
    }
    
    // ไม่ต้องปิด $stmt ซ้ำข้างล่างนี้แล้ว
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มหมวดหมู่</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">เพิ่มหมวดหมู่</h2>
                    <p>กรุณากรอกชื่อหมวดหมู่ และกดปุ่ม Submit เพื่อบันทึกลงในฐานข้อมูล</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>ชื่อหมวดหมู่</label>
                            <input type="text" name="categoryname" class="form-control <?php echo (!empty($categoryname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $categoryname; ?>">
                            <span class="invalid-feedback"><?php echo $categoryname_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="categories.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
