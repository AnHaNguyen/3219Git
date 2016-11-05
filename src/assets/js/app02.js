var formatMillisecond = d3.timeFormat(".%L"),
		formatSecond = d3.timeFormat(":%S"),
		formatMinute = d3.timeFormat("%I:%M"),
		formatHour = d3.timeFormat("%I %p"),
		formatDay = d3.timeFormat("%a %d"),
		formatWeek = d3.timeFormat("%b %d"),
		formatMonth = d3.timeFormat("%b"),
		formatYear = d3.timeFormat("%Y");

function drawLineGraph(data){
    
    // Set the dimensions of the canvas / graph
    var margin = {top: 20, right: 50, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 350 - margin.top - margin.bottom;
    
    // Parse the date / time
    var parseDate = d3.timeParse("%Y-%m-%d");
    var formatTime = d3.timeFormat("%b %d");
	var bisectDate = d3.bisector(function(d) { return d.date; }).left;
    
    // Set the ranges
    var x = d3.scaleTime().range([0, width]);
    var y = d3.scaleLinear().range([height, 0]);
    
    // Define the axes
    var xAxis = d3.axisBottom().scale(x).ticks(5).tickFormat(multiFormat);
    var yAxis = d3.axisLeft().scale(y).ticks(5);
    
    // Define the line
    var valueline = d3.line()
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
	
    // Add the X Axis
    svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
    
    // Add the Y Axis
    svg.append("g")
    .attr("class", "y axis")
    .call(yAxis);
 
	 var focus = svg.append("g")
      .attr("class", "focus")
      .style("display", "none");

	  focus.append("circle")
		  .attr("r", 4.5);
	
	  focus.append("text")
		  .attr("x", 9)
		  .attr("dy", ".35em");
	
	  svg.append("rect")
		  .attr("class", "overlay")
		  .attr("width", width)
		  .attr("height", height)
		  .on("mouseover", function() { focus.style("display", null); })
		  .on("mouseout", function() { focus.style("display", "none"); })
		  .on("mousemove", mousemove);
	
	  function mousemove() {
		var x0 = x.invert(d3.mouse(this)[0]),
			i = bisectDate(data, x0, 1),
			d0 = data[i - 1],
			d1 = data[i];
			d = x0 - d0.date > d1.date - x0 ? d1 : d0;
		focus.attr("transform", "translate(" + x(d.date) + "," + y(d.totalNum) + ")");
		//focus.select("text").text(formatTime(d.date) + " : " + d.totalNum);}
		focus.select("text").text(d.totalNum);
	}
	

}

function multiFormat(date) {
	  return (d3.timeSecond(date) < date ? formatMillisecond
		  : d3.timeMinute(date) < date ? formatSecond
		  : d3.timeHour(date) < date ? formatMinute
		  : d3.timeDay(date) < date ? formatHour
		  : d3.timeMonth(date) < date ? (d3.timeWeek(date) < date ? formatDay : formatWeek)
		  : d3.timeYear(date) < date ? formatMonth 
		  : formatYear)(date);
	}

function drawTable(tableData, githubLink){
    for (var i = 0; i < tableData.length; i++) {
        var counter = tableData[i];
        var row = document.createElement("tr");
        var col1 = document.createElement("td"); //date
        var col2 = document.createElement("td"); //hash
        
        var date = document.createTextNode(counter.date);
        var a = document.createElement("a");
        var hash = document.createTextNode(counter.hash);
        a.appendChild(hash);
        var link = githubLink.substring( 1, githubLink.indexOf(".git"));
        link = link.replace(/^"(.*)"$/,'$1');
        a.href = link+"/tree/"+counter.hash;
        a.setAttribute('target','_blank');
        
        col1.appendChild(date);
        col2.appendChild(a);
        
        row.appendChild(col1);
        row.appendChild(col2);
        
        document.getElementById("tablebody").appendChild(row);
    }
}

function loadSelectValue(contributors){
	for (i = 0; i < contributors.length; i++) { 
		var counter = contributors[i];
		var search1 = document.getElementById("search1");
		var option1 = document.createElement("option");
		option1.text = counter.Name;
		option1.value = counter.Name;
		search1.add(option1);
	}
}
