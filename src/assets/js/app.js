var overallInsertAndDeletions = 0;
var nameTotalChanges = [];

function buildTable(data){
    
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var insertNum = counter.totalIns;
        var deleteNum = counter.totalDel;
        overallInsertAndDeletions += insertNum + deleteNum;
    }
    
    //console.log(totalInsertAndDeletions);
    for (var i = 0; i < data.length; i++) {
        var item = {};
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
        var totalInsertAndDeletions = counter.totalIns + counter.totalDel;
        var percentage = (totalInsertAndDeletions/overallInsertAndDeletions*100).toFixed(2);
        var numOfChanges = document.createTextNode(percentage);
        
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
        
        nameTotalChanges.push({label:counter.name, value:parseFloat(percentage)});
        
        document.getElementById("tablebody01").appendChild(row);
    }
}

function draw01(){
    
    var pie = new d3pie("chart", {
            size: {
                pieOuterRadius: "100%",
                canvasHeight: 360
            },
            data: {
                sortOrder: "value-asc",
                smallSegmentGrouping: {
                    enabled: true,
                    value: 2,
                    valueType: "percentage",
                    label: "Other authors"
                },
                content:
                    nameTotalChanges
                },
            tooltips: {
                enabled: true,
                type: "placeholder",
                string: "{label}: {value}%",
                // data is an object with the three properties listed below. Just modify the properties
                // directly - there's no need to return anything
                placeholderParser: function(index, data) {
                    data.label = data.label;
                    data.value = data.value.toFixed(2);
                },
                styles: {
                        fadeInSpeed: 500,
                        backgroundColor: "#00cc99",
                        backgroundOpacity: 0.8,
                        color: "#ffffcc",
                        borderRadius: 4,
                        font: "verdana",
                        fontSize: 20,
                        padding: 20
                }
                        
            }
            
   });
}
