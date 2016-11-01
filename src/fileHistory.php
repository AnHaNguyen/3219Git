<?php
    session_start();
    $current_page = 'File History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
	
	$repoName = $_SESSION['repo_name'];
    
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

<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="https://d3js.org/d3.v4.min.js"></script></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>

<script src="assets/js/app04.js" type="text/javascript"></script>


<style>
	.ui-autocomplete {
		position: absolute;
		z-index: 1000;
		cursor: default;
		padding: 0;
		margin-top: 2px;
		list-style: none;
		background-color: #ffffff;
		border: 1px solid #ccc;
		-webkit-border-radius: 5px;
		   -moz-border-radius: 5px;
				border-radius: 5px;
		-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		   -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
				box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	}
	.ui-autocomplete > li {
	  padding: 3px 20px;
	}
	.ui-autocomplete > li.ui-state-focus {
	  background-color: #DDD;
	}
	.ui-helper-hidden-accessible {
	  display: none;
	}
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
    <div class="col-sm-12">
        <div id="chart"></div>
    </div>
	
	<div class="col-sm-12">
        <h3 class="sub-header"><?php echo $filename; ?></h3>
        <br/>
        <div class="table-responsive">
            <table id="sortable" class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1">Hash</th>
                        <th class="col-md-2">Author</th>
                        <th class="col-md-3">Date</th>
                        <th class="col-md-3">Lines</th>
                    </tr>
                </thead>
                <tbody id="tablebody01"></tbody>
            </table>
        </div>
    </div>
</div>

</div>

<script type="text/javascript">

	$(function() {
		var availableTags = <?php include('autocomplete.php'); ?>;
		$( "#basic-filename" ).autocomplete({
			source: availableTags,
        	autoFocus:true
		});
	});
	
	var jsonData = '<?php echo $result ?>';
	if (jsonData != ''){
		document.getElementById("basic-filename").value = '<?php echo $filename ?>';
		var data = JSON.parse(jsonData);
		drawTable(data);
		$(document).ready(function() {
			$('#sortable').DataTable();
		});
	}

    </script>

</div> <!-- /container -->

</body></html>

