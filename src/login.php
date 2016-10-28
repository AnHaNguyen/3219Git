<?php
ob_start();
session_start();
$current_page = 'Login';

include_once('./template/header.php');
include_once('./template/navbar.php');

if (isset($_SESSION["loginuser"])) {
    header("Location: index.php");
}

if(isset($_POST["submit"])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	if($username != '' && $password != ''){
		$_SESSION['loginuser'] = $username; 
		header("Location: index.php");
	}
}

?>

<div class="container">
    <div class="col-sm-12" style="margin-bottom: 50px;">
        <h1>Login</h1>
        <form method="post" class="form" role="form" action="login.php">
			<div class="form-group">
				<label>Username</label>
				<input class="form-control" type="text" id="username" name="username" required/>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input class="form-control" type="password" id="password" name="password" data-minlength="6" required/>
			</div>
			<input class="btn btn-primary" id="submit" name="submit" value="Submit" type="submit">
        </form>
    </div>
</div>

</body></html>

<?php ob_end_flush();?>

