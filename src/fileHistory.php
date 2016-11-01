<?php
    session_start();
    $current_page = 'File History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
   
    /*if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url'])) {
        
        if(isset($_SESSION['git_start_date']) && !empty($_SESSION['git_start_date'])) {
            $result = execute('getcommithistory', null, null, $_SESSION['git_username'], $_SESSION['git_start_date'], null,'','');
        } else {
            $result = execute('getcommithistory', null, null, $_SESSION['git_username'], null, null,'','');
        }
        $result = json_encode($result);
    }*/
    
    if (isset($_POST["submit"])) {
        //https://github.com/jiaminw12/cs2102_stuffSharing
        //https://github.com/scrapy/scrapy <- cannot clone
        //102 - https://github.com/leereilly/games
        
		if(empty($_POST['basic-filename'])){
			$message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please insert a filename!</div>'; 
		} else {
			$filename = $_POST['basic-filename'];
			$_SESSION['git_filename'] = $filename;
			
			$startLine = $_POST['basic-start-line'];
			$endLine = $_POST['basic-end-line'];
			
			if($startLine != '' && $endLine != ''){
				$result = execute('getfilehistory',null,null,null,null,$filename,$startLine,$endLine);
			} else {
				$result = execute('getfilehistory',null,null,null,null,$filename,'','');
			}
				$result = json_encode($result);
			}
    }
    
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="https://d3js.org/d3.v3.min.js"></script></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>

<script src="assets/js/app04.js" type="text/javascript"></script>


<style>

</style>

<div class="container">

<!-- Main component for a primary marketing message or call to action -->
	<div id="response">
		<?php echo $message;?>
	</div>

<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
    <label>Insert File</label>
    <form method="post" class="form" role="form" action="fileHistory.php">
        <input type="text" class="form-control" id="basic-filename" name="basic-filename" aria-describedby="basic-addon3">
        <p></p>
        <input type="text" class="form-control" id="basic-start-line" name="basic-start-line" aria-describedby="basic-addon3">
        <p></p>
        <input type="text" class="form-control" id="basic-end-line" name="basic-end-line" aria-describedby="basic-addon3">
        <p></p>
        <input class="btn btn-primary" id="submit" name="submit" value="Submit" type="submit">
    </form>
    </p>
</div>


<div class="row">
    <div class="col-xs-12">
        <div id="chart"></div>
    </div>
</div>

</div>

<script type="text/javascript">

    /*jsonData = '<?php echo $finalResult ?>';
    var tableData = '<?php echo $result ?>';

    if (jsonData != '[]' && tableData != '[]'){
        data = JSON.parse(jsonData);
        drawLineGraph(data);
        
        tableData = JSON.parse(tableData);
        var githubLink = '<?php echo json_encode($_SESSION['git_url']) ?>';
        drawTable(tableData, githubLink);
        $(document).ready(function() {
                          $('#sortable').DataTable();
                          });
    }*/


    </script>

</div> <!-- /container -->

</body></html>

