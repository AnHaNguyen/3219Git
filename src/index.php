<?php
    session_start();
    $current_page = 'Home';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    require_once('./php/contributors.php');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['git_url'] = $_POST["basic-url"];
    }
    
    $func = 'getAllUsers';
    $obj = json_decode($func);
    echo $obj;
    
    
    call_user_func_array('getAllUsers');
    
?>
<script src="//d3js.org/d3.v3.min.js"></script>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h2>Visualize your GitHub Repos</h2>
        <p>
            <label for="basic-url">Your Github Repo URL</label>
            <form method="post" class="form" role="form">
            <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
            <p></p>
            <button class="btn btn-primary" id="submit" name="submit" type="submit">Submit</button>
            </form>
        </p>
    </div>

</div> <!-- /container -->


<?php
    include_once('./template/footer.php');
    ?>


</body></html>

