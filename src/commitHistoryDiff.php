<?php
    session_start();
    $current_page = 'Commit History Diff';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
	
	$_SESSION['git_start_date_diff'] = $_SESSION['git_start_date'];
	$listContributors = $_SESSION['git_contributors'];
	
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
		
		// check whether the cuurent and previous is the same contributors list
		if (isset($_SESSION['current_contributors']) && !empty($_SESSION['current_contributors'])){
			$timedata = isNewList($_SESSION['user01'], $_SESSION['user02'], $_SESSION['user03'], $_SESSION['git_start_date_diff']);
		} else {
			$_SESSION['user01'] = $_SESSION['git_username'];
			$templateList = json_decode($listContributors, true);
			$_SESSION['user02'] = $templateList[1]['Name'];
			if(($_SESSION['git_total_contributors']) > 2 ){
				$_SESSION['user03'] = $templateList[2]['Name'];
			}
			$timedata = isNewList($_SESSION['user01'], $_SESSION['user02'],$_SESSION['user03'], $_SESSION['git_start_date_diff']);
		}
    }
	
	function isNewList($user01, $user02, $user03, $date){
		
		if(!empty($user01) && !empty($user02)){
			$result1 = execute('getcommithistory',null,null,$user01,$date,null,'','');
			$finalResult = generateTotalInsAndDelByDate($user01, $result1);
			
			$result2 = execute('getcommithistory',null,null,$user02,$date,null,'','');
			$finalResult2 = generateTotalInsAndDelByDate($user02, $result2);
		
			if(!empty($user03)){
				$result3 = execute('getcommithistory',null,null,$user03,$date,null,'','');
				$finalResult3 = generateTotalInsAndDelByDate($user03, $result3);
				$timedata = array($user01 => $finalResult, $user02 => $finalResult2, $user03 => $finalResult3);
			} else {
				$timedata = array($user01 => $finalResult, $user02=> $finalResult2);
			}
		} 
		
		return json_encode($timedata);
	}
	
	if (isset($_POST["submit"])) {
		$timedata = array();
		$_SESSION['current_contributors'] = $_SESSION['git_contributors'];
		$startDate = $_POST['startDate'];
        $_SESSION['user01'] = $_POST['search1'];
		$_SESSION['user02'] = $_POST['search2'];
		
		if(empty($startDate)){
			$_SESSION['git_start_date_diff'] = null;
		} else {
			$_SESSION['git_start_date_diff'] = $startDate;
		}
		
        $result1 = execute('getcommithistory',null,null,$_SESSION['user01'],$_SESSION['git_start_date_diff'],null,'','');
        $finalResult = generateTotalInsAndDelByDate($_SESSION['user01'], $result1);
		
		$result2 = execute('getcommithistory',null,null,$_SESSION['user02'],$_SESSION['git_start_date_diff'],null,'','');
        $finalResult2 = generateTotalInsAndDelByDate($_SESSION['user02'], $result2);
		
		if(($_SESSION['git_total_contributors']) > 2 ) {
			$_SESSION['user03'] = $_POST['search3'];
			$result3 = execute('getcommithistory',null,null,$_SESSION['user03'],$_SESSION['git_start_date_diff'],null,'','');
			$finalResult3 = generateTotalInsAndDelByDate($_SESSION['user03'], $result3);
			$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2, $_SESSION['user03'] => $finalResult3);
		} else {
			$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2);
		}
		$timedata = json_encode($timedata);
    }
	  
	function generateTotalInsAndDelByDate($name, $result){
		
		global $smallestDate;
		global $largestDate;
		global $maxYAxisValue;
		
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
				if($smallestDate == null){
					$smallestDate = $res["date"];
				}
                $totalNum = 1;
            } else {
                $currentDate = $res["date"];
                if($previousDate != $currentDate){
                    $out["date"] = $previousDate;
                    $out["totalNum"] = $totalNum;
                    array_push($list, $out);	
					
					if($maxYAxisValue == null){
						$maxYAxisValue = $totalNum;
					} else {
						if($maxYAxisValue < $totalNum){
							$maxYAxisValue = $totalNum;
						}
					}
													
					if($previousDate < $smallestDate){
						$smallestDate = $previousDate;	
					}
					
                    $totalNum = 0;
                }
            	$totalNum += 1;
           		$previousDate = $res["date"];
            }
        }
        if($previousDate != null){
            $out["date"] = $previousDate;
            $out["totalNum"] = $totalNum;
            array_push($list, $out);
			
			if($largestDate == null){
				$largestDate = $previousDate;	
			} else {
				
				if($maxYAxisValue < $totalNum){
					$maxYAxisValue = $totalNum;
				}
						
				if($previousDate > $largestDate){
					$largestDate = $previousDate;	
				}
			}
		}
		
		if(empty($maxYAxisValue)){
			$maxYAxisValue = $totalNum;
		}
		
        return $list;
    }
	
	function getMonth($date){
		return date('m', strtotime($date));	
	}
	
	function getYear($date){
		return date('Y', strtotime($date));	
	}
	
	function getDay($date){
		return date('d', strtotime($date));	
	}
    
    ?>

