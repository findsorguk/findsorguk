/*

jquery.gChart.js 0.1   gChart plugin

Copyright (c) 2007 Maurice Maltbia 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
$.fn.chart = function(){
		var $this = $(this);
		var chart = new Chart($this);	
		return chart;
};
function Chart(element){
	this.element = element;
	this.dataLists = [];
	this.ChartType = 'lc';
	this.chartWidth = 250;
	this.chartHeight = 150;
	this.titleValue = '';
	this.xaxes = [];
	this.yaxes = [];
	this.taxes = [];
	this.raxes = [];	
	this.xaxes[0] = this.x = new ChartAxis();	// shorthand for xaxis(0)
	this.yaxes[0] = this.y = new ChartAxis();	// shorthand for yaxis(0)
	this.taxes[0] = this.t = new ChartAxis();	// shorthand for taxis(0)
	this.raxes[0] = this.r = new ChartAxis();	// shorthand for raxis(0)
	this.gridLines = new GridLineStyle();
	};

Chart.prototype.title = function(t){
	this.titleValue = t;
	return this;
}

Chart.prototype.width = function(width){
	width = width > 1000 ? 1000 : width;
	width = width * this.chartHeight > 300000 ? 300000 / this.chartHeight : width;
	this.chartWidth = width;
	return this;
}

Chart.prototype.height = function(height){
	height = height > 1000 ? 1000 : height;
	height = this.chartWidth * height > 300000 ? 300000 / this.chartWidth : height;
	this.chartHeight = height;
	return this;
}
Chart.prototype.render = function(){
	var image;
	var container;
	if (arguments.length >= 1){
		container = $(arguments[0]);
		image = this.image();
		image.appendTo(container);
		
	}
	else if (this.element != null){
		image = this.image();
		image.appendTo($(this.element));
		var imageID = '';
		if ($(this.element).attr("id").length > 0){
			var elementId = $(this.element).attr("id")
			image.attr("id", elementId+ 'Chart');
		}
		else {
			var chartNumber = 0;
			while($("#"+chartNumber).get(0)){
				++chartNumber;
			}
			image.attr("id", 'chart'+chartNumber);
		}
	}
	return image;	
};

Chart.prototype.type = function(chartType){
	for(ctype in ChartType){
		if ( ChartType[ctype] == chartType){
			this.ChartType = chartType;
			break;
		}
	}
}

Chart.prototype.barHeight = function(height){
	this.BarHeight = height;
}

Chart.prototype.image = function(){
	var image = $("<img/>");
	image.attr("src", this.url());
	return image;
}

Chart.prototype.url = function(){
	var url = "http://chart.apis.google.com/chart?";
	url += 'chs='+ this.chartWidth +'x'+ this.chartHeight;
	url += '&cht='+ this.ChartType;
	if (this.BarHeight > 0){
		url += '&chbh='+ this.BarHeight;
	}
	
	// title
	if (this.titleValue.length > 0){
		url += '&chtt='+ this.titleValue.replace(/\n/g,'|').replace(/\s/g, '+'); 
	}
	
	//grid lines
		url += '&'+ this.gridLines.toString();
	
	// axis types
	var axisTypes = [];
	for (var xa = 0; xa < this.xaxes.length; xa++){
		if (this.xaxes[xa].IsEnabled())
			axisTypes.push('x');
	}
	for (var ya = 0; ya < this.yaxes.length; ya++){
		if (this.yaxes[ya].IsEnabled())
			axisTypes.push('y');
	}
	for (var ta = 0; ta < this.taxes.length; ta++){
		if (this.taxes[ta].IsEnabled())
			axisTypes.push('t');
	}
	for (var ra = 0; ra < this.raxes.length; ra++){
		if (this.raxes[ra].IsEnabled())
			axisTypes.push('r');
	}
	if (axisTypes.length > 0)
		url += '&chxt='+ axisTypes.join(',');
	
	// axis labels
	var axisIndex = -1;
	var axisLabels = [];
	
	for (var xa = 0; xa < this.xaxes.length; xa++){
		axisIndex++;
		if (this.xaxes[xa].axisLabels.length > 0){
			axisLabels.push(axisIndex+':');
			axisLabels.push(this.xaxes[xa].axisLabels.join('|'));
		}
	}
	for (var ya = 0; ya < this.yaxes.length; ya++){
		axisIndex++;
		if (this.yaxes[ya].axisLabels.length > 0){
			axisLabels.push(axisIndex+':');
			axisLabels.push(this.yaxes[ya].axisLabels.join('|'));
		}		
	}
	for (var ta = 0; ta < this.taxes.length; ta++){
		axisIndex++;
	}
	for (var ra = 0; ra < this.raxes.length; ra++){
		axisIndex++;
	}
	if (axisLabels.length > 0)
		url += '&chxl='+ axisLabels.join('|');
		
// axis range
	var axisIndex = -1;
	var axisRanges = [];
	var rangeValues = [];
	for (var xa = 0; xa < this.xaxes.length; xa++){
		axisIndex++;
		if (this.xaxes[xa].rangeStart != null && this.xaxes[xa].rangeEnd != null){
			rangeValues.push(axisIndex);
			rangeValues.push(this.xaxes[xa].rangeStart);
			rangeValues.push(this.xaxes[xa].rangeEnd);
			axisRanges.push(rangeValues.join(','));
		}
	}

	for (var ya = 0; ya < this.yaxes.length; ya++){
		axisIndex++;
		rangeValues = [];
		if (this.yaxes[ya].rangeStart != null && this.yaxes[ya].rangeEnd != null){
			rangeValues.push(axisIndex);
			rangeValues.push(this.yaxes[ya].rangeStart);
			rangeValues.push(this.yaxes[ya].rangeEnd);
			axisRanges.push(rangeValues.join(','));
		}		
	}
	for (var ta = 0; ta < this.taxes.length; ta++){
		axisIndex++;
	}
	for (var ra = 0; ra < this.raxes.length; ra++){
		axisIndex++;
	}
	if (axisRanges.length > 0)
		url += '&chxr='+ axisRanges.join('|');
		
	// axis style
	var axisIndex = -1;
	var axisStyles = [];
	for (var xa = 0; xa < this.xaxes.length; xa++){
		axisIndex++;
		if (this.xaxes[xa].colorValue != null){
			var currentStyle = [];
			currentStyle.push(axisIndex);
			currentStyle.push(this.xaxes[xa].colorValue);
			
			if (this.xaxes[xa].fontSizeValue != null)
				currentStyle.push(this.xaxes[xa].fontSizeValue);
				
			if (this.xaxes[xa].alignmentValue != null)
				currentStyle.push(this.xaxes[xa].alignmentValue);
				
			axisStyles.push(currentStyle.join(','));
		}
	}
	for (var ya = 0; ya < this.yaxes.length; ya++){
		axisIndex++;
	}
	for (var ta = 0; ta < this.taxes.length; ta++){
		axisIndex++;
	}
	for (var ra = 0; ra < this.raxes.length; ra++){
		axisIndex++;
	}
	if (axisStyles.length > 0)
		url += '&chxs='+ axisStyles.join('|');

		
	//legend
	var legendNames = [];
	var queryStringName = "";	

	if (this.ChartType != ChartType.Pie && this.ChartType != ChartType.Pie3D){
		for (var d = 0; d < this.dataLists.length; d++){
			if (this.dataLists[d].listLabel.length > 0)
				legendNames.push(this.dataLists[d].listLabel);
		}
		queryStringName = '&chdl=';
	}
	else {  // Pie chart legend
		for(var p = 0; p < this.dataLists[0].pointLabels.length; p++){
			legendNames.push(this.dataLists[0].pointLabels[p]);
		}
		queryStringName = '&chl=';
	}
	if (legendNames.length > 0)
		url += queryStringName + legendNames.join('|');

	// data set colors
	if (this.ChartType != ChartType.Scatter || this.ChartType != ChartType.Pie || this.ChartType != ChartType.Pie3D){
		var colorList = [];
		for(var ci = 0; ci < this.dataLists.length; ci++){
			if (this.dataLists[ci].colorValue.length > 0)
				colorList.push(this.dataLists[ci].colorValue);
		}
		url += '&chco='+colorList.join(',');
	}
	// shape markers
	if (this.ChartType == ChartType.Scatter ||this.ChartType == ChartType.Line || this.ChartType == ChartType.LineXY ){
		var markers = [];
		for (var d = 0; d < this.dataLists.length; d++){
			if (this.dataLists[d].markerTypeValue.length > 0){
				for(var v = 0; v < this.dataLists[d].xValues.length; v++){
					var marker = new Marker(v+1, this.dataLists[d].markerTypeValue, this.dataLists[d].markerColorValue, this.dataLists[d].markerSizeValue);
					marker.dataSet = d;
					markers.push(marker.toString());
				}
				for(var m = 0; m < this.dataLists[d].dataMarkers.length; m++){
					markers.push(this.dataLists[d].dataMarkers[m].toString());
				}
   
			}
		}
		url += '&chm='+ markers.join('|');
	}
	
	// line style
	if (this.ChartType == ChartType.Line || this.ChartType == ChartType.LineXY ){
	var styles = [];
	for (var d = 0; d < this.dataLists.length; d++){
			styles.push(this.dataLists[d].lineStyle.toString());
		}
		url += '&chls='+ styles.join('|');
	}
	
	//data
	url += '&chd=t:';
	for (var d = 0; d < this.dataLists.length; d++){
		var datalistYValues = this.dataLists[d].yValues;
		var datalistXValues = this.dataLists[d].xValues;
		if (this.ChartType == ChartType.Scatter){
			if (datalistXValues.length == 0){
				for(var x = 0; x < datalistYValues.length; x++){
					datalistXValues.push(x);
				}
			}
			url += datalistXValues.join(',') +'|';					
		}
		else if (this.ChartType == ChartType.LineXY){
			if (datalistXValues.length == 0){
				url += '-1|';
			}
			else {
				url += datalistXValues.join(',') + '|';					
			}
		}
		url += datalistYValues.join(',');
		/*for (var dl = 0; dl < datalist.length; dl++){
			url += data
		}*/
		if (d < this.dataLists.length - 1 && this.ChartType != ChartType.Scatter && this.ChartType != ChartType.Pie && this.ChartType != ChartType.Pie3D){
			url += '|';
		}
		if (this.ChartType == ChartType.Scatter || this.ChartType == ChartType.Pie || this.ChartType == ChartType.Pie3D){
			break;
		}
	} // end of datalist loop
	
	return url;
}


