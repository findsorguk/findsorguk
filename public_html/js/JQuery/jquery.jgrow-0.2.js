	/*
	* jGrow 0.2
	* 08.02.2008
	* 0.2 release: 04.03.2008
	*/
	
	(function($) {

		$.fn.jGrow = function(options) {

			var opts = $.extend({}, $.fn.jGrow.defaults, options);

			return this.each(function() {

				$(this).css({ overflow: "hidden" }).bind("keypress", function() {

					$this = $(this);

					var o = $.meta ? $.extend({}, opts, $this.data()) : opts;

					if(o.rows == 0 && (this.scrollHeight > this.clientHeight)) {
						
						this.rows += 1;
						
					} else if((this.rows <= o.rows) && (this.scrollHeight > this.clientHeight)) {

						this.rows += 1;

					} else if(o.rows != 0 && this.rows > o.rows) {

						$this.css({ overflow: "auto" });

					}

					$this.html();

				});

			});

		}

		$.fn.jGrow.defaults = { rows: 0 };

	})(jQuery);