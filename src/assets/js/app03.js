function drawCompareGraph(contributors, timedate, minDate, maxDate, maxYValue, user01, user02, user03){
	
	loadSelectValue(contributors);
	
	var ids = ["nil","nil","nil"]; 
	var valueLine, parseTime, svg;
	var minDate = minDate;
	var maxDate = maxDate;
	
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
		
		var x = d3.scaleTime().domain([mindate,maxdate]).range([0, width]);
		var y = d3.scaleLinear().domain([0,maxYValue]).range([height, 0]);
	
		svg.append("g")
		  .attr("transform", "translate(0," + height + ")")
		  .call(d3.axisBottom(x));
	
		 svg.append("g")
		  .call(d3.axisLeft(y));
		  
		var name = user01;
		document.getElementById("search1").value = name;
		var toDraw = timedate[name];
		drawLine(toDraw,"blue","#first",0);
		ids[0] = "#first";
		
		if (user02){
			name = user02;
			document.getElementById("search2").value = name;
			toDraw = timedate[name];
			drawLine(toDraw,"green","#second",1);
			ids[1] = "#second";
		} else {
			document.getElementById("search2").selectedIndex = randomNumberFromRange(document.getElementById("search2").value.length);		
		}

		if (user03){
			name = user03;
			document.getElementById("search3").value = name;
			toDraw = timedate[name];
			drawLine(toDraw,"red","#third",2);
			ids[2] = "#third";
		} else {
			document.getElementById("search3").selectedIndex = randomNumberFromRange(document.getElementById("search3").value.length);
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