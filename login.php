    <?php

    // Include config file

    require_once('_php/init.php');

    // Define variables and initialize with empty values

    $username = $password = "";
    $username_err = $password_err = "";

    // Processing form data when form is submitted

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Check if username is empty

        if(empty(trim($_POST["username"]))){
            $username_err = 'Please enter username.';
        } else{
            $username = trim($_POST["username"]);
        }

        // Check if password is empty

        if(empty(trim($_POST['password']))){
            $password_err = 'Please enter your password.';
        } else{
            $password = trim($_POST['password']);
        }

        // Validate credentials
        if(empty($username_err) && empty($password_err)){
            // Prepare a select statement
          $sql = "SELECT name, password FROM users WHERE name = ?";
          $stmt = $db->prepare($sql);
          $stmt->bind_param('s', $username);
          if($stmt->execute()){
            $stmt->store_result();
            echo var_dump($stmt);
            if($stmt->num_rows == 1){
              $stmt->bind_result($my_username, $hashed_password);
              $stmt->fetch();
              if(password_verify($password, $hashed_password)){
                session_start();
                $_SESSION['username'] = $username;
                redirect_to('welcome.php');
              } else {
                $password_err = "The password you entered is incorrect";
              }
            } else {
              $username_err = "The username you entered is incorrect";
            }
          }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>HAL Survey Platform Login</title>
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="shortcut icon" type="image/x-icon" href="hal.ico" />
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; margin: auto;}
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h2 class="text-center">Login</h2>
            <p>Please fill in your credentials to login.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Username</label>
                    <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            </form>
        </div>
    </body>
    </html>
