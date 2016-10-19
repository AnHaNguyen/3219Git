<?php
//session_start();
$current_page = 'Home';
include_once('./template/header.php');
include_once('./template/navbar.php');
?>

<div class="container">
    <div class="col-sm-12" style="margin-bottom: 50px;">
        <h1>Login</h1>
        <form id="signin">
        <div class="form-group">
            <label>Username</label>
            <input class="form-control" type="text" id="username" />
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" type="password" id="password" />
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>


<?php
    include_once('./template/footer.php');
    ?>


</body></html>

