<?php
    session_start();
    ob_start();
    if(isset($_POST['submit'])) {
        require_once '../config.php';
        $login = new User($db);
        if($login->login($_POST) == true){
            header('Location: index.php');
        } else {
            $error = '<div class="error">Forkert Login.</div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>City Cykler - Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="./assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/css/login/util.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/login/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('./assets/images/img-01.jpg');">
			<div class="wrap-login100 p-t-190 p-b-30">
				<form class="login100-form validate-form" id="login" method="post">

					<span class="login100-form-title p-t-20 p-b-45">
						Admin Login - City Cykler
					</span>

                    <?= @$error ?>

					<div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
					</div>

					<div class="container-login100-form-btn p-t-10">
						<button type="submit" form="login" name="submit" class="login100-form-btn">
							Login
						</button>
                    </div>
                    
                    

					<div class="text-center w-full p-t-25 p-b-230">

					</div>

					<div class="text-center w-full">

					</div>
                </form>
                <a href="../index.php" class="container-login100-form-btn p-t-10">
						<button class="login100-form-btn">
							Til City Cykler
						</button>
                </a>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="./assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/vendor/bootstrap/js/popper.js"></script>
	<script src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/js/main.js"></script>

</body>
</html>