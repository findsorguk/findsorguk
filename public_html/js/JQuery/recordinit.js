// JavaScript Document


$(document).ready(
	function(){
		var chart = $("#QRtag").chart();
		
		chart.width(200).height(200);
		
		chart.data(0).x(10,20,30,40,50).y([5,30,20,70,90]).color('0000ff');
		
		chart.xaxis(0).range(0, 50);
		
		chart.yaxis(0).range(0, 110);
		
		chart.data(0).label('Series 1');
		
		chart.data(0).markerType(MarkerType.Circle).markerSize(8).markerColor('00ff00');
		
		chart.type(ChartType.qr);
		
		chart.render();
	}
)