<?php
    session_start();
    $current_page = 'Commit History';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        $result = execute('getcommithistory', $_SESSION['git_url'], null, $_SESSION['git_username'], null, null,'','');
        
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
        $out["date"] = $previousDate;
        $out["totalNum"] = $totalNum;
        array_push($list, $out);
        
        $finalResult = json_encode($list);

    }
    
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="./assets/js/app.js" type="text/javascript"></script>

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

    // set the dimensions and margins of the graph
    var margin = {top: 20, right: 20, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

    // parse the date / time
    var parseTime = d3.timeParse("%Y-%m-%d");

    // set the ranges
    var x = d3.scaleTime().range([0, width]);
    var y = d3.scaleLinear().range([height, 0]);

    // define the line
    var valueline = d3.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.totalNum); });

    // append the svg obgect to the body of the page
    // appends a 'group' element to 'svg'
    // moves the 'group' element to the top left margin
    var svg = d3.select("div#chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");

    // Scale the range of the data
    x.domain(d3.extent(data, function(d) { return d.date; }));
    y.domain([0, d3.max(data, function(d) { return d.totalNum; })]);

    // Add the valueline path.
    svg.append("path")
    .data([data])
    .attr("class", "line")
    .attr("d", valueline);

    // Add the X Axis
    svg.append("g")
    .attr("transform", "translate(0," + height + ")")
    .call(d3.axisBottom(x));

    // Add the Y Axis
    svg.append("g")
    .call(d3.axisLeft(y));

    function type(d) {
        d.date = parseTime(d.date);
        d.totalNum = +d.totalNum;
        return d;
    }

</script>

</div> <!-- /container -->

</body></html>

