function statHalfDonut(svgname, jsondata) {

   nv.addGraph(function() {

      var width = 400,
          height = 380;

      var chart = nv.models.pieChart()
          .x(function(d) { return d.key })
          .showLabels(false)
      //          .values(function(d) { return d.value })
      //          .color(function(d) {return d.data.color})
          .width(width)
          .height(height)
          .donut(true);

      chart.pie
          .startAngle(function(d) { return d.startAngle/2 -Math.PI/2 })
          .endAngle(function(d) { return d.endAngle/2 -Math.PI/2 });

      chart.legend.maxKeyLength(200);

      d3.select('#' + svgname)
          .datum(JSON.parse(jsondata))
          .transition().duration(1200)
          .attr('width', width)
          .attr('height', height)
          .call(chart);

      return chart;
   });
}


function statBar(svgname, jsondata, title) {

   nv.addGraph(function() {

      var width = 400,
          height = 380;

      var chart = nv.models.discreteBarChart()
          .x(function(d) { return d.label })
          .y(function(d) { return d.value })
          .width(width)
          .height(height)
          .staggerLabels(true)
          .showValues(false);

      d3.select('#' + svgname)
         .datum([JSON.parse(jsondata)])
         .attr('width', width)
         .attr('height', height)
         .call(chart);

      d3.select('#' + svgname)
         .append('text')
         .attr('x', 200)
         .attr('y', 12)
         .attr('text-anchor', 'middle')
         .style('font-weight', 'bold')
         .text(title);

      nv.utils.windowResize(chart.update);

      return chart;
   });
}