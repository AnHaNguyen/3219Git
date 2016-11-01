function drawLineGraph(data){
    
    // Set the dimensions of the canvas / graph
    var margin = {top: 30, right: 20, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;
    
    // Parse the date / time
    var parseDate = d3.time.format("%Y-%m-%d").parse;
    var formatTime = d3.time.format("%e %b");
    
    // Set the ranges
    var x = d3.time.scale().range([0, width]);
    var y = d3.scale.linear().range([height, 0]);
    
    // Define the axes
    var xAxis = d3.svg.axis().scale(x)
    .orient("bottom").ticks(5);
    
    var yAxis = d3.svg.axis().scale(y)
    .orient("left").ticks(5);
    
    // Define the line
    var valueline = d3.svg.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.totalNum); });
    
    // Define the div for the tooltip
    var div = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);
    
    // Adds the svg canvas
    var svg = d3.select("div#chart")
    .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");
    
    data.forEach(function(d) {
                 d.date = parseDate(d.date);
                 d.totalNum = +d.totalNum;
    });
    
    // Scale the range of the data
    x.domain(d3.extent(data, function(d) { return d.date; }));
    y.domain([0, d3.max(data, function(d) { return d.totalNum; })]);
    
    // Add the valueline path.
    svg.append("path")
    .attr("class", "line")
    .attr("d", valueline(data));
    
    // Add the scatterplot
    svg.selectAll("dot")
    .data(data)
    .enter().append("circle")
    .attr("r", 3)
    .attr("cx", function(d) { return x(d.date); })
    .attr("cy", function(d) { return y(d.totalNum); })
    .on("mouseover", function(d) {
        div.transition()
        .duration(200)
        .style("opacity", .9);
        div.html(formatTime(d.date) + "<br/>"  + d.totalNum)
        .style("left", (d3.event.pageX) + "px")
        .style("top", (d3.event.pageY - 28) + "px");
        })
    .on("mouseout", function(d) {
        div.transition()
        .duration(500)
        .style("opacity", 0);
        });
    
    // Add the X Axis
    svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
    
    // Add the Y Axis
    svg.append("g")
    .attr("class", "y axis")
    .call(yAxis);

}

function drawTable(data){
    /*for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var insertNum = counter.totalIns;
        var deleteNum = counter.totalDel;
        overallInsertAndDeletions += insertNum + deleteNum;
    }*/
    
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var row = document.createElement("tr");
        var col1 = document.createElement("td"); //hash
        var col2 = document.createElement("td"); //author
        var col3 = document.createElement("td"); //date
        var col4 = document.createElement("td"); //lines
        
        var hash = document.createTextNode(counter.hash);
        var author = document.createTextNode(counter.author);
        var date = document.createTextNode(counter.date);
        var lines = document.createTextNode(counter.lines);
		console.log(lines.length);
        col1.appendChild(hash);
        col2.appendChild(author);
        col3.appendChild(date);
        col4.appendChild(lines);
        
        row.appendChild(col1);
        row.appendChild(col2);
        row.appendChild(col3);
        row.appendChild(col4);
        
        document.getElementById("tablebody01").appendChild(row);
    }
}
