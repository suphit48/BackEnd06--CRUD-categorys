<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$productname = $price = $category = "";
$productname_err = $price_err = $category_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate productname (รองรับภาษาไทย)
$input_productname = trim($_POST["productname"]);
if(empty($input_productname)){
    $productname_err = "กรุณากรอกชื่อสินค้า";
} elseif(!preg_match("/^[\p{Thai}a-zA-Z0-9\s]+$/u", $input_productname)){
    $productname_err = "ชื่อสินค้าควรเป็นตัวอักษรไทย อังกฤษ หรือเลขเท่านั้น";
} else{
    $productname = $input_productname;
}
    
    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter a price.";     
    } else{
        $price = $input_price;
    }
    
    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
          $category_err = "Please enter a category.";     
    } else{
        $category = $input_category;
    }
   
    
    // Check input errors before inserting in database
    if(empty($productname_err) && empty($price_err) && empty($category_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO products (productname, price, category) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_productname, $param_price, $param_category);
            
            // Set parameters
            $param_productname = $productname;
            $param_price = $price;
            $param_category = $category;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: products.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add product record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>ชื่อสินค้า</label>
                            <input type="text" name="productname" class="form-control <?php echo (!empty($productname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $productname; ?>">
                            <span class="invalid-feedback"><?php echo $productname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>ราคาสินค้า</label>
                            <input type="number" step="0.01" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
<span class="invalid-feedback"><?php echo $price_err;?></span>
                        <div class="form-group">
                            <label>หมวดหมู่</label>
                            <input type="text" name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category; ?>">
                            <span class="invalid-feedback"><?php echo $category_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="products.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>