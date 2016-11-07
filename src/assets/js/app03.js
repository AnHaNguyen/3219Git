var formatMillisecond = d3.timeFormat(".%L"),
		formatSecond = d3.timeFormat(":%S"),
		formatMinute = d3.timeFormat("%I:%M"),
		formatHour = d3.timeFormat("%I %p"),
		formatDay = d3.timeFormat("%a %d"),
		formatWeek = d3.timeFormat("%b %d"),
		formatMonth = d3.timeFormat("%b"),
		formatYear = d3.timeFormat("%Y");
		
function drawCompareGraph(contributors, timedate, minDate, maxDate, maxYValue, user01, user02, user03){
	
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
		
		if(minDate === maxDate){
			var x = d3.scaleTime().domain([0,maxdate]).range([0, width]);
		} else {
			var x = d3.scaleTime().domain([mindate,maxdate]).range([0, width]);
		}
		
		var x = d3.scaleTime().domain([mindate,maxdate]).range([0, width]);
		var y = d3.scaleLinear().domain([0,maxYValue]).range([height, 0]);
		
		var xAxis = d3.axisBottom(x).tickFormat(multiFormat);
	
		svg.append("g")
		  .attr("transform", "translate(0," + height + ")")
		  .call(xAxis);
	
		 svg.append("g")
		  .call(d3.axisLeft(y));
		  
		var name = user01;
		if(name){
			document.getElementById("search1").value = name;
			var toDraw = timedate[name];
			drawLine(toDraw,"blue","#first",0);
			ids[0] = "#first";
		}
		
		name = user02;
		if(name){
			document.getElementById("search2").value = name;
			toDraw = timedate[name];
			drawLine(toDraw,"green","#second",1);
			ids[1] = "#second";
		}

		name = user03;
		if(name){
			document.getElementById("search3").value = name;
			toDraw = timedate[name];
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

function buildTable(timedate,user1,user2,user3,minDate, maxDate){
    
	var oneDay = 24*60*60*1000;
	var mindate = new Date(minDate);
	var maxdate = new Date(maxDate);
	var diffdate = Math.round((maxdate - mindate)/oneDay);
	var index1 = 0,index2 = 0,index3 = 0;
	var temp1 = [];
	var temp2 = [];
	var temp3 = [];
	for (var i = 0; i<= diffdate; i++){
		console.log(mindate, i);
		temp1 = getCommit(timedate[user1],user1,index1,mindate);
		temp2 = getCommit(timedate[user2],user2,index2,mindate);
			
		var row = document.createElement("tr");
        var col1 = document.createElement("td"); //date
        var col2 = document.createElement("td"); 
		var col3 = document.createElement("td");
		
		var tempDate = mindate;
		var day = tempDate.getDay();
		var month = tempDate.getMonth();
		var year = tempDate.getFullYear();
		
  		var str = tempDate.toString("yyyy-MM-dd");
		
		var date = document.createTextNode(str);
		var commit1 = document.createTextNode(temp1[0]);
		var commit2 = document.createTextNode(temp2[0]);
		
		col1.appendChild(date);
		col2.appendChild(commit1);
		col3.appendChild(commit2);
		
		row.appendChild(col1);
		row.appendChild(col2);
		row.appendChild(col3);
		
		if(user3){
			temp3 = getCommit(timedate[user3],user3,index3,mindate);
			var col4 = document.createElement("td");
			var commit3 = document.createTextNode(temp3[0]);
			col4.appendChild(commit3);
			row.appendChild(col4);
			index3 = temp3[1];
		}
		
		index1 = temp1[1];
		index2 = temp2[1];
		
		mindate.setDate(mindate.getDate() + 1);
		
		document.getElementById("tablebody").appendChild(row);
	}
}

function getCommit(data,user,index,date){
	var temp = [];
	console.log(data);
	console.log(data.length, index);
	//if data size is 0
	if(data.length == 0){
		temp.push(0);
		temp.push(index);
	}
	else if(data.length <= index){
		temp.push(0);
		temp.push(index);
	}
	
	//if data[index] is not equal to the date
	else if(new Date(data[index].date).getTime() == date.getTime()){
		temp.push(data[index].totalNum);
		temp.push(index+1);
	}
	//equal to date get totalnum of commit of the day and incremement index by 1
	else{
		temp.push(0);
		temp.push(index);
	}
	return temp;	
}