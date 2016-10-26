<?php
    session_start();
    $current_page = 'Commit History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        //https://github.com/jiaminw12/cs2102_stuffSharing

        $result = execute('getcommithistory', $_SESSION['git_url'], null, $_SESSION['git_username'], null, null,'','');
        $result = json_encode($result);
        
        $jsondata = json_decode($result, true);
        // https://github.com/jiaminw12/cs2102_stuffSharing/tree/b3f1a8b
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
        $out["date"] = $previousDate;
        $out["totalNum"] = $totalNum;
        array_push($list, $out);
        
        $finalResult = json_encode($list);
    }
    
    ?>

<script src="https://d3js.org/d3.v3.min.js"></script></script>
<script src="./assets/js/app02.js" type="text/javascript"></script>

<style>
    path {
        stroke: steelblue;
        stroke-width: 2;
        fill: none;
    }

    .axis path,
    .axis line {
        fill: none;
        stroke: grey;
            stroke-width: 1;
            shape-rendering: crispEdges;
    }

    div.tooltip {
        position: absolute;
        text-align: center;
        width: 60px;
        height: 30px;
        padding: 2px;
        font: 12px sans-serif;
        background: lightsteelblue;
        border: 0px;
        border-radius: 8px;
        pointer-events: none;
    }
</style>

<div class="container">

<div class="row">
    <h3><?php echo $_SESSION['git_username']; ?></h3>
    <div class="col-xs-12">
        <div id="chart"></div>
    </div>

</div>

<script type="text/javascript">

    var jsonData = '<?php echo $finalResult ?>';
    console.log(jsonData);
    var data = JSON.parse(jsonData);

    drawLineGraph(data);

    </script>

</div> <!-- /container -->

</body></html>

