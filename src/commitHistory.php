<?php
    session_start();
    $current_page = 'Commit History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
	
	$listContributors = $_SESSION['git_contributors'];
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        
        if(isset($_SESSION['git_start_date']) && !empty($_SESSION['git_start_date'])) {
            $result = execute('getcommithistory', null, null, $_SESSION['git_username'], $_SESSION['git_start_date'], null,'','');
			$title = $_SESSION['git_username']." From ".$_SESSION['git_start_date'];
        } else {
            $result = execute('getcommithistory', null, null, $_SESSION['git_username'], null, null,'','');
			$title = $_SESSION['git_username'];
        }
		$finalResult = generateTotalInsAndDelByDate($result);
        $result = json_encode($result);
    }
    
    if (isset($_POST["submit"])) {
		$_SESSION['git_username'] = $_POST['search1'];
		$startDate = $_POST['startDate'];
		if(empty($startDate)){
			$result = execute('getcommithistory',null,null,$_SESSION['git_username'],null,null,'','');
			$_SESSION['git_start_date'] = null;
			$title = $_SESSION['git_username'];
		} else {
			$result = execute('getcommithistory',null,null,$_SESSION['git_username'],$startDate,null,'','');
			$_SESSION['git_start_date'] = $startDate;
			$title = $_SESSION['git_username']." From ".$startDate;
		}
		$finalResult = generateTotalInsAndDelByDate($result);
		$result = json_encode($result);
    }
    
    function generateTotalInsAndDelByDate($result){
        $result = json_encode($result);
        $jsondata = json_decode($result, true);
        $out = array();
        $list = array();
        $previousDate = null;
        $currentDate = null;
        $totalNum = 0;
        foreach ($jsondata as $res){
            if($previousDate == null){
                $previousDate = $res["date"];
                $totalNum += 1;
            } else {
                $currentDate = $res["date"];
                if($previousDate != $currentDate){
                    $out["date"] = $previousDate;
                    $out["totalNum"] = $totalNum;
                    array_push($list, $out);
                    $totalNum = 0;
                }
            }
            $totalNum += 1;
            $previousDate = $res["date"];
        }
        if($previousDate != null){
            $out["date"] = $previousDate;
            $out["totalNum"] = $totalNum;
            array_push($list, $out);
        }
        
        return json_encode($list);
    }
	
?>

<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">
<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<link href="./assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	
<script src="https://d3js.org/d3.v4.min.js"></script></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="./assets/js/app02.js" type="text/javascript"></script>


<style>
	/*body {
  		font: 10px sans-serif;
	}*/

	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}
	
	.x.axis path {
	  display: none;
	}
	
	.line {
	  fill: none;
	  stroke: steelblue;
	  stroke-width: 1.5px;
	}
	
	.overlay {
	  fill: none;
	  pointer-events: all;
	}
	
	.focus circle {
	  fill: none;
	  stroke: steelblue;
	}
	
</style>

<div class="container">

<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
    <label>Insert Date</label>
	
	<form method="post" role="form" class="form-inline" action="commitHistory.php">
		<div class="form-group">
		<select class="selectpicker" data-live-search="true" data-style="btn-primary" id = "search1" name="search1"></select>
		</div>
		
		<div class="form-group">
			<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
				<input class="form-control" size="16" type="text" id="startDate" name="startDate">
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
		</div>
		
   <input class="btn btn-primary" id="submit" name="submit" value="Submit" type="submit">
</form>
</div>


<div class="row">
    <h3><?php echo $title ?></h3>
	
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
                    <th class="col-md-1">Date</th>
                    <th class="col-md-2">Hash</th>
                </tr>
            </thead>
        <tbody id="tablebody"></tbody>
        </table>
        </div>
    </div>

</div>

<script type="text/javascript">

	var obj01 = '<?php echo $listContributors ?>';
	if (obj01){
		var contributors = JSON.parse(obj01);
		loadSelectValue(contributors);
		document.getElementById("search1").value = '<?php echo $_SESSION['git_username']?>';
		document.getElementById("startDate").value = '<?php echo $_SESSION['git_start_date']?>';
	}

    $('.form_date').datetimepicker({
       language:  'en',
       weekStart: 1,
       todayBtn:  1,
       autoclose: 1,
       todayHighlight: 1,
       startView: 2,
       minView: 2,
       forceParse: 0
    });
	
	var currDate = '<?php echo $_SESSION['git_start_date']?>';
	if(currDate){
		document.getElementById("startDate").value = currDate;
	}

    jsonData = '<?php echo $finalResult ?>';
    var tableData = '<?php echo $result ?>';

    if (jsonData && tableData){
        data = JSON.parse(jsonData);
        drawLineGraph(data);
        
        tableData = JSON.parse(tableData);
        var githubLink = '<?php echo json_encode($_SESSION['git_url']) ?>';
        drawTable(tableData, githubLink);
        $(document).ready(function() {
			$('#sortable').DataTable();
		});
    }
	
</script>

</div> <!-- /container -->

</body></html>

