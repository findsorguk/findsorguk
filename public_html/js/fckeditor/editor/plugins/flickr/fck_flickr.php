<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>Flickr</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta content="noindex, nofollow" name="robots">
		<script type="text/javascript" src="./jquery-1.2.6.js"></script>
		<script type="text/javascript" src="./jquery.form.js"></script>
		<script type="text/javascript" src="../../../../fckeditor/fckeditor.js"></script>
		<script type="text/javascript">
			// Fetch parent editor
			var oParentEditor = window.opener.FCK ;
			
			// Starts when editor is loaded
			function FCKeditor_OnComplete( editorInstance )
			{
				return false;
			}
			
			// Insert an image into the (parent) FCKeditor
			function insertImage(photo_id) {
				var insertsize = "";
				$("#insertsize option:selected").each(function () {
					insertsize = $(this).val();
				});
				var linksize = "";
				$("#linksize option:selected").each(function () {
					linksize = $(this).val();
				});
				
				$.get('./actions.php?todo=getphoto&photo_id='+photo_id+'&insertsize='+insertsize+'&linksize='+linksize+'&cacheFix='+Math.random(), function(data) {
					oParentEditor.InsertHtml(data);
				});
			}
			
			// Paging
			function getPage(page) {
				$("#page").val(page);
				
				var viewsize = "";
				$("#viewsize option:selected").each(function () {
					viewsize = $(this).val();
				});
				var insertsize = "";
				$("#insertsize option:selected").each(function () {
					insertsize = $(this).val();
				});
				var linksize = "";
				$("#linksize option:selected").each(function () {
					linksize = $(this).val();
				});
				
				$("#load_anim").show();
				$("#placeholder").fadeOut(function() {
					$.get('./actions.php?todo=show&viewsize='+viewsize+'&insertsize='+insertsize+'&linksize='+linksize+'&page='+page+'&cacheFix='+Math.random(), function(data) {
						$("#placeholder").html(data).fadeIn(function() {
							$("#load_anim").hide();
						})
					});
				});
			}
			
			$(document).ready(function() {
				// HIJAX the form
				var form_options = {
					target: '#placeholder',
					type: 'get',
					resetForm: false,
					beforeSubmit: function() {
						$("#submitter").attr("disabled", "true"); 
						$("#load_anim").show();
						$("#placeholder").fadeOut()
						return true;
					},
					success: function(data) {
						$("#submitter").removeAttr("disabled");
						$("#load_anim").hide();
						$("#placeholder").fadeIn(function() {
							$(this).html(data)
						});
					}
				};
				$('#flickr_form').ajaxForm(form_options);
				
				// Submit the form once
				$('#flickr_form').submit();
			});
		</script>
		
		<style type="text/css">
			body { margin:0px; font-family:"Trebuchet MS"; font-size:0.8em; color:#333; }
			a { text-decoration: none; }
			a.link:link, a.link:visited { color: #0063DC; padding: 0px 4px; margin: 0px 1px; }
			a.link:hover, a.link:active { color: #fff; background: #0063DC; }
			a.active { color: #fff; background: #0063DC; padding: 0px 3px;}
			a.img:link img, a.img:visited img { border: 3px solid #0063DC; }
			a.img:hover img, a.img:active img { border: 3px solid #FF0084; }
			#load_anim {
				position: absolute;
				top: 200px;
				left: 390px;
				margin: 0px auto;
			}
			#placeholder {
				height: 375px;
				overflow: scroll;
				padding: 0px 10px;
				margin-top: 25px;
			}
			
			#placeholder #paging {
				width: 95%;
				position: absolute;
				top: 35px;
				left: 20px;
				border-top: 1px solid #E6E6E6;
				border-bottom: 1px solid #E6E6E6;
			}
		</style>
		
	</head>
	<body>
		
		<?php
		/************************************************************************************************/
		$sizes = array("Square", "Thumbnail", "Small", "Medium", "Large", "Original");
		/************************************************************************************************/
		?>
		
		<form id="flickr_form" method="get" action="./actions.php" style="padding:10px 10px 0px 10px;">
			<center>
				View size: 
				<select name="viewsize" id="viewsize">
					<?php
					foreach($sizes as $size) :
						if(strtolower($size) == "thumbnail") :
							echo '<option value="'.strtolower($size).'" selected="selected">'.$size.'</option>';
						else :
							echo '<option value="'.strtolower($size).'">'.$size.'</option>';
						endif;
					endforeach;
					?>
				</select>
				
				 &bull; 
				
				Insert size: 
				<select name="insertsize" id="insertsize">
					<?php
					foreach($sizes as $size) :
						if(strtolower($size) == "small") :
							echo '<option value="'.strtolower($size).'" selected="selected">'.$size.'</option>';
						else :
							echo '<option value="'.strtolower($size).'">'.$size.'</option>';
						endif;
					endforeach;
					?>
				</select>
				
				 &bull; 
				
				Link size: 
				<select name="linksize" id="linksize">
					<?php
					foreach($sizes as $size) :
						if(strtolower($size) == "medium") :
							echo '<option value="'.strtolower($size).'" selected="selected">'.$size.'</option>';
						else :
							echo '<option value="'.strtolower($size).'">'.$size.'</option>';
						endif;
					endforeach;
					?>
				</select>
				
				 &bull; 
				
				<input type="hidden" name="page" id="page" value="1" />
				<input type="hidden" name="todo" value="show" />
				<input type="submit" id="submitter" value="Show" />
			</center>
		</form>
		
		<?php /************************************************************************************************/ ?>
		
		<img id="load_anim" src="./loading.gif" alt="Loading..." style="display:none;" />
		<div id="placeholder"></div>
		
		<?php /************************************************************************************************/ ?>
		
	</body>
</html>