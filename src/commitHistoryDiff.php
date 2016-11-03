<?php
    session_start();
    $current_page = 'Commit History Diff';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
	
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
		
		// check whether the cuurent and previous is the same contributors list
		if (isset($_SESSION['current_contributors']) && !empty($_SESSION['current_contributors'])){
			//echo "line13";
			$listContributors = $_SESSION['git_contributors'];
			$jsonData1 = $_SESSION['current_contributors'];
			$jsonData2 = $listContributors;
			if($jsonData1 === $jsonData2){
				//echo "line18";
				if(isset($_SESSION['user01']) && !empty($_SESSION['user01']) && isset($_SESSION['user02']) && !empty($_SESSION['user02'])){
					$result1 = execute('getcommithistory',null,null,$_SESSION['user01'],$_SESSION['git_start_date'],null,'','');
					$finalResult = generateTotalInsAndDelByDate($_SESSION['user01'], $result1);
					$result2 = execute('getcommithistory',null,null,$_SESSION['user02'],$_SESSION['git_start_date'],null,'','');
					$finalResult2 = generateTotalInsAndDelByDate($_SESSION['user02'], $result2);
				
					if(isset($_SESSION['user03']) && !empty($_SESSION['user03'])){
						//echo "line26";
						$result3 = execute('getcommithistory',null,null,$_SESSION['user03'],$_SESSION['git_start_date'],null,'','');
						$finalResult3 = generateTotalInsAndDelByDate($_SESSION['user03'], $result3);
						$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2, $_SESSION['user03'] => $finalResult3);
					} else {
						$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2);
					}
				} else {
					//echo "line 34";
					$result = execute('getcommithistory', null, null, $_SESSION['git_username'], $_SESSION['git_start_date'], null,'','');
					$_SESSION['user01'] = $_SESSION['git_username'];
					$finalResult = generateTotalInsAndDelByDate($_SESSION['git_username'], $result);
					$timedata = array($_SESSION['git_username'] => $finalResult);
				}
			} else {
				//echo "line35";
				$timedata = isNewList();
			}
		} else {
			//echo "line49";
			$listContributors = $_SESSION['git_contributors'];
			$timedata = isNewList();
		}
		$timedata = json_encode($timedata);
    }
	
	function isNewList(){
		unset($_SESSION['user01']);
		unset($_SESSION['user02']);
		unset($_SESSION['user03']);
		if(isset($_SESSION['git_start_date']) && !empty($_SESSION['git_start_date'])) {
			$result = execute('getcommithistory', null, null, $_SESSION['git_username'], $_SESSION['git_start_date'], null,'','');
		} else {
			$result = execute('getcommithistory', null, null, $_SESSION['git_username'], null, null,'','');
		}
		$_SESSION['user01'] = $_SESSION['git_username'];
		$finalResult = generateTotalInsAndDelByDate($_SESSION['git_username'], $result);
		$timedata = array($_SESSION['git_username'] => $finalResult);
		return $timedata;
	}
	
	if (isset($_POST["submit"])) {
		$_SESSION['current_contributors'] = $_SESSION['git_contributors'];
		$timedata = array();
		$_SESSION['git_start_date'] = $_POST['startDate'];
        $_SESSION['user01'] = $_POST['search1'];
		$_SESSION['user02'] = $_POST['search2'];
		
        $result1 = execute('getcommithistory',null,null,$_SESSION['user01'],$_SESSION['git_start_date'],null,'','');
        $finalResult = generateTotalInsAndDelByDate($_SESSION['user01'], $result1);
		
		$result2 = execute('getcommithistory',null,null,$_SESSION['user02'],$_SESSION['git_start_date'],null,'','');
        $finalResult2 = generateTotalInsAndDelByDate($_SESSION['user02'], $result2);
		
		if(($_SESSION['git_total_contributors']) > 2 ) {
			$_SESSION['user03'] = $_POST['search3'];
			$result3 = execute('getcommithistory',null,null,$_SESSION['user03'],$_SESSION['git_start_date'],null,'','');
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
            }
            $totalNum += 1;
            $previousDate = $res["date"];
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
	
</style>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<form method="post" class="form" role="form" action="commitHistoryDiff.php">
				<div class="row row-centered">
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-primary" id = "search1" name="search1"></select>
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-success" id = "search2" name="search2"></select>
				<?php if(($_SESSION['git_total_contributors']) > 2 ) { ?>
					<select class="selectpicker col-centered col-fixed" data-live-search="true" data-style="btn-danger" id = "search3" name="search3"></select>
					<?php }?>
					<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
        <input class="form-control" size="16" type="text" id="startDate" name="startDate">
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
	
	var currDate = '<?php echo $_SESSION['git_start_date']?>';
	if(currDate){
		document.getElementById("startDate").value = currDate;
	}
	

	var obj01 = '<?php echo $listContributors ?>';
	
	if (obj01){
		var contributors = JSON.parse(obj01);
	
		var timedate = <?php echo $timedata ?>;
		
		var minDate = "<?php echo $smallestDate ?>";
		var maxDate = "<?php echo $largestDate ?>";
		var maxYValue = "<?php echo $maxYAxisValue; ?>";
		
		var user01 = '<?php echo $_SESSION['user01'] ?>';
		var user02 = '<?php echo $_SESSION['user02'] ?>';
		var user03 = '<?php echo $_SESSION['user03'] ?>';
		
		drawCompareGraph(contributors, timedate, minDate, maxDate, maxYValue, user01, user02, user03);
		
	}
	
	
</script>

</div> <!-- /container -->

</body></html>

