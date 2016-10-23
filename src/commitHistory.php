<?php
    session_start();
    $current_page = 'Commit History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        $result = execute('getcommithistory', $_SESSION['git_url'], null, $_SESSION['git_username'], null, null,'','');
    }
    
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="./assets/js/d3.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="./assets/js/app.js" type="text/javascript"></script>

<div class="container">

<div class="row">
    <div class="col-xs-12">
        <div id="chart"></div>
    </div>

</div>

<script type="text/javascript">

    var jsonData = '<?php echo $result ?>';
    console.log(jsonData);
    //var data02 = JSON.parse(jsonData02);
    //draw02(data02);
    //buildTable02(data02);

</script>

</div> <!-- /container -->

</body></html>

