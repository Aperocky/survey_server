<?php
// Include config file
require_once('_php/init.php');

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $param_username);
        $param_username = trim($_POST["username"]);
        if($stmt->execute()){
                /* store result */
            $stmt->store_result();
            if($stmt->num_rows() == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
      }
        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, password) VALUES (?, ?)";
        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            echo $param_password;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                redirect_to("login.php");
            } else{
                echo $stmt->error;
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    }
    // Close connection
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HAL Survey Platform</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="shortcut icon" type="image/x-icon" href="hal.ico" />
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: auto;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="text-center">Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <small id="emailHelp" class="form-text text-muted">We do not keep your password, please remember it</small>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
