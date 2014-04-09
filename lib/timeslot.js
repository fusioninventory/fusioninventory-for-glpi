var selectedtimeslot;

function setfocustimeslot(type) {
   selectedtimeslot = type + 'timeslot';
   
}

function eventrect(day, hour) {
   //$('#' + focustimeslot).val(day + '-' + hour);
   $("#beginday").find('select').select2("val", day);
}

function timeslot(jsonstr) {
   
   var json_data = JSON.parse(jsonstr);
   
   var ddays = new Array();
   for (var key in json_data) {
      ddays.push(key);
   }
   
   var margin = { top: 50, right: 0, bottom: 100, left: 80 },
       width = 944 - margin.left - margin.right,
       height = 190 - margin.top - margin.bottom,
       gridSize = width / (24*4),
       gridSizeHeight = 18,
       legendElementWidth = gridSize*2,
       colors = ["#e6e6e6", "#3f3f3f"]
       days = ddays,
       times = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];
      
   var data = [];

   for (d in json_data) {
     day_index = days.indexOf(d);
     for(i=0;i<(24*4); i+=1) {
       var val = 0;
       for (h in json_data[d]) {
         hour = json_data[d][h];
         if (i >= ((hour.start / 3600) * 4) && i < ((hour.end / 3600) * 4)) { val = 1};
       }
       var dec = 0;
       if((i % 4) == 0) {
          dec = 1;
       }
       data[day_index*(24*4) + i] = {"day":day_index+1, "hour": (i/4)+1, "value":val, "dec":dec};
     }
   };
   console.log(data);
   var svg = d3.select("#chart").append("svg")
       .attr("width", width + margin.left + margin.right)
       .attr("height", height + margin.top + margin.bottom)
       .append("g")
       .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

   var dayLabels = svg.selectAll(".dayLabel")
       .data(days)
       .enter().append("text")
         .text(function (d) { return d; })
         .attr("x", 0)
         .attr("y", function (d, i) { return i * gridSizeHeight; })
         .style("text-anchor", "end")
         .attr("transform", "translate(-6," + gridSizeHeight / 1.5 + ")")
         .attr("class", function (d, i) { return ((i >= 0 && i <= 4) ? "dayLabel mono axis axis-workweek" : "dayLabel mono axis"); });

   var timeLabels = svg.selectAll(".timeLabel")
       .data(times)
       .enter().append("text")
         .text(function(d) { return d; })
         .attr("x", function(d, i) { return (i * gridSize * 4) + 3; })
         .attr("y", 0)
         .style("text-anchor", "start")
         .attr("transform", "translate(0, -3)")
         .attr("class", function(d, i) { return ((i >= 7 && i <= 16) ? "timeLabel mono axis axis-worktime" : "timeLabel mono axis"); });
        
   svg.selectAll()
       .data(times)
       .enter().append("line")
       .attr("x1", function(d, i) { return (i * gridSize * 4) + 1; })
       .attr("y1", 0)
       .attr("x2", function(d, i) { return (i * gridSize * 4) + 1; })
       .attr("y2", -15)
       .style("stroke", "#e6e6e6");       

   var heatMap = svg.selectAll(".hour")
       .data(data)
       .enter().append("rect")
       .attr("x", function(d) { return ((d.hour - 1) * gridSize) * 4; })
       .attr("y", function(d) { return (d.day - 1) * gridSizeHeight; })
       .attr("class", "hour bordered")
       .attr("width", gridSize -1)
       .attr("height", gridSizeHeight - 1)
       .style("fill", colors[0])
       .on("mouseover", function(){ d3.select(this).style("fill", "#7f7");} )
       .on("mouseout", function(){ d3.select(this).style("fill", function(d) { return colors[d.value] });} )
       .on("click", function(d) {eventrect(d.day, d.hour)});

   heatMap.transition().duration(1000)
       .style("fill", function(d) { return colors[d.value] });

   heatMap.append("title").text(function(d) { return d.value; });
   content = $("#chart > svg");
   uriContent = "data:application/octet-stream," + encodeURIComponent(content);
}
