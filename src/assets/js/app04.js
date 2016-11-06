function drawBarGraph(data){
	
		var margin = {top: 20, right: 20, bottom: 30, left: 40},
		width = 960 - margin.left - margin.right,
		height = 500 - margin.top - margin.bottom;
		
		var x = d3.scale.ordinal().rangeRoundBands([0, width], .23);
		
		var y = d3.scale.linear().rangeRound([height, 0]);
		
		var color = d3.scale.category10();
		
		var xAxis = d3.svg.axis().scale(x).orient("bottom");
		
		var yAxis = d3.svg.axis().scale(y).orient("left").tickFormat(d3.format(".2s"));
		
		var svg = d3.select("div#chart").append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
			
		var divTooltip = d3.select("div#chart").append("div").attr("class", "toolTip");
		
		 color.domain(d3.keys(data[0]).filter(function(key) { return key !== "author"; }));
		
		  data.forEach(function(d) {
			var y0 = 0;
			d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });
			d.total = d.ages[d.ages.length - 1].y1;
		  });
		
		  data.sort(function(a, b) { return b.total - a.total; });
		
		  x.domain(data.map(function(d) { return d.author; }));
		  y.domain([0, d3.max(data, function(d) { return d.total; })]);
		
		  svg.append("g")
			  .attr("class", "x axis")
			  .attr("transform", "translate(0," + height + ")")
			  .call(xAxis)
			  .selectAll(".tick text")
			  .call(wrap, x.rangeBand());
		
		  svg.append("g")
			  .attr("class", "y axis")
			  .call(yAxis);
			   
		  var bar = svg.selectAll(".label")
				.data(data)
				.enter().append("g")
				.attr("class", "g")
				.attr("transform", function(d) { return "translate(" + x(d.author) + ",0)"; });
				
		 var bar_enter = bar.selectAll("rect")
			.data(function(d) { return d.ages; })
			.enter();

		bar_enter.append("rect")
			.attr("width", x.rangeBand())
			.attr("y", function(d) { return y(d.y1); })
			.attr("height", function(d) { return y(d.y0) - y(d.y1); })
			.style("fill", function(d) { return color(d.name); });
		
		bar
				.on("mousemove", function(d){
					divTooltip.style("left", d3.event.pageX+10+"px");
					divTooltip.style("top", d3.event.pageY-400+"px");
					divTooltip.style("display", "inline-block");
					var elements = document.querySelectorAll(':hover');
					l = elements.length
					l = l-1
					element = elements[l].__data__
					value = element.y1 - element.y0
					divTooltip.html((d.author)+"<br>"+element.name+"<br>"+value);
				});
		bar
				.on("mouseout", function(d){
					divTooltip.style("display", "none");
				});
				
			
	  var legend = svg.selectAll(".legend")
		  .data(color.domain().slice().reverse())
		  .enter().append("g")
		  .attr("class", "legend")
		  .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
	
	  legend.append("rect")
		  .attr("x", width - 18)
		  .attr("width", 18)
		  .attr("height", 18)
		  .style("fill", color);
	
	  legend.append("text")
		  .attr("x", width - 24)
		  .attr("y", 9)
		  .attr("dy", ".35em")
		  .style("text-anchor", "end")
		  .text(function(d) { return d; });
}

function wrap(text, width) {
  text.each(function() {
    var text = d3.select(this),
        words = text.text().split(/\s+/).reverse(),
        word,
        line = [],
        lineNumber = 0,
        lineHeight = 1.1, // ems
        y = text.attr("y"),
        dy = parseFloat(text.attr("dy")),
        tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
    while (word = words.pop()) {
      line.push(word);
      tspan.text(line.join(" "));
      if (tspan.node().getComputedTextLength() > width) {
        line.pop();
        tspan.text(line.join(" "));
        line = [word];
        tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
      }
    }
  });
}

function drawTable(data){
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var row = document.createElement("tr");
        var col1 = document.createElement("td"); //date
        var col2 = document.createElement("td"); //hash
        var col3 = document.createElement("td"); //author
        var col4 = document.createElement("td"); //ins
		var col5 = document.createElement("td"); //del
        
        var date = document.createTextNode(counter.date);
        var hash = document.createTextNode(counter.hash);
        var author = document.createTextNode(counter.author);
        var ins = document.createTextNode(counter.totalIns);
		var del = document.createTextNode(counter.totalDel);
		
		col1.appendChild(date);
        col2.appendChild(hash);
        col3.appendChild(author);
        col4.appendChild(ins);
		col5.appendChild(del);
        
        row.appendChild(col1);
        row.appendChild(col2);
        row.appendChild(col3);
        row.appendChild(col4);
		row.appendChild(col5);
        
        document.getElementById("tablebody01").appendChild(row);
    }
}
