/*
 *
 * TableSorter - Client-side table sorting with ease!
 *
 * Copyright (c) 2006 Christian Bach (http://motherrussia.polyester.se)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * jQueryDate: 
 * jQueryAuthor: Christian jQuery
 *
 */
(function($) {

	$.fn.tableSorter = function(o) {
	
		var defaults =  {
			sortDir: 0,
			sortColumn: null,
			sortClassAsc: 'ascending',
			sortClassDesc: 'descending',
			headerClass: null,
			stripingRowClass: false,
			highlightClass: false,
			rowLimit: 0,
			minRowsForWaitingMsg: 0,
			disableHeader: -1,
			stripeRowsOnStartUp: false,
			columnParser: false,
			rowHighlightClass: false,
			useCache: true,
			debug: false,
			textExtraction: 'simple',
			textExtractionCustom: false,
			textExtractionType: false,
			bind: true,
			addHeaderLink: false,
			lockedSortDir: false,
			enableResize: false,
			dateFormat: 'mm/dd/yyyy' /** us default, uk dd/mm/yyyy */
		};
	 
		return this.each(function(){
			
			/** merge default with custom options */
			$.extend(defaults, o);
	
			/** Private vars */
			var COLUMN_DATA;			/** array for storing columns */
			var COLUMN_CACHE;			/** array for storing sort caches.*/
			var COLUMN_INDEX;				/** int for storing current cell index */
			var COLUMN_SORTER_CACHE = [];	/** array for sorter parser cache */
			var COLUMN_CELL;				/** stores the current cell object */
			var COLUMN_DIR;					/** stores the current soring direction */
			var COLUMN_HEADER_LENGTH;		/** stores the columns header length */
			var COLUMN_ROW_LENGTH;
			var ROW_LAST_HIGHLIGHT_OBJ = false;
			var COLUMN_LAST_INDEX = -1;
			var COLUMN_LAST_DIR = defaults.sortDir;
			
			/** table object holder.*/
			var oTable = this;
	
			if(defaults.stripeRowsOnStartUp && defaults.stripingRowClass) {
				$.tableSorter.utils.stripeRows(defaults,oTable);
			}
			
			/** bind events to the tablesorter element */
			$(this).bind("resort",doSorting);
			
			$(this).bind("flushCache",function(event) {
				COLUMN_CACHE = [];
			});
			
			$(this).bind("updateColumnData",buildColumnDataIndex);
			
			/** Store length of table rows. */
			var tableRowLength = (oTable.tBodies[0] && oTable.tBodies[0].rows.length-1) || 0;
	
			/** Index column data. */
			buildColumnDataIndex();
			
			/** when done, build headers. */
			buildColumnHeaders();
	
			function buildColumnHeaders() {
				var oFirstTableRow = oTable.rows[0];
				var oDataSampleRow = oTable.rows[1];
				/** store column length */
				COLUMN_HEADER_LENGTH = oFirstTableRow.cells.length;
				/** loop column headers */
				for( var i=0; i < COLUMN_HEADER_LENGTH; i++ ) {
					var oCell = oFirstTableRow.cells[i];
	
					if(oDataSampleRow && !$.tableSorter.utils.isHeaderDisabled(defaults,oCell,defaults.disableHeader,i)) {
						/** get current cell from columns headers */
						var oCellValue = $.tableSorter.utils.getElementText(defaults,oDataSampleRow.cells[i],'columns',i);
						/** check for default column. */
						if(typeof(defaults.sortColumn) == "string") {
							if(defaults.sortColumn.toLowerCase() == $.tableSorter.utils.getElementText(defaults,oCell,'header',i).toLowerCase()) {
								defaults.sortColumn = i;
							}
						}
	
						/** get sorting method for column. */
						COLUMN_SORTER_CACHE[i] = $.tableSorter.analyzer.analyseString(defaults,oCellValue);
						
						/** if we have a column parser, set it manual. */
						if(defaults.columnParser) {
							var a = defaults.columnParser;
							var l = a.length;
							for(var j=0; j < l; j++) {
								if(i == a[j][0]) {
									COLUMN_SORTER_CACHE[i] = $.tableSorter.analyzer.getById(a[j][1]);
									continue;
								}
							}
						}
	
						if(defaults.headerClass) {
							$(oCell).addClass(defaults.headerClass);
						}
						if(defaults.addHeaderLink) {
							$(oCell).wrapInner({element: '<a href="#">', name: 'a', className: 'sorter'});
	
							$(".sorter",oCell).click(function(e) {
								sortOnColumn( $(this).parent(), ((defaults.lockedSortDir) ? defaults.lockedSortDir : $(this).parent()[0].count++) % 2, $(this).parent()[0].index );
								return false;
							});
						} else {
							$(oCell).click(function(e) {
								sortOnColumn( $(this), ((defaults.lockedSortDir) ? defaults.lockedSortDir : $(this)[0].count++) % 2, $(this)[0].index );
								return false;
							});
						}
						oCell.index = i;
						oCell.count = 0;
					}
				}
				/** comming feature. */
				if(defaults.enableResize) {
					addColGroup(oFirstTableRow);
				}
				/** if we have a init sorting, fire it! */
				if(defaults.sortColumn != null) {
					$(oFirstTableRow.cells[defaults.sortColumn]).trigger("click");
				}
	
				if(defaults.rowHighlightClass) {
					$("> tbody:first/tr",oTable).click(function() {
						if(ROW_LAST_HIGHLIGHT_OBJ) {
							ROW_LAST_HIGHLIGHT_OBJ.removeClass(defaults.rowHighlightClass);
						}
						ROW_LAST_HIGHLIGHT_OBJ = $(this).addClass(defaults.rowHighlightClass);
					});
				}
			}
			/** break out and put i $.tableSorter? */
			function buildColumnDataIndex() {
				/** make colum data. */
				COLUMN_DATA = [];
				COLUMN_CACHE = [];
				COLUMN_ROW_LENGTH = (oTable.tBodies[0] && oTable.tBodies[0].rows.length) || 0;
				var l = COLUMN_ROW_LENGTH;
				for (var i=0;i < l; i++) {
					/** Add the table data to main data array */
					COLUMN_DATA.push(oTable.tBodies[0].rows[i]);
				}
			}
				
			function addColGroup(columnsHeader) {
				var oSampleTableRow = oTable.rows[1];
				/** adjust header to the sample rows */
				for(var i=0; i < COLUMN_HEADER_LENGTH; i++) {
					if(oSampleTableRow && oSampleTableRow.cells[i])
						$(columnsHeader.cells[i]).css("width",oSampleTableRow.cells[i].clientWidth + "px");
				}
			}
			
			function sortOnColumn(oCell,dir,index) {
				/** trigger event sort start. */
				if(tableRowLength > defaults.minRowsForWaitingMsg) {
					$(oTable).trigger( "sortStart");
				}
				/** define globals for current sorting. */
				COLUMN_INDEX = index;
				COLUMN_CELL = oCell;
				COLUMN_DIR = dir;
				/** clear all classes, need to be optimized. */
				$("thead th",oTable).removeClass(defaults.sortClassAsc).removeClass(defaults.sortClassDesc);
				/**add active class and append image. */
				$(COLUMN_CELL).addClass((dir % 2 ? defaults.sortClassAsc : defaults.sortClassDesc));
				/** if this is fired, with a straight call, sortStart / Stop would never be fired. */
				setTimeout(doSorting,0);
			}
			
			function doSorting() {
				/** added check to see if COLUMN_INDEX is set */
				if(COLUMN_INDEX >= 0) {
					/** array for storing sorted data. */
					var columns;
					/** sorting exist in cache, get it. */
					if($.tableSorter.cache.exist(COLUMN_CACHE,COLUMN_INDEX) && defaults.useCache) {
						/** get from cache */
						var cache = $.tableSorter.cache.get(COLUMN_CACHE,COLUMN_INDEX);
						/** figure out the way to sort. */
						if(cache.dir == COLUMN_DIR) {
							columns = cache.data;
							cache.dir = COLUMN_DIR;
						} else {
							columns = cache.data.reverse();
							cache.dir = COLUMN_DIR;
						}
					/** sort and cache */
					} else {
						/** return flat data, and then sort it. */
						var flatData = $.tableSorter.data.flatten(defaults,COLUMN_DATA,COLUMN_SORTER_CACHE,COLUMN_INDEX);
						/** do sorting, only onces per column. */
						flatData.sort(COLUMN_SORTER_CACHE[COLUMN_INDEX].sorter);
						/** if we have a sortDir, reverse the damn thing. */
						if(COLUMN_LAST_DIR != COLUMN_DIR) {
							flatData.reverse();
						}
						/** rebuild data from flat. */
						columns = $.tableSorter.data.rebuild(COLUMN_DATA,flatData,COLUMN_INDEX,COLUMN_LAST_INDEX);
						/** append to table cache. */
						$.tableSorter.cache.add(COLUMN_CACHE,COLUMN_INDEX,COLUMN_DIR,columns);
						/** good practise */
						flatData = null;
					}
					/** append to table > tbody */
					$.tableSorter.utils.appendToTable(defaults,oTable,columns,COLUMN_INDEX,COLUMN_LAST_INDEX);
					/** good practise i guess */
					columns = null;
					/** trigger stop event. */
					if(tableRowLength > defaults.minRowsForWaitingMsg) {
						$(oTable).trigger("sortStop",[COLUMN_INDEX]);
					}
					COLUMN_LAST_INDEX = COLUMN_INDEX;
				}
			}
		});
	};
	$.fn.sortStart = function(fn) {
		return this.bind("sortStart",fn);
	};
	$.fn.sortReload = function(fn) {
		return this.bind("sortStart",fn);
	};
	$.fn.sortStop = function(fn) {
		return this.bind("sortStop",fn);
	};
	$.tableSorter = {
		params: {},
		/** cache functions, okey for now. */
		cache: {
			add: function(cache,index,dir,data) {
				var oCache = {};
				oCache.dir = dir;
				oCache.data = data;
				cache[index] = oCache;
			},
			get: function (cache,index) {
				return cache[index];
			},
			exist: function(cache,index) {
				var oCache = cache[index];
				if(!oCache) {
					return false
				} else {
					return true
				}
			},
			clear: function(cache) {
				cache = [];
			}
		},
		data: {
			flatten: function(defaults,columnData,columnCache,columnIndex) {
				var flatData = [];
				var l = columnData.length;
				for (var i=0;i < l; i++) {
					flatData.push([i,columnCache[columnIndex].format($.tableSorter.utils.getElementText(defaults,columnData[i].cells[columnIndex],'columns',columnIndex),defaults)]);
				}
				return flatData;
			},
			rebuild: function(columnData,flatData,columnIndex,columnLastIndex) {
				var l = flatData.length;
				var sortedData = [];
				for (var i=0;i < l; i++) {
					sortedData.push(columnData[flatData[i][0]]);
				}
				return sortedData;
			}
		},
		sorters: {},
		parsers: {},
		analyzer: {
			analyzers: [],
			add: function(analyzer) {
				this.analyzers.push(analyzer);
			},
			add_to_front: function(analyzer) {
				this.analyzers.unshift(analyzer);
			},
			analyseString: function(defaults,s) {
				/** set defaults params. */
				var found = false;
				var analyzer = $.tableSorter.parsers.generic;
				var list = this.analyzers;
				$.each(list, function(i) {
					if(!found) {
						if(list[i].is(s)) {
							found = true;
							analyzer = list[i];
						}
					}
				});
				return analyzer;
				
			},
			getById: function(s) {
				var list = this.analyzers;
				var analyzer = $.tableSorter.parsers.generic;
				$.each(list, function(i) {
					if(list[i].id == s) {
						analyzer = list[i];
					}
				});
				return analyzer;
			}
		},
		utils: {
			getElementText: function(defaults,o,type,index) {
				if(!o) return "";
				var elementText = "";
				if(type == 'header') {
					elementText = $(o).text();
				} else if(type == 'columns') {
					if(defaults.textExtractionCustom && typeof(defaults.textExtractionCustom[index]) == "function") {
						elementText = defaults.textExtractionCustom[index](o);
					} else {
						if(defaults.textExtraction == 'simple') {
							if(typeof(defaults.textExtractionType) == "object") {
								var d = defaults.textExtractionType;
								$.each(d,function(i) {
									var val = o[d[i]];		
									if(val && val.length > 0) {
										elementText = val;
									}
								});
							} else {
								if(o.childNodes[0] && o.childNodes[0].hasChildNodes()) {
									elementText = o.childNodes[0].innerHTML;
								} else {
									elementText = o.innerHTML;
								}
							}
						} else if(defaults.textExtraction == 'complex') {
							// make a jquery object, this will take forever with large tables.
							elementText = $(o).text();
						}
					}
				}
				return elementText;
			},
			formatFloat: function(s) {
				var i = parseFloat(s);
				return (isNaN(i)) ? 0 : i;
			},
			appendToTable: function(defaults,o,c,index,lastIndex) {
				var l = c.length;
				$("> tbody:first",o).empty().append(c);
				/** jquery way, need to be benched mark! */
				if(defaults.stripingRowClass) {
					/** remove old! */
					$("> tbody:first/tr",o).removeClass(defaults.stripingRowClass[0]).removeClass(defaults.stripingRowClass[1]);
					/** add new! */
					$.tableSorter.utils.stripeRows(defaults,o);
				}
				if(defaults.highlightClass) {
					$.tableSorter.utils.highlightColumn(defaults,o,index,lastIndex);
				}
				
				/** empty object, good practice! */
				c=null;
			},
			highlightColumn : function(defaults,o,index, lastIndex) {
				$("> tbody:first/tr", o).find("td:eq(" + lastIndex+ ")").removeClass(defaults.highlightClass);
				$("> tbody:first/tr", o).find("td:eq(" + index + ")").addClass(defaults.highlightClass);
			},
			stripeRows: function(defaults,o) {
				$("> tbody:first/tr:visible:even",o).addClass(defaults.stripingRowClass[0]);
				$("> tbody:first/tr:visible:odd",o).addClass(defaults.stripingRowClass[1]);
			},
			isHeaderDisabled: function(defaults,o,arg,index) {
				if(typeof(arg) == "number") {
					return (arg == index)? true : false;
				} else if(typeof(arg) == "string") {
					return (arg.toLowerCase() == $.tableSorter.utils.getElementText(defaults,o,'header',index).toLowerCase()) ? true : false;
				} else if(arg.parentNode) {
	       			return (o == arg) ? true : false
				} else if(typeof(arg) == "object") {
					var l = arg.length;
					if(!this.lastFound) { this.lastFound = -1; }
					for(var i=0; i < l; i++) {
						var val = $.tableSorter.utils.isHeaderDisabled(defaults,o,arg[i],index);
						if(this.lastFound != i && val) {
							this.lastFound = i;
							return val;
						}
					}
				} else {
					return false
				}
			}
		},
		sorters: {
			generic: function(a,b) {
				return ((a[1] < b[1]) ? -1 : ((a[1] > b[1]) ? 1 : 0));
	 		},
	 		numeric: function(a,b) {
				return a[1]-b[1];
			}
		}
	};
	$.tableSorter.parsers.generic = {
		id: 'generic',
		is: function(s) {
			return true;
		},
		format: function(s) {
			return jQuery.trim(s.toLowerCase());
		},
		sorter: $.tableSorter.sorters.generic
	};
	$.tableSorter.parsers.currency = {
		id: 'currency',
		is: function(s) {
			return s.match(new RegExp(/^[£$?.]/g));
		},
		format: function(s) {
			return $.tableSorter.utils.formatFloat(s.replace(new RegExp(/[^0-9.]/g),''));
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.integer = {
		id: 'integer',
		is: function(s) {
			return s.match(new RegExp(/^\d+$/));
		},
		format: function(s) {
			return $.tableSorter.utils.formatFloat(s);
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.floating = {
		id: 'floating',
		is: function(s) {
			return s.match(new RegExp(/^(\+|-)?[0-9]+\.[0-9]+((E|e)(\+|-)?[0-9]+)?$/));
		},
		format: function(s) {
			return $.tableSorter.utils.formatFloat(s.replace(new RegExp(/,/),''));
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.ipAddress = {
		id: 'ipAddress',
		is: function(s) {
			return s.match(/^\d{2,3}[\.]\d{2,3}[\.]\d{2,3}[\.]\d{2,3}$/);
		},
		format: function(s) {
			var a = s.split('.');
			var r = '';
			for (var i = 0, item; item = a[i]; i++) {
			   if(item.length == 2) {
					r += '0' + item;
			   } else {
					r += item;
			   }
			}
			return $.tableSorter.utils.formatFloat(r);
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.url = {
		id: 'url',
		is: function(s) {
			return s.match(new RegExp(/(https?|ftp|file):\/\//));
		},
		format: function(s) {
			return jQuery.trim(s.replace(new RegExp(/(https?|ftp|file):\/\//),''));
		},
		sorter: $.tableSorter.sorters.generic
	};
	$.tableSorter.parsers.isoDate = {
		id: 'isoDate',
		is: function(s) {
			return s.match(new RegExp(/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/));
		},
		format: function(s) {
			return parseFloat((s != "") ? new Date(s.replace(new RegExp(/-/g),'/')).getTime() : "0");
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.usLongDate = {
		id: 'usLongDate',
		is: function(s) {
			return s.match(new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));
		},
		format: function(s) {
			return $.tableSorter.utils.formatFloat((new Date(s)).getTime());
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.shortDate = {
		id: 'shortDate',
		is: function(s) {
			return s.match(new RegExp(/\d{1,2}[\/-]\d{1,2}[\/-]\d{2,4}/));
		},
		format: function(s,defaults) {
			s = s.replace(new RegExp(/-/g),'/');
			if(defaults.dateFormat == "mm/dd/yyyy" || defaults.dateFormat == "mm-dd-yyyy") {
				/** reformat the string in ISO format */
				s = s.replace(new RegExp(/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/), '$3/$1/$2');
			} else if(defaults.dateFormat == "dd/mm/yyyy" || defaults.dateFormat == "dd-mm-yyyy") {
				/** reformat the string in ISO format */
				s = s.replace(new RegExp(/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/), '$3/$2/$1');
			} else if(defaults.dateFormat == "dd/mm/yy" || defaults.dateFormat == "dd-mm-yy") {
				s = s.replace(new RegExp(/(\d{1,2})[\/-](\d{1,2})[\/-](\d{2})/), '$1/$2/$3');	
			}
			return $.tableSorter.utils.formatFloat((new Date(s)).getTime());
		},
		sorter: $.tableSorter.sorters.numeric
	};
	$.tableSorter.parsers.time = {
	    id: 'time',
	    is: function(s) {
	        return s.toUpperCase().match(new RegExp(/^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));
	    },
	    format: function(s) {
	        return $.tableSorter.utils.formatFloat((new Date("2000/01/01 " + s)).getTime());
	    },
	    sorter: $.tableSorter.sorters.numeric
	};
	/** add parsers */
	$.tableSorter.analyzer.add($.tableSorter.parsers.currency);
	$.tableSorter.analyzer.add($.tableSorter.parsers.integer);
	$.tableSorter.analyzer.add($.tableSorter.parsers.isoDate);
	$.tableSorter.analyzer.add($.tableSorter.parsers.shortDate);
	$.tableSorter.analyzer.add($.tableSorter.parsers.usLongDate);
	$.tableSorter.analyzer.add($.tableSorter.parsers.ipAddress);
	$.tableSorter.analyzer.add($.tableSorter.parsers.url);
	$.tableSorter.analyzer.add($.tableSorter.parsers.time);
	$.tableSorter.analyzer.add($.tableSorter.parsers.floating);

})(jQuery);
