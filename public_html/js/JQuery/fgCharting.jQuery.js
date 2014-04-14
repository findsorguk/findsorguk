/**
 * --------------------------------------------------------------------
 * jQuery-Plugin "fgCharting"
 * by Scott Jehl, scott@filamentgroup.com
 * http://www.filamentgroup.com
 * reference article: http://www.filamentgroup.com/lab/creating_accessible_charts_using_canvas_and_jquery/
 * demo page: http://www.filamentgroup.com/examples/charting/
 * 
 * Copyright (c) 2008 Filament Group, Inc
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
 *
 * Usage Notes: please refer to our article above for documentation
 *  
 * Version: 1.0, 11.11.2007
 * Changelog:
 * 	
 * --------------------------------------------------------------------
 */

			//create Line graph function
			$.fn.createLineGraph = function(tableData, filledLine){
				//get passed values
				var members = tableData.members();
				var allData = tableData.allData();
				var dataSum = tableData.dataSum();
				var topValue = tableData.topValue();
				var memberTotals = tableData.memberTotals();
				var xLabels = tableData.xLabels();
				var yLabels = tableData.yLabels();


				if($(this).parents('.chartBlock').size()==0) $(this).wrap('<div class="chartBlock" style="position: relative;"></div>');
				if(!$(this).attr('scalable')){
					$(this).width($(this).width()/10+'em');
					$(this).height($(this).height()/10+'em');
					$(this).attr('scalable', 'true');
				}
				//set xLabels width
				var xInterval = Math.round($(this).width() / xLabels.length);
				xInterval+= Math.round(xInterval / xLabels.length+1);

				//write X labels
				var ulID = $(this).attr('id')+'_data';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(xLabels).each(function(){ $('#'+ulID).append('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'margin': 0, 'padding':0, 'list-style': 'none', 'float': 'left', 'width': xInterval/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0,'clear':'both'});

				//write Y labels
				var yScale = $(this).height() / topValue;
				var liHeight = $(this).height() / yLabels.length;
				liHeight += liHeight / yLabels.length;

				var ulID = $(this).attr('id')+'_dataY';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(yLabels).each(function(i){  $('#'+ulID).prepend('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'padding':0, 'list-style': 'none', 'height': liHeight/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0, 'position':'absolute', 'top': 0, 'text-align': 'right', 'left': '-5em', 'width': '40px'});


				
				var ctx = $(this).get(0);
				ctx = ctx.getContext('2d');
				//ctx.restore();
				ctx.clearRect(0,0, 1000, 1000);	
				ctx.save();	
				//start from the bottom left
				ctx.translate(0,$(this).height());
				for(var h=0; h<members.length; h++){
					ctx.beginPath();
					ctx.lineWidth = '3';
					ctx.lineJoin = 'round';
					var points = members[h].points;
					var integer = 0;
					ctx.moveTo(0,-Math.round(points[0]*yScale));
					for(var i=0; i<points.length; i++){
						ctx.lineTo(integer,-Math.round(points[i]*yScale));
						integer+=xInterval;
					}
					ctx.strokeStyle = tableData.members()[h].color;
					ctx.stroke();
					if(filledLine){
						ctx.lineTo(integer,0);
						ctx.lineTo(0,0);
						ctx.closePath();
						ctx.fillStyle = tableData.members()[h].color;
						ctx.globalAlpha = .3;
						ctx.fill();
						ctx.globalAlpha = 1.0;
					}
					else ctx.closePath();
				}
			}



			//create Line graph function
			$.fn.createAdditiveLineGraph = function(tableData, filledLine){
				//get passed values
				var members = tableData.members();
				var allData = tableData.allData();
				var dataSum = tableData.dataSum();
				var topValue = tableData.topValue();
				var memberTotals = tableData.memberTotals();
				var xLabels = tableData.xLabels();
				var yLabels = tableData.yLabels();
				var topYtotal = tableData.topYtotal();
				var yLabelsAdditive = tableData.yLabelsAdditive();

				if($(this).parents('.chartBlock').size()==0) $(this).wrap('<div class="chartBlock" style="position: relative;"></div>');
				if(!$(this).attr('scalable')){
					$(this).width($(this).width()/10+'em');
					$(this).height($(this).height()/10+'em');
					$(this).attr('scalable', 'true');
				}
				//set xLabels width
				var xInterval = Math.round($(this).width() / xLabels.length);
				xInterval+= Math.round(xInterval / xLabels.length+1);

				//write X labels
				var ulID = $(this).attr('id')+'_data';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(xLabels).each(function(){ $('#'+ulID).append('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'margin': 0, 'padding':0, 'list-style': 'none', 'float': 'left', 'width': xInterval/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0,'clear':'both'});

				//write Y labels
				var yScale = $(this).height() / topYtotal;
				var liHeight = $(this).height() / yLabelsAdditive.length;
				liHeight += liHeight / yLabelsAdditive.length;

				var ulID = $(this).attr('id')+'_dataY';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(yLabelsAdditive).each(function(i){  $('#'+ulID).prepend('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'padding':0, 'list-style': 'none', 'height': liHeight/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0, 'position':'absolute', 'top': 0, 'text-align': 'right', 'left': '-5em', 'width': '40px'});


				
				var ctx = $(this).get(0);
				ctx = ctx.getContext('2d');	
				//ctx.restore();
				ctx.clearRect(0,0, 1000, 1000);	
				ctx.save();					
				//start from the bottom left
				ctx.translate(0,$(this).height());
				for(var h=0; h<members.length; h++){
					ctx.beginPath();
					ctx.lineWidth = '3';
					ctx.lineJoin = 'round';
					var points = members[h].points;
					var prevPoints = [];
					if(members[h+1]){
						prevPoints = members[h+1].points;
					}
					var nextPrevPoints = [];
					if(members[h+2]){
						nextPrevPoints = members[h+2].points;
					}
					var integer = 0;
					ctx.moveTo(0,Math.round(-points[0]*yScale));
					for(var i=0; i<points.length; i++){
						var prevPoint = 0;
						var nextPrevPoint = 0;
						if(prevPoints[i]) prevPoint = prevPoints[i];
						if(nextPrevPoints[i]) nextPrevPoint = nextPrevPoints[i];
						ctx.lineTo(integer,Math.round((-points[i] - prevPoint - nextPrevPoint)*yScale));
						integer+=xInterval;
					}
					ctx.strokeStyle = tableData.members()[h].color;
					ctx.stroke();
					if(filledLine){
						ctx.lineTo(integer,0);
						ctx.lineTo(0,0);
						ctx.closePath();
						ctx.fillStyle = tableData.members()[h].color;
						//ctx.globalAlpha = 0.9;
						ctx.fill();
						//ctx.globalAlpha = 1.0;
					}
					else ctx.closePath();
				}
			}





			//create pie chart function
			//Credit: Some of the ideas for this pie chart plotting were borrowed from Plotkit http://www.liquidx.net/plotkit/. 
			$.fn.createPieChart = function(tableData){
					//get passed values
					var members = tableData.members();
					var allData = tableData.allData();
					var dataSum = tableData.dataSum();
					var topValue = tableData.topValue();
					var memberTotals = tableData.memberTotals();
					var xLabels = tableData.xLabels();
					var yLabels = tableData.yLabels();

					if($(this).parents('.chartBlock').size()==0) $(this).wrap('<div class="chartBlock" style="position: relative;"></div>');
					if(!$(this).attr('scalable')){
					$(this).width($(this).width()/10+'em');
					$(this).height($(this).height()/10+'em');
					$(this).attr('scalable', 'true');
				}
				
					function toRad(integer){
						return (Math.PI/180)*integer;
					}

					var ctx = $(this).get(0);
					ctx = ctx.getContext('2d');	
					//ctx.restore();
					ctx.clearRect(0,0, 1000, 1000);	
					ctx.save();	
					var centerx = $(this).width()/2;
					var centery = $(this).height()/2;
					var radius =  $(this).height()/2-20;
					//full circle - not needed in the end
					ctx.arc(centerx, centery, radius, toRad(0), toRad(360), true);
					ctx.fillStyle = '#ccc';
					ctx.fill();
					var counter = 0.0;

					var ulID = $(this).attr('id')+'_data';
					$('#'+ulID).remove();
					$(this).after('<ul id="'+ulID+'" style="position:absolute; list-style: none; top: -2.5em; left: -3em;"></ul>');
					
					//draw the pie pieces
					$(memberTotals).each(function(i){
						var fraction = this / dataSum;
						ctx.beginPath();
						ctx.moveTo(centerx, centery);
						ctx.arc(centerx, centery, radius, 
                   counter * Math.PI * 2 - Math.PI * 0.5,
                   (counter + fraction) * Math.PI * 2 - Math.PI * 0.5,
                   false);
	        ctx.lineTo(centerx, centery);
	        ctx.closePath();
	        
	        ctx.fillStyle = tableData.members()[i].color;
	        ctx.fill();


	        // draw labels
	        var sliceMiddle = (counter + fraction/2)
	        var labelx = centerx + Math.sin(sliceMiddle * Math.PI * 2) * (radius/2);
	        var labely = centery - Math.cos(sliceMiddle * Math.PI * 2) * (radius/2);

	        
        		$('#'+ulID).append('<li style="color:#fff; list-style: none !important; font-size: 1.1em; font-weight: bold; position: absolute; left:'+labelx/10+'em; top:'+labely/10+'em;">'+Math.round(fraction*100)+'%</li>');
	
                      
	      counter+=fraction;
					});
					
			}//end create pie





			//create bar graph function
			$.fn.createBarGraph = function(tableData){
				//get passed values
					var members = tableData.members();
					var allData = tableData.allData();
					var dataSum = tableData.dataSum();
					var topValue = tableData.topValue();
					var memberTotals = tableData.memberTotals();
					var xLabels = tableData.xLabels();
					var yLabels = tableData.yLabels();

					if($(this).parents('.chartBlock').size()==0) $(this).wrap('<div class="chartBlock" style="position: relative;"></div>');
					if(!$(this).attr('scalable')){
					$(this).width($(this).width()/10+'em');
					$(this).height($(this).height()/10+'em');
					$(this).attr('scalable', 'true');
				}
					
				//set xLabels width
				var xInterval = Math.round($(this).width() / xLabels.length);
				//xInterval+= xInterval / xLabels.length;

				//write X labels
				var ulID = $(this).attr('id')+'_data';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(xLabels).each(function(){ $('#'+ulID).append('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'margin': 0, 'padding':0, 'list-style': 'none', 'float': 'left', 'width': xInterval/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0});

				//write Y labels
				var yScale = $(this).height() / topValue;
				var liHeight = $(this).height() / yLabels.length;
				liHeight += liHeight / yLabels.length;

				var ulID = $(this).attr('id')+'_dataY';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(yLabels).each(function(i){  $('#'+ulID).prepend('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'padding':0, 'list-style': 'none', 'height': liHeight/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0, 'position':'absolute', 'top': 0, 'text-align': 'right', 'left': '-5em', 'width': '40px'});



				
				var ctx = $(this).get(0);
				ctx = ctx.getContext('2d');	
				//ctx.restore();
				ctx.clearRect(0,0, 1000, 1000);	
				ctx.save();	
				//start from the bottom left
				ctx.translate(0,$(this).height());
				for(var h=0; h<members.length; h++){
					ctx.beginPath();
					var linewidth = Math.round(xInterval / (members.length+1));
					ctx.lineWidth = linewidth;
					var points = members[h].points;
					var integer = 0;
					
					for(var i=0; i<points.length; i++){
						ctx.moveTo(Math.round(integer+(h*linewidth)), 0);
						ctx.lineTo(Math.round(integer+(h*linewidth)),Math.round(-points[i]*yScale));
						integer+=xInterval;
					}
					
					ctx.strokeStyle = tableData.members()[h].color;
					ctx.stroke();
					ctx.closePath();
				}
			}



			//create bar graph function
			$.fn.createAdditiveBarGraph = function(tableData){
				//get passed values
					var members = tableData.members();
					var allData = tableData.allData();
					var dataSum = tableData.dataSum();
					var topValue = tableData.topValue();
					var memberTotals = tableData.memberTotals();
					var xLabels = tableData.xLabels();
					var yLabels = tableData.yLabels();
					var topYtotal = tableData.topYtotal();
					var yLabelsAdditive = tableData.yLabelsAdditive();

					if($(this).parents('.chartBlock').size()==0) $(this).wrap('<div class="chartBlock" style="position: relative;"></div>');
					if(!$(this).attr('scalable')){
					$(this).width($(this).width()/10+'em');
					$(this).height($(this).height()/10+'em');
					$(this).attr('scalable', 'true');
				}
					
				//set xLabels width
				var xInterval = Math.round($(this).width() / xLabels.length);
				//xInterval+= xInterval / xLabels.length;

				//write X labels
				var ulID = $(this).attr('id')+'_data';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(xLabels).each(function(){ $('#'+ulID).append('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'margin': 0, 'padding':0, 'list-style': 'none', 'float': 'left', 'width': xInterval/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0});

				//write Y labels
				var yScale = $(this).height() / topYtotal;
				var liHeight = $(this).height() / yLabelsAdditive.length;
				liHeight += liHeight / yLabelsAdditive.length;

				var ulID = $(this).attr('id')+'_dataY';
				$('#'+ulID).remove();
				$(this).after('<ul id="'+ulID+'"></ul>');
				$(yLabelsAdditive).each(function(i){  $('#'+ulID).prepend('<li>'+this+'</li>');});
				$('#'+ulID+' li').css({'padding':0, 'list-style': 'none', 'height': liHeight/10+'em'});
				$('#'+ulID).css({'margin': 0, 'padding': 0, 'position':'absolute', 'top': 0, 'text-align': 'right', 'left': '-5em', 'width': '40px'});



				
				var ctx = $(this).get(0);
				ctx = ctx.getContext('2d');	
				//ctx.restore();
				ctx.clearRect(0,0, 1000, 1000);	
				ctx.save();				
				//start from the bottom left
				ctx.translate(0,$(this).height());
				for(var h=0; h<members.length; h++){
					ctx.beginPath();
					var linewidth = Math.round(xInterval*.8);
					ctx.lineWidth = linewidth;
					var points = members[h].points;
					var prevPoints = [];
					if(members[h+1]){
						prevPoints = members[h+1].points;
					}
					var nextPrevPoints = [];
					if(members[h+2]){
						nextPrevPoints = members[h+2].points;
					}
					var integer = 0;
					
					for(var i=0; i<points.length; i++){
						var prevPoint = 0;
						var nextPrevPoint = 0;
						if(prevPoints[i]) prevPoint = prevPoints[i];
						if(nextPrevPoints[i]) nextPrevPoint = nextPrevPoints[i];
						
						ctx.moveTo(integer, 0);
						ctx.lineTo(integer,Math.round((-points[i] - prevPoint - nextPrevPoint)*yScale));
						integer+=xInterval;
					}
					
					ctx.strokeStyle = tableData.members()[h].color;
					ctx.stroke();
					ctx.closePath();
				}
			}



			//graph data from table function
			$.fn.getTableData = function(chartDimensions){
					var tableObj = this;
					var colors = ['#be1e2d','#666699','#92d5ea','#ee8310','#8d10ee','#5a3b16','#26a4ed','#f45a90','#e9e744'];
					var tableData = {
						members: function(){
											var members = [];
											tableObj.find('tr:gt(0)').each(function(i){
												members[i] = {};
												members[i].points = [];
												members[i].color = colors[i];
												$(this).find('td').each(function(){
													members[i].points.push($(this).text()*1);
												});
											});
											//sort members array
											members.sort(function(a,b){
													var x = a.points.join(',').split(',');
													var y = b.points.join(',').split(',');
													var xSum = 0;
													var ySum = 0;
													$(x).each(function(){
															xSum += parseInt(this);
													});
													$(y).each(function(){
															ySum += parseInt(this);
													});
													return ((xSum < ySum) ? -1 : ((xSum > ySum) ? 1 : 0));
											});
											members.reverse();
											return members;
											
										},
						allData: function(){
											var allData = [];
											$(this.members()).each(function(){
												allData.push(this.points);
											});
											return allData;
										},
						dataSum: function(){
											var dataSum = 0;
											var allData = this.allData().join(',').split(',');
											$(allData).each(function(){
												dataSum += parseInt(this);
											});
											return dataSum
										},	
						topValue: function(){
												var topValue = 0;
												var allData = this.allData().join(',').split(',');
												$(allData).each(function(){
													if(parseInt(this)>topValue) topValue = parseInt(this);
												});
												return topValue;
										},
						memberTotals: function(){
											var memberTotals = [];
											var members = this.members();
											$(members).each(function(l){
												var count = 0;
												$(members[l].points).each(function(m){
													count +=members[l].points[m];
												});
												memberTotals.push(count);
											});
											return memberTotals;
										},
						yTotals: function(){
											var yTotals = [];
											var members = this.members();
											var loopLength = this.xLabels().length;
											for(var i = 0; i<loopLength; i++){
												yTotals[i] =[];
												var thisTotal = 0;
												$(members).each(function(l){
													yTotals[i].push(this.points[i]);
												});
												yTotals[i].join(',').split(',');
												$(yTotals[i]).each(function(){
													thisTotal += parseInt(this);
												});
												yTotals[i] = thisTotal;
												
											}
											return yTotals;
										},
						topYtotal: function(){
											var topYtotal = 0;
												var yTotals = this.yTotals().join(',').split(',');
												$(yTotals).each(function(){
													if(parseInt(this)>topYtotal) topYtotal = parseInt(this);
												});
												return topYtotal;
										},
						xLabels: function(){
											var xLabels = [];
											tableObj.find('tr:eq(0) th').each(function(){
												xLabels.push($(this).html());
											});
											return xLabels;
										},
						yLabels: function(){
											var yLabels = [];
											var chartHeight = chartDimensions.height;
											var numLabels = chartHeight / 30;
											var loopInterval = Math.round(this.topValue() / numLabels);

											for(var j=0; j<=numLabels; j++){
												yLabels.push(j*loopInterval);
											}
											if(yLabels[numLabels] != this.topValue()) {
												yLabels.pop();
												yLabels.push(this.topValue());
											}
											return yLabels;
										},
						yLabelsAdditive: function(){
											var yLabelsAdditive = [];
											var chartHeight = chartDimensions.height;
											var numLabels = chartHeight / 30;
											var loopInterval = Math.round(this.topYtotal() / numLabels);

											for(var j=0; j<=numLabels; j++){
												yLabelsAdditive.push(j*loopInterval);
											}
											if(yLabelsAdditive[numLabels] != this.topYtotal()) {
												yLabelsAdditive.pop();
												yLabelsAdditive.push(this.topYtotal());
											}
											return yLabelsAdditive;
										}				
					}
					if(!$(this).attr('colored')){
					$(this).find('tr:gt(0) th').each(function(i){
							$(this).css({'background-color': colors[i]});
					});
					$(this).attr('colored', 'true');
					}
					return tableData;

			}//end graphData








$.fgCharting = function(){
			$('[class^=fgCharting_]').each(function(){
				//get the class that contains fgCharting settings and set it to chartClass var
				var thisClass = $(this).attr('class');
				var chartClass = '';
				thisClass = thisClass.split(' ');
				$(thisClass).each(function(){
					if(this.match('fgCharting_')) chartClass = this;
				});

				//parse the chartClass var for chart settings
				var chartClass = chartClass.split('_');
				var chartSrc = '';
				var chartType = '';

				$(chartClass).each(function(){
					if(this.match('src-')) chartSrc = '#'+this.split('-')[1];
					if(this.match('type-')) chartType = this.split('-')[1];
				});

				var chartDimensions = {};
				chartDimensions.width = $(this).width();
				chartDimensions.height = $(this).height();
				

				//make sure data source and type is avail
				if($(chartSrc).size() > 0 && chartType != ''){
					//get data from source table
					var tableData = $(chartSrc).getTableData(chartDimensions);
					//create chart type specified
					switch (chartType){
						case 'line': $(this).createLineGraph(tableData);
						break
						case 'filledLine': $(this).createLineGraph(tableData, true);
						break
						case 'additiveLine': $(this).createAdditiveLineGraph(tableData);
						break
						case 'additiveFilledLine': $(this).createAdditiveLineGraph(tableData, true);
						break
						case 'pie': $(this).createPieChart(tableData);
						break
						case 'bar': $(this).createBarGraph(tableData);
						break
						case 'additiveBar': $(this).createAdditiveBarGraph(tableData);
						break
					}
				}
			});
}