Chart.prototype.xaxis = function(axisIndex){
	if (this.xaxes[axisIndex] == null){
		this.xaxes[axisIndex] = new ChartAxis();
	}
	return this.xaxes[axisIndex];
}

Chart.prototype.yaxis = function(axisIndex){
	if (this.yaxes[axisIndex] == null){
		this.yaxes[axisIndex] = new ChartAxis();
	}
	return this.yaxes[axisIndex];
}

Chart.prototype.data = function(){
	var listIndex;
	if (arguments.length == 1){
		if (typeof arguments[0].constructor == Array){
			listIndex = 0;
			if (this.dataLists[listIndex] == null){
				this.dataLists[listIndex] = new DataList(listIndex);
			}		
			this.dataLists[listIndex].yValues = arguments[0];
		}
		else {
			listIndex = arguments[0];
		}
	}
	else {
		listIndex = 0;
	}
	if (this.dataLists[listIndex] == null){
		this.dataLists[listIndex] = new DataList(listIndex);
	}
	return this.dataLists[listIndex];
};


function DataList(){
	this.xValues = [];
	this.yValues = [];
	this.zValues = [];
	this.colorValue = '';
	this.marker = new Marker();
	this.markerTypeValue = '';
	this.markerColorValue = '0000FF';
	this.markerSizeValue = '3';
	this.dataMarkers = [];
	this.listID = -1;
	this.listLabel = '';
	this.pointLabels = [];
	this.lineStyle = new DataLineStyle();
	
	if (arguments.length == 1){
		this.listID = arguments[0];
	}
};
DataList.prototype.label = function(labelName){
	this.listLabel = labelName;
}
DataList.prototype.pointLabel = function(pointNum, labelName){
	if (arguments.length == 1){
		var isDataList = false;
		var arry = arguments[0];
		if (arguments[0].constructor == DataList){
			arry = arguments[0].yValues;
		}
		for(var l = 0; l < arry.length; l++){
			this.pointLabels[l] = new String(arry[l]);
		}
	}
	else if (arguments.length == 2){
		this.pointLabels[pointNum] = labelName;
	}
}
DataList.prototype.addMarker = function(markerObject){
	if (markerObject != null && markerObject.constructor == Marker){
		markerObject.dataSet = this.listID;
		this.dataMarkers.push(markerObject);
	}
	else if (markerObject != null && markerObject.constructor == Object){
		var newMarker = new Marker();
		for(var i in markerObject){
			if (i.toLowerCase() == 'dataPoint'.toLowerCase())
				newMarker[i] = markerObject[i];
			if (i.toLowerCase() == 'markerType'.toLowerCase())
				newMarker[i] = MarkerType.GetMarkerTypeValue(markerObject[i]);
			if (i.toLowerCase() == 'colorValue'.toLowerCase())
				newMarker[i] = markerObject[i];
			if (i.toLowerCase() == 'markerSize'.toLowerCase())
				newMarker[i] = markerObject[i];
		}
		this.dataMarkers.push(newMarker);		
	}
	return this;
}