<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">
<link href="./assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	
<script src="https://d3js.org/d3.v4.min.js"></script></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="assets/js/app03.js" type="text/javascript"></script>

<style>

	#graph{
		margin-top: 35px;	
	}

	.row-centered {
		text-align:center;
	}
	
	.col-centered {
		display:inline-block;
		float:none;
		/* reset the text-align */
		text-align:left;
		/* inline-block space fix */
		margin-right:-4px;
	}
	
	.col-fixed {
		/* custom width */
		width:320px;
	}
	
	.axis {
	  font: 10px sans-serif;
	}
	
	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<form method="post" class="form" role="form" action="commitHistoryDiff.php">
				<div class="row row-centered">
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-primary" id = "search1" name="search1" ></select>
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-success" id = "search2" name="search2"></select>
				<?php if(($_SESSION['git_total_contributors']) > 2 ) { ?>
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-danger" id = "search3" name="search3"></select>
					<?php }?>
					
					<div class='col-md-5 col-centered'>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
				<input class="form-control" size="16" type="text" id="startDate" name="startDate">
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					</div>
					
					<input class="btn btn-primary" id="submit" name="submit" value="Submit" type="submit">
				</div>
			</form>
		</div>
		<br/>
		<div class="col-xs-12">
			<div id="graph"></div>
		</div>
	</div>

<script type="text/javascript">
	
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

	var currDate = '<?php echo $_SESSION['git_start_date_diff']?>';
	if(currDate){
		document.getElementById("startDate").value = currDate;
	}
	
	var obj01 = '<?php echo $listContributors ?>';
	
	if (obj01){
		var contributors = JSON.parse(obj01);
		
		loadSelectValue(contributors);
		
		var user01 = '<?php echo $_SESSION['user01'] ?>';
		var user02 = '<?php echo $_SESSION['user02'] ?>';
		if (!user02){
			document.getElementById("search2").selectedIndex = randomNumberFromRange(document.getElementById("search2").value.length);	
			user02 = document.getElementById("search2").value;
		}
		
		<?php if(($_SESSION['git_total_contributors']) > 2 ) {?>
			var user03 = '<?php echo $_SESSION['user03'] ?>';
			if(!user03){
				document.getElementById("search3").selectedIndex = randomNumberFromRange(document.getElementById("search3").value.length);
				user03 = 	document.getElementById("search3").value;	
			}
		<?php }  else {?>
			user03 = null;
		<?php }?>
	
		var timedate = <?php echo $timedata?>;
		
		var minDate = "<?php echo $smallestDate ?>";
		if(currDate){
			minDate = currDate;
		}
		
		var maxDate = "<?php echo $largestDate ?>";
		var maxYValue = "<?php echo $maxYAxisValue; ?>";
		
		drawCompareGraph(contributors, timedate, minDate, maxDate, maxYValue, user01, user02, user03);
		
	}

	
</script>

</div> <!-- /container -->

</body></html>

