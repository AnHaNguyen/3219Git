<?php
    session_start();
    $current_page = 'Home';
    ini_set('max_execution_time', 300);
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        $result = execute('getcontributors', null, null, null, null, null,'','');
        $result = json_encode($result);
    }
    
    if (isset($_POST["submit"])) {
        //https://github.com/jiaminw12/cs2102_stuffSharing
        //https://github.com/nhaarman/ListViewAnimations
		//https://github.com/JamesNK/Newtonsoft.Json
        //https://github.com/scrapy/scrapy
        //102 - https://github.com/leereilly/games

		if(empty($_POST['basic-url'])){
			$message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please insert a Git URL!</div>'; 
		} else {
			$userLink = $_POST['basic-url'];
			$response = execute($command='addrepo',$userLink);
			if (strcmp($response,"sucess")){
				$res = explode('/', parse_url($userLink, PHP_URL_PATH));
				$username = $res[1];
				$_SESSION['git_username'] = $username;
				$result = execute('getcontributors',null,null,null,null,null,'','');
				
				$_SESSION['git_contributors'] = getContributorsList($result);
				resetAllSession();
				$result = json_encode($result);
			}
		}
    }
	
	function getContributorsList($result){
		$total = 0;
		$result = json_encode($result);
        $jsondata = json_decode($result, true);
		$out = array();
        $list = array();
		foreach ($jsondata as $re){
			$out["Name"] = $re["name"];
			array_push($list, $out);
			$total += 1;
		}
		$_SESSION['git_total_contributors'] = $total;
		return json_encode($list);
	}
	
	function resetAllSession(){
		//1b
		unset($_SESSION['git_start_date']);
		
		//1c
		unset($_SESSION['current_contributors']);
		unset($_SESSION['git_start_date_diff']);
		unset($_SESSION['user01']);
		unset($_SESSION['user02']);
		unset($_SESSION['user03']);
		
		//1d
		unset($_SESSION['git_filename']);	
		unset($_SESSION['git_startLine']);
		unset($_SESSION['git_endLine']);
		
	}
 
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />

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
	<div id="response">
		<?php echo $message;?>
	</div>

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
	<h3><?php echo $_SESSION['git_url'] ?></h3>
    <div class="col-sm-12">
        <div id="chart"></div>
    </div>

    <div class="col-sm-12">
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
	if(jsonData){
		var data = JSON.parse(jsonData);
		buildTable(data);
		$(document).ready(function() {
			$('#sortable').DataTable({
				"order": [[ 4, "desc" ]]
			});
		});
		drawGraph();
	}
</script>

</div> <!-- /container -->

</body></html>

