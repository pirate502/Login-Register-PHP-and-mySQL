<?php
session_start();
if(isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <?php
//check if form already submit
        if(isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeatPassword = $_POST["repeat-password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT); 
            $errors = array();
            // Checks whether input has been entered or not
            if(empty($fullName) or empty($email) or empty($password) or empty($repeatPassword)) {
                array_push($errors, "All of feilds are required!");
            }
            // validate email data
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            // validate password
            if(strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 chacracter long");
            }
            // check whether password and repeatPassword is duplicate
            if($password !==$repeatPassword) {
                array_push($errors, "Password does not match!");
            }

            require_once "database.php";
            $sql ="SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn,$sql);
            $rowCount = mysqli_num_rows($result);
            //check whether email has aldready exists
            if ($rowCount > 0) {
                array_push($errors, "Email already exitsts");
            }
            if(count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                // insert data in to database
                $sql = "INSERT INTO users (full_name,email,password) VALUE ( ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if($prepareStmt) {
                    mysqli_stmt_bind_param($stmt,"sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class = 'alert alert-success'>You are register successfully.</div>";
                } else {
                    die("Something Wrong!");
                }
            }

        }   
        // form register 
        ?>
  
        <form action="register.php" method="POST">
            <div class="group">
                <input type="text" class="form-control" name="fullname" placeholder="FullName:">
            </div>
            <div class="group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="group">
                <input type="password" class="form-control" name="repeat-password" placeholder="Repeat-Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
            
    </form>
    <div><p>Already register <a href="login.php">Login</a></p></div>
    </div>
</body>
</html>