DataList.prototype.removeMarker = function(xValue){
	for(var i = 0 ; i < this.dataMarkers.length; i++){
		if(this.dataMarkers[i].dataPoint == xValue){
			this.dataMarkers.splice(i, 1);
		}
	}
	return this;
}

DataList.prototype.color = function(colorString){
	this.colorValue = new String(colorString);
	if (this.markerColor.length == 0){
		this.markerColorValue = this.colorValue;
	}
	return this;
}

DataList.prototype.markerColor = function(colorString){
	this.markerColorValue = new String(colorString);
	return this;
}

DataList.prototype.markerType = function(mType){
	this.markerTypeValue = mType;
	return this;
}

DataList.prototype.markerSize = function(mSize){
	this.markerSizeValue = mSize;
	return this;
}

DataList.prototype.points = function(){
	if (arguments.length == 1 && typeof arguments[0].constructor == Array ){
		this.yValues = arguments[0];
	}
	else if (arguments.length > 0){
		for(var i = 0; i < arguments.length; i++){
			this.yValues.push(arguments[i]);
		}	
	}
	if (this.marker.markerType.length > 0){
		CreateMarkers();
	}
	

	return this;	
};

	DataList.prototype.x = function(){
	if (arguments[0].constructor == Array){
		this.xValues = arguments[0];
	}
	else if (arguments.length > 0){
		for(var i = 0; i < arguments.length; i++){
			this.xValues.push(arguments[i]);
		}		
	}
	return this;
};

