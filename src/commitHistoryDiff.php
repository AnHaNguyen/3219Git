<?php
    session_start();
    $current_page = 'Commit History Diff';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
	
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
		
		$listContributors = $_SESSION['git_contributors'];
		
		if(isset($_SESSION['user01']) && !empty($_SESSION['user01']) && isset($_SESSION['user02']) && !empty($_SESSION['user02'])){
			$result1 = execute('getcommithistory',null,null,$_SESSION['user01'],$_SESSION['git_start_date'],null,'','');
			$finalResult = generateTotalInsAndDelByMonth($_SESSION['user01'], $result1);
			$result2 = execute('getcommithistory',null,null,$_SESSION['user02'],$_SESSION['git_start_date'],null,'','');
        	$finalResult2 = generateTotalInsAndDelByMonth($_SESSION['user02'], $result2);
		
			if(isset($_SESSION['user03']) && !empty($_SESSION['user03'])){
				$_SESSION['user03'] = $_POST['search3'];
				$result3 = execute('getcommithistory',null,null,$_SESSION['user03'],$_SESSION['git_start_date'],null,'','');
				$finalResult3 = generateTotalInsAndDelByMonth($_SESSION['user03'], $result3);
				$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2, $_SESSION['user03'] => $finalResult3);
			} else {
				$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2);
			}
		} else {
			
			if(isset($_SESSION['git_start_date']) && !empty($_SESSION['git_start_date'])) {
				$result = execute('getcommithistory', null, null, $_SESSION['git_username'], $_SESSION['git_start_date'], null,'','');
			} else {
				$result = execute('getcommithistory', null, null, $_SESSION['git_username'], null, null,'','');
			}
			
			$finalResult = generateTotalInsAndDelByMonth($_SESSION['git_username'], $result);
			$timedata = array($_SESSION['git_username'] => $finalResult);
		}
		$timedata = json_encode($timedata);
    }
	
	if (isset($_POST["submit"])) {
		$timedata = array();
        $_SESSION['user01'] = $_POST['search1'];
		$_SESSION['user02'] = $_POST['search2'];
		
        $result1 = execute('getcommithistory',null,null,$_SESSION['user01'],$_SESSION['git_start_date'],null,'','');
        $finalResult = generateTotalInsAndDelByMonth($_SESSION['user01'], $result1);
		
		$result2 = execute('getcommithistory',null,null,$_SESSION['user02'],$_SESSION['git_start_date'],null,'','');
        $finalResult2 = generateTotalInsAndDelByMonth($_SESSION['user02'], $result2);
		
		if(($_SESSION['git_total_contributors']) > 2 ) {
			$_SESSION['user03'] = $_POST['search3'];
			$result3 = execute('getcommithistory',null,null,$_SESSION['user03'],$_SESSION['git_start_date'],null,'','');
			$finalResult3 = generateTotalInsAndDelByMonth($_SESSION['user03'], $result3);
			$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2, $_SESSION['user03'] => $finalResult3);
		} else {
			$timedata = array($_SESSION['user01'] => $finalResult, $_SESSION['user02']=> $finalResult2);
		}
		$timedata = json_encode($timedata);
    }
	  
	function generateTotalInsAndDelByMonth($name, $result){
		
		global $smallestYear;
		global $smallestMonth;
		global $largestYear;
		global $largestMonth;
		
        $result = json_encode($result);
        $jsondata = json_decode($result, true);
        $out = array();
        $list = array();
        $previousMonth = null;
        $currentMonth = null;
        $totalNum = 0;
        foreach ($jsondata as $res){
            if($previousMonth == null){
                $previousMonth = getMonth($res["date"]);
				$previousYear = getYear($res["date"]);
                $totalNum = 1;
            } else {
                $currentMonth = getMonth($res["date"]);
                if((int)$previousMonth != (int)$currentMonth){
                    $out["date"] = $previousYear ."-".$previousMonth;
                    $out["totalNum"] = $totalNum;
                    array_push($list, $out);
					
					if($smallestMonth == '' && $smallestYear == ''){
						$smallestMonth = $previousMonth;
						$smallestYear = $previousYear;
					}
					
					if ((int)$previousYear < (int)$smallestYear){
						$smallestYear = $previousYear;			
						if ((int)$previousMonth < (int)$smallestMonth){
							$smallestMonth = $previousMonth;	
						}
					}
					
					$totalNum = 1;
                } else {
					$totalNum += 1;
				}
				$previousMonth = getMonth($res["date"]);
				$previousYear = getYear($res["date"]);
            }
        }
        if($previousMonth != null){
            $out["date"] = $previousYear ."-".$previousMonth;
            $out["totalNum"] = $totalNum;
            array_push($list, $out);
			
			if($largestMonth == '' && $largestYear == ''){
				$largestMonth = $previousMonth;
				$largestYear = $previousYear;
			}
			
			if ((int)$previousYear > (int)$largestYear){
				$largestYear = $previousYear;					
				if ((int)$previousMonth > (int)$largestMonth){
					$largestMonth = $previousMonth;
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
    
    ?>

<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">
	
<script src="https://d3js.org/d3.v4.min.js"></script></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
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
	var obj01 = '<?php echo $listContributors ?>';
	var contributors = JSON.parse(obj01);
	
	var timedate = <?php echo $timedata ?>;
	
	var minDate = "<?php echo $smallestYear."-".$smallestMonth ?>";
	var maxDate = "<?php echo $largestYear."-".$largestMonth ?>";
	
	var user01 = '<?php echo $_SESSION['user01'] ?>';
	var user02 = '<?php echo $_SESSION['user02'] ?>';
	var user03 = '<?php echo $_SESSION['user03'] ?>';
	
	console.log(minDate);
	console.log(maxDate);
	
	drawCompareGraph(contributors, timedate, minDate, maxDate, user01, user02, user03)
	
</script>

</div> <!-- /container -->

</body></html>

