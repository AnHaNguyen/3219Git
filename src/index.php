<?php
    session_start();
    $current_page = 'Home';
    
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    execute($command='addrepo',$repo="https://github.com/jiaminw12/cs2102_stuffSharing");
    $result1 = execute($command='getcontributors',$repo="https://github.com/jiaminw12/cs2102_stuffSharing",null,$user="jiaminw12",null,null,'','');
    
    // use jquery.ajax
    // insert
    // /controller.php/command=addCommand?.....
?>

<script src="./assets/js/d3.min.js" type="text/javascript"></script>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="container">

<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
<h2>Visualize your GitHub Repos</h2>
<p>
<label for="basic-url">Your Github Repo URL</label>
<form method="post" class="form" role="form">
<input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
<p></p>
<button class="btn btn-primary" id="submit" name="submit" type="submit">Submit</button>
</form>
</p>
</div>

<div id="chart">
<ol id="langDetails"></ol>
</div>

    /* detect when the button is clicked
        call addRepo first
        call contributors
        call history*/
 
    <script type="text/javascript">
        var jsonData = '<?php echo $result1 ?>';
        console.log(jsonData);
        var data = JSON.parse(jsonData);

    /*var userName = [], commitNum = [];
 
    for (var i = 0; i < myJson.length; i++) {
     var counter = myJson[i];
     userName.push(counter.name);
     commitNum.push(counter.commit_num);
     }*/

    var width = 360,
    height = 300,
    radius = Math.min(width, height) / 2;
 
    var color = d3.scale.ordinal()
                .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b"]);
 
    var arc = d3.svg.arc()
                .outerRadius(radius - 10)
                .innerRadius(0);
 
    var labelArc = d3.svg.arc()
                    .outerRadius(radius - 40)
                    .innerRadius(radius - 40);
 
    var pie = d3.layout.pie()
                .sort(null)
                .value(function(d) { return d.commit_num; });
 
    var svg = d3.select("div#chart").append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
 
    function draw(){
        var g = svg.selectAll(".arc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "arc");
 
        g.append("path")
        .attr("d", arc)
        .style("fill", function(d) { return color(d.data.name); });
 
        g.append("text")
        .attr("transform", function(d) { return "translate(" + labelArc.centroid(d) + ")"; })
        .attr("dy", ".35em")
        .text(function(d) { return d.data.name; });
    }
 
    function type(d) {
        d.commit_num = +d.commit_num;
        return d;
    }
 
    draw();

    </script>

</div> <!-- /container -->

</body></html>