DataList.prototype.y = function(){
	if (arguments[0].constructor == Array){
		this.yValues = arguments[0];
	}
	else if (arguments.length > 0){
		for(var i = 0; i < arguments.length; i++){
			this.yValues.push(arguments[i]);
		}		
	}
	return this;
};

function Marker(markerDataPoint, mType, mColor, mSize){
	this.markerType = '';
	this.colorValue = '0000FF';
	this.markerSize = 3;	
	this.dataSet = 0;
	this.dataPoint = 0;
	
	if (arguments.length > 0){
		if (arguments.length >= 1){
			this.dataPoint = markerDataPoint;
		}
		if (arguments.length >= 2){
			this.markerType = mType;
		}
		if (arguments.length >= 3){
			this.colorValue = mColor;
		}
		if (arguments.length >= 4){
			this.markerSize = mSize;
		}		
	}
}

Marker.prototype.type = function(type){
	for(mtype in MarkerType){
		if ( MarkerType[mtype] == type){
			this.markerType = type;
			break;
		}
	}
	return this;
}

Marker.prototype.color = function(color){
	this.colorValue = new String(colorString);
	return this;
}

Marker.prototype.size = function(markerSize){
	this.size = markerSize;
	return this;
}

Marker.prototype.toString = function(){
	var stringData = [];
	stringData.push(this.markerType);
	stringData.push(this.colorValue);
	stringData.push(this.dataSet);
	stringData.push(this.dataPoint);
	stringData.push(this.markerSize);
	return stringData.join(',');
}

function LineStyle(){
	this.segmentLength = 1;
	this.blankSegmentLength = 0;
}

LineStyle.prototype.segment = function(sl){
	this.segmentLength = sl;
	return this;
}

