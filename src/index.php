<?php
    session_start();
    $current_page = 'Home';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        $result = execute('getcontributors', null, null, null, null, null,'','');
        $result = json_encode($result);
    }
    
    if (isset($_POST["submit"])) {
        //https://github.com/jiaminw12/cs2102_stuffSharing
        //https://github.com/scrapy/scrapy <- cannot clone
        //https://github.com/detailyang/awesome-cheatsheet
        
        $userLink = $_POST['basic-url'];
        $response = execute($command='addrepo',$userLink);
        
        if (strcmp($response,"sucess")){
            $res = explode('/', parse_url($userLink, PHP_URL_PATH));
            $username = $res[1];
            $_SESSION['git_username'] = $username;
            $result = execute('getcontributors',null,null,null,null,null,'','');
            $result = json_encode($result);
        }
    }
    
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="./assets/js/d3.min.js" type="text/javascript"></script>
<script src="./assets/js/d3pie.min.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="./assets/js/app.js" type="text/javascript"></script>

<style>
svg{
    width: 600px;
    position: relative;
    left: 50%;
    -webkit-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    transform: translateX(-50%);
}
</style>


<div class="container">

<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
    <h2>Visualize your GitHub Repos</h2>
    <p>
    <label>Your Github Repo URL</label>
    <form method="post" class="form" role="form" action="index.php">
        <input type="text" class="form-control" id="basic-url" name="basic-url" aria-describedby="basic-addon3">
        <p></p>
        <input class="btn btn-primary" id="submit" name="submit" value="Submit" type="submit">
    </form>
    </p>
</div>


<div class="row">
    <div class="col-xs-12">
        <div id="chart"></div>
    </div>

    <div class="col-xs-12">
        <h4 class="sub-header">The following historical commit information, by author, was found.</h4>
        <br/>
        <div class="table-responsive">
            <table id="sortable" class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1">Author</th>
                        <th class="col-md-2">Commits</th>
                        <th class="col-md-3">Insertions</th>
                        <th class="col-md-3">Deletions</th>
                        <th class="col-md-3">% of changes</th>
                    </tr>
                </thead>
                <tbody id="tablebody01"></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var jsonData = '<?php echo $result ?>';
    //console.log(jsonData);
    var data = JSON.parse(jsonData);
    buildTable(data);
    $(document).ready(function() {
                      $('#sortable').DataTable();
    });
    draw01();
</script>

</div> <!-- /container -->

</body></html>

