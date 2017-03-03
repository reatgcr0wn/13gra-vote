var width = 500,
  height = 500,
  radius = Math.min(width, height) / 2;

var size = {
  width : 500,
  height: 500
};


var color = d3.scale.category10();

var pie = d3.layout.pie()
  .value(function(d) {
    return d.value;
  })
  .sort(null);


var arc = d3.svg.arc()
  .innerRadius(radius - 100)
  .outerRadius(radius - 20);

var svg = d3.select("body").append("svg")
  .attr("width", width)
  .attr("height", height)
  .append("g")
  .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

var data = [{"label":"one", "value":1},
            {"label":"two", "value":4}];

var g = svg.datum(data).selectAll(".arc")
  .data(pie)
  .enter().append("g")
  .attr("class", "arc");

var path = g.append("path")
  .attr("d", arc)
  .style("fill", function(d,i) {
    return ["#3498db","#e74c3c"][i];
    // return color(d.value);
  })
  .each(function(d) {
    this._current = d;
  }); // store the initial angles

var text = g.append("text")
  .attr("transform", function(d) {
    return "translate(" + arc.centroid(d) + ")";
  })
  .attr("dy", ".35em")
  .text(function(d) {
    return d.value;
  });

d3.selectAll("input")
  .on("change", change);

function change() {
  // var value = this.value;
  pie.value(function(d) {
    return d.value;
  }); // change the value function
  console.log(pie.value);
  g = g.data(pie); // compute the new angles
  g.select("path")
    .transition()
    .duration(750)
    .attrTween("d", arcTween); // redraw the arcs
  g.select("text")
    .style("opacity", 0)
    .attr("transform", function(d) {
      return "translate(" + arc.centroid(d) + ")";
    })
    .text(function(d) {
      return d.value;
    })
    .transition()
    .duration(1000)
    .style("opacity", 1);
}

// function type(d) {
//   d.apples = +d.apples;
//   d.oranges = +d.oranges;
//   return d;
// }

// Store the displayed angles in _current.
// Then, interpolate from _current to the new angles.
// During the transition, _current is updated in-place by d3.interpolate.
function arcTween(a) {
  var i = d3.interpolate(this._current, a);
  this._current = i(0);
  return function(t) {
    return arc(i(t));
  };
}

//ajax

function getContent(timestamp)
{
    var queryString = {'timestamp' : timestamp};

    $.ajax(
        {
            type: 'GET',
            url: 'server.php',
            data: queryString,
            success: function(data){
                var obj = jQuery.parseJSON(data);
                // $('#response').html(obj.data_from_file);
                getContent(obj.timestamp);
                var obj = jQuery.parseJSON(obj.data_from_file);
                // console.log(obj);
                changeData(obj.vote.vote_01,obj.vote.vote_02);
            }
        }
    );
}

$(function() {
    getContent();
});

function changeData(vote_01,vote_02) {
 console.log(vote_01,vote_02);
 data[0].value = vote_01;
 data[1].value = vote_02;
 change();
}

 var win  = d3.select(window); //←リサイズイベントの設定に使用します

 function update(){

  // 自身のサイズを取得する
  size.width = parseInt(svg.style("width"));
  size.height = parseInt(svg.style("height")); //←取得はしていますが、使用していません...

  // 円グラフの外径を更新
  arc.outerRadius(size.width / 2);

  // 取得したサイズを元に拡大・縮小させる
  svg
    .attr("width", size.width)
    .attr("height", size.width);

  // それぞれのグループの位置を調整
  var g = svg.selectAll(".arc")
    .attr("transform", "translate(" + (size.width / 2) + "," + (size.width / 2) + ")");

  // パスのサイズを調整
  g.selectAll("path").attr("d", arc);
}

update();
win.on("resize", update); // ウィンドウのリサイズイベントにハンドラを設定