LineStyle.prototype.blankSegment = function(bsl){
	this.blankSegmentLength = bsl;
	return this;
}

function DataLineStyle(){
	this.thickness = 1;
	this.segmentLength = 0;
}

DataLineStyle.prototype = new LineStyle();

DataLineStyle.prototype.toString = function(){
	if (this.segmentLength == 0){
		return '';
	}
	var style = [this.thickness, this.segmentLength, this.blankSegmentLength];
	return style.join(',');
}

DataLineStyle.prototype.width = function(w){
	this.thickness = w;
	return this;
}

function GridLineStyle(){
	this.xAxisStep = 0;
	this.yAxisStep = 0;
	this.urlParamName = 'chg';
}
GridLineStyle.prototype = new LineStyle();

GridLineStyle.prototype.xStep = function(xs){
	this.xAxisStep = xs;
	return this;
}

GridLineStyle.prototype.yStep = function(ys){
	this.yAxisStep = ys;
	return this;
}

GridLineStyle.prototype.toString = function(){
	var style = [];
	if (this.xAxisStep == 0 && this.yAxisStep == 0){
		return "";
	}
	style.push(this.xAxisStep);
	style.push(this.yAxisStep);
	if (this.segmentLength > 0){
		style.push(this.segmentLength);
		style.push(this.blankSegmentLength);
	}
	return this.urlParamName +'='+ style.join(',');
}

var MarkerType = {
	Arrow: 'a',
	Cross: 'c',
	Diamond: 'd',
	Circle: 'o',
	Square: 's',
	VLine: 'v',
	VLineTop: 'V',
	HLine: 'h',
	X: 'x',
	GetMarkerTypeValue: function (markerTypeObject){
		var markerTypeValue = '';
		if (markerTypeObject != null && markerTypeObject.constructor == String && markerTypeObject.length > 1){
			for(var name in MarkerType){
				if (name.toLowerCase() == markerTypeObject.toLowerCase()){
					markerTypeValue = MarkerType[name];
					break;
				}
			} 
		}
		else if (markerTypeObject != null && markerTypeObject.constructor == String && markerTypeObject.length == 1){
			for(var name in MarkerType){
				if (MarkerType[name].toLowerCase() == markerTypeObject.toLowerCase()){
					markerTypeValue = MarkerType[name];
					break;
				}
			}	
		}
		return markerTypeValue;
	}
}
var ChartType = {
	Line: 'lc',
	LineXY: 'lxy',
	Bar: 'bhs',
	BarHorizontal: 'bhs',
	BarVertical: 'bvs',
	BarHorizontalGroup: 'bhg',
	BarGroup: 'bhg',
	BarVerticalGroup: 'bvg',
	Pie: 'p',
	Pie3D: 'p3',
	Venn: 'v',
	Scatter: 's'
};

function ChartAxis(){
	this.axisLabels = [];
	this.rangeStart = null;
	this.rangeEnd = null;
	this.colorValue = null;
	this.fontSizeValue = null
	this.alignmentValue = null;
	var enabled = false;
	
	function setEnableStatus(enableStatus){
		enabled = enableStatus;
	}
	
	this.labels = function(){
		if (arguments.length > 0){
			for(var i = 0; i < arguments.length; i++){
				this.axisLabels[i] = arguments[i];
			}
		}
		setEnableStatus(true);
		return this;
	}	

	this.IsEnabled = function(){
		return enabled;
	}

	this.color = function(hexColor){
		this.colorValue = hexColor;
		setEnableStatus(true);
		return this;
	}

	this.fontSize = function(fontSize){
		this.fontSizeValue = fontSize;
		setEnableStatus(true);
		return this;
	}

	this.alignment = function(alignment){
		switch(alignment){
			case 'left':
				this.alignmentValue = -1;
				setEnableStatus(true);
				break;
			case 'center':
				this.alignmentValue = 0;
				setEnableStatus(true);
				break;
			case 'right':
				this.alignmentValue = 1;
				setEnableStatus(true);
				break;
		}

		return this;
	}

	this.range = function(start,end){
		if (!isNaN(parseFloat(start)) && !isNaN(parseFloat(end))){
			this.rangeStart = start;
			this.rangeEnd = end;
		}
		setEnableStatus(true);
		return this;
	}	
}


