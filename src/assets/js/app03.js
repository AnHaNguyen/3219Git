function drawCompareGraph(contributors, timedate, minDate, maxDate, maxYValue, user01, user02, user03){
	
	var ids = ["nil","nil","nil"]; 
	var valueLine, parseTime, svg;
	var minDate = minDate;
	var maxDate = maxDate;
	
	var formatMillisecond = d3.timeFormat(".%L"),
		formatSecond = d3.timeFormat(":%S"),
		formatMinute = d3.timeFormat("%I:%M"),
		formatHour = d3.timeFormat("%I %p"),
		formatDay = d3.timeFormat("%a %d"),
		formatWeek = d3.timeFormat("%b %d"),
		formatMonth = d3.timeFormat("%b"),
		formatYear = d3.timeFormat("%Y");
	
	$(document).ready(function() {	
		var margin = {top: 20, right: 20, bottom: 100, left: 50},
		width = 960 - margin.left - margin.right,
		height = 500 - margin.top - margin.bottom;
	
		valueline = d3.line()
			.x(function(d) { return x(d.date); })
			.y(function(d) { return y(d.totalNum); });
	
		svg = d3.select("#graph").append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
					.append("g")
					.attr("transform","translate(" + margin.left + "," + margin.top + ")");
	
		parseTime = d3.timeParse("%Y-%m-%d");
		var mindate = parseTime(minDate);
		var maxdate = parseTime(maxDate);
		
		if(minDate === maxDate){
			var x = d3.scaleTime().domain([0,maxdate]).range([0, width]);
		} else {
			var x = d3.scaleTime().domain([mindate,maxdate]).range([0, width]);
		}
		
		var x = d3.scaleTime().domain([mindate,maxdate]).range([0, width]);
		var y = d3.scaleLinear().domain([0,maxYValue]).range([height, 0]);
		
		var xAxis = d3.axisBottom(x).ticks(5).tickFormat(multiFormat);
	
		svg.append("g")
		  .attr("transform", "translate(0," + height + ")")
		  .call(xAxis);
	
		 svg.append("g")
		  .call(d3.axisLeft(y));
		  
		var name = user01;
		document.getElementById("search1").value = name;
		var toDraw = timedate[name];
		drawLine(toDraw,"blue","#first",0);
		ids[0] = "#first";
		
		name = user02;
		document.getElementById("search2").value = name;
		toDraw = timedate[name];
		if(toDraw){
			drawLine(toDraw,"green","#second",1);
			ids[1] = "#second";
		}

		name = user03;
		document.getElementById("search3").value = name;
		toDraw = timedate[name];
		if(toDraw){
			drawLine(toDraw,"red","#third",2);
			ids[2] = "#third";
		}

	});

	function drawLine(linedate,color,id,idspos) {
		if(ids[idspos] != "nil"){
        	document.getElementById(id).remove();
        } 	
		
		var temp = [];		
		linedate.forEach(function(d) {
			var tempD = Object.assign({}, d);
			tempD.date = parseTime(tempD.date);
			tempD.totalNum = tempD.totalNum;
			temp.push(tempD);
		});
    
	  	svg.append("path")
	  		.attr("d", valueline(temp))
	       	.attr("stroke", color)
	       	.attr("stroke-width", 2)
	        .attr("fill", "none")
	        .attr("id",id);
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
}


function loadSelectValue(contributors){
	for (i = 0; i < contributors.length; i++) { 
		var counter = contributors[i];
		
		var search1 = document.getElementById("search1");
		var search2 = document.getElementById("search2");
		var search3 = document.getElementById("search3");
		
		var option1 = document.createElement("option");
		var option2 = document.createElement("option");
		var option3 = document.createElement("option");
		
		option1.text = counter.Name;
		option2.text = counter.Name;
		option3.text = counter.Name;
		
		option1.value = counter.Name;
		option2.value = counter.Name;
		option3.value = counter.Name;
		
		search1.add(option1);
		search2.add(option2);
		search3.add(option3);
	}
}

function randomNumberFromRange(valueLength)
{
    return  Math.floor(Math.random()*(valueLength)+1);
}