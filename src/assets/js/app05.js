var overallInsertAndDeletions = 0;
var nameTotalChanges = [];

function buildTable(data){
    
    /*for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var insertNum = counter.lineNum;
        overallInsertAndDeletions += insertNum + deleteNum;
    }*/
    
    for (var i = 0; i < data.length; i++) {
        var counter = data[i];
        var row = document.createElement("tr");
        var col1 = document.createElement("td"); //author
        var col2 = document.createElement("td"); //line_num
        
        var author = document.createTextNode(counter.name);
        var lineNum = document.createTextNode(counter.lineNum);
        
        col1.appendChild(author);
        col2.appendChild(lineNum);
        
        row.appendChild(col1);
        row.appendChild(col2);
        
        nameTotalChanges.push({label:counter.name, value:counter.lineNum});
        document.getElementById("tablebody01").appendChild(row);
    }
}

function drawGraph(){
    
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
                string: "{label}: {value}",
                // data is an object with the three properties listed below. Just modify the properties
                // directly - there's no need to return anything
                placeholderParser: function(index, data) {
                    data.label = data.label;
                    data.value = data.value;
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
