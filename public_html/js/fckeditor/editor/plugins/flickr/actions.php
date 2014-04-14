<?php
/************************************************************************************************/

/**
 * Setup your Flickr Account
 */

$api_key = 'dbb87ca6390925131a4fedb34d9d8d80';
$username = 'portableantiquities';

/************************************************************************************************/

/**
 * What to do?
 */
$todo = $_REQUEST['todo'];

/************************************************************************************************/

/**
 * Show page with photos
 */
if($todo == "show") :
	$page = ($_REQUEST['page'] == "") ? 1 : $_REQUEST['page'];
	$viewsize = ($_REQUEST['viewsize'] == "") ? "thumbnail" : $_REQUEST['viewsize'];
	
	require_once("phpFlickr.php");
	$f = new phpFlickr($api_key);
	$person = $f->people_findByUsername($username);
	
	// Get the friendly URL of the user's photos
	$photos_url = $f->urls_getUserPhotos($person['id']);
	
	// Get person's public photos
	$photos = $f->people_getPublicPhotos($person['id'], NULL, NULL, 18, $page);
	// Loop through the photos and output the html
	//var_dump($f);
	echo '<center id="paging">';
		$paging = 1;
		while($paging < ($photos['photos']['pages']+1)) :
			if($paging == $page) :
				echo '<a class="active" href="javascript:getPage('.$paging.');">'.$paging.'</a>';
			else :
				echo '<a class="link" href="javascript:getPage('.$paging.');">'.$paging.'</a>';
			endif;
			$paging++;
		endwhile;
	echo '</center>';
	
	echo '<table width="100%" cellspacing="0" cellpadding="5" border="0">';
		echo '<tr>';
			$count = 0;
			foreach ($photos['photos']['photo'] as $photo) :
				$count++;
				echo '<td align="center">';
					echo '<a class="img" href="javascript:insertImage('.$photo['id'].');">';
						echo '<img src="'.$f->buildPhotoURL($photo, $viewsize).'" title="'.$photo['title'].'" />';
					echo '</a>';
				echo '</td>';
				$row = 6;
				if($viewsize == "square" || $viewsize == "thumbnail") :
				 	$row = 6;
				elseif($viewsize == "small") :
					$row = 3;
				else :
					$row = 1;
				endif;
				
				if($count % $row == 0) :
					echo '</tr><tr>';
				endif;
			endforeach;
		echo '</tr>';
	echo '</table>';

/************************************************************************************************/
/**
 * Show page with photos
 */
elseif($todo == "getphoto") :
	$photo_id = $_REQUEST['photo_id'];
	$insertsize = ($_REQUEST['insertsize'] == "") ? "small" : $_REQUEST['insertsize'];
	$linksize = ($_REQUEST['linksize'] == "") ? "medium" : $_REQUEST['linksize'];
	
	require_once("phpFlickr.php");
	$f = new phpFlickr($api_key);
	$photo = $f->photos_getInfo($photo_id);
	
	echo '<a href="'.$f->buildPhotoURL($photo, $linksize).'" title="'.$photo['title'].'"><img src="'.$f->buildPhotoURL($photo, $insertsize).'" alt="'.$photo['title'].'" title="'.$photo['title'].'" /></a>';

/************************************************************************************************/

endif;
?>