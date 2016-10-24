function draw01(data){
    var width = 300,
    height = 280,
    radius = Math.min(width, height) / 2;
    
    //var color = d3.scale.ordinal()
    //.range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b"]);
    
    var color = d3.scale.category20c();
    
    var arc = d3.svg.arc()
    .outerRadius(radius - 10)
    .innerRadius(0);
    
    var labelArc = d3.svg.arc()
    .outerRadius(radius - 40)
    .innerRadius(radius - 40);
    
    var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) {
           return d.commit_num; });
    
    var svg = d3.select("div#chart").append("svg")
    .attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
    
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

    function type(d) {
        d.commit_num = +d.commit_num;
        return d;
    }

}

function buildTable(data){
    
    var overallInsertAndDeletions = 0;
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var insertNum = counter.totalIns;
        var deleteNum = counter.totalDel;
        overallInsertAndDeletions += insertNum + deleteNum;
    }
    
    console.log(totalInsertAndDeletions);
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var row = document.createElement("tr");
        var col1 = document.createElement("td"); //author
        var col2 = document.createElement("td"); //commits_num
        var col3 = document.createElement("td"); //insert
        var col4 = document.createElement("td"); //delete
        var col5 = document.createElement("td"); //num of changes
        
        var author = document.createTextNode(counter.name);
        var commitNum = document.createTextNode(counter.commit_num);
        var insertNum = document.createTextNode(counter.totalIns);
        var deleteNum = document.createTextNode(counter.totalDel);
        var totalInsertAndDeletions = counter.totalIns + counter.totalDel
        var numOfChanges = document.createTextNode((totalInsertAndDeletions/overallInsertAndDeletions*100).toFixed(2));
        
        col1.appendChild(author);
        col2.appendChild(commitNum);
        col3.appendChild(insertNum);
        col4.appendChild(deleteNum);
        col5.appendChild(numOfChanges);
        
        row.appendChild(col1);
        row.appendChild(col2);
        row.appendChild(col3);
        row.appendChild(col4);
        row.appendChild(col5);
        
        document.getElementById("tablebody01").appendChild(row);
    }
}
