<?php
/* Copyright (C) 2009 Anne-Cécile Calvot
 *
 * This file is part of Flunker.
 * Flunker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flunker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Flunker.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once('./inc/flunker_api.php');
//ryzom_log_start('Flunker');
reinit_session();
$_SESSION['comeFrom'] = null;
$_SESSION['style'] = null;
$content = null;

#POST_CASE
if(isset($_POST['key']) && $_POST['key'] != '') {
	$_SESSION['comeFrom'] = 'index';
	if(is_array($_POST['key'])) {
		$location = "Location: links.php?";
		foreach( $_POST['key'] as $key ) {
			if( $key != '') {
				$location .= 'ckey[]='.ryzom_encrypt($key,FLUNKER_CRYPT_KEY).'&';
			}
		}
		session_write_close();
		header($location);
	}
	else {
		session_write_close();
		header('Location: links.php?ckey='.ryzom_encrypt($_POST['key'],FLUNKER_CRYPT_KEY));
	}
}
if ($content == null) {
	# First time case
	// Display the form to enter the API Key
	$content = '<form action="" method="post"><p>';
	$content .= __('Please enter the API Key(s) (guild or character) that you can find on');
	$content .= ' <a href="https://secure.ryzom.com/payment_profile">';
	$content .= __('your profile page');
	$content .= '</a>:</p>';
	$content .= '<input type="text" name="key[]"/><br/>';
	$content .= '<input type="text" name="key[]"/><br/>';
	$content .= '<input type="text" name="key[]"/><br/>';
	$content .= '<input type="text" name="key[]"/><br/>';
	$content .= '<input type="text" name="key[]"/><br/>';
	$content .= '<input type="submit" value="Submit" />';
	$content .= '</form>';
}
header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_SESSION['lang']; ?>" lang="<?php echo $_SESSION['lang']; ?>">
	<head>
	<title><?php echo __("Flunker"); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link type="text/css" href="inc/ryzom_api/ryzom_api/render/ryzom_ui.css" rel="stylesheet" media="all" />
	<?php  echo ryzom_render_header_www(); ?>
	</head>
	
	<body>
		<div id="main">
			<div style="text-align: right;">
				<a href="http://www.ryzom.com/"><img border="0" src="http://www.ryzom.com/data/logo.gif" alt=""/></a>
			</div>
			<a href="?language=en"><img hspace="5" border="0" src="http://www.ryzom.com/data/en_v6.jpg" alt="English" /></a>
			<a href="?language=fr"><img hspace="5" border="0" src="http://www.ryzom.com/data/fr_v6.jpg" alt="Français" /></a>
			<a href="?language=de"><img hspace="5" border="0" src="http://www.ryzom.com/data/de_v6.jpg" alt="Deutsch" /></a>
			
			<div class="ryzom-ui ryzom-ui-header">
				<div class="ryzom-ui-tl"><div class="ryzom-ui-tr">
					<div class="ryzom-ui-t">
						<?php 
						echo __("Flunker");
						?>
					</div>
				</div></div>
			
				<div class="ryzom-ui-l"><div class="ryzom-ui-r"><div class="ryzom-ui-m">
					<div class="ryzom-ui-body">
						<?php 
						if( $GLOBALS['__error'] != "" ) {
							echo '<div class="error">'.$GLOBALS['__error'].'</div>';
							$GLOBALS['__error'] = "";
						}
						if( $_SESSION['error'] != "" ) {
							echo '<div class="error">'.$_SESSION['error'].'</div>';
							$_SESSION['error'] = "";
						}
						?>
					
						<!-- begining of content -->
						<?php echo $content; ?>
						<!-- end of content -->

					</div>
				</div></div></div>

				<div class="ryzom-ui-bl"><div class="ryzom-ui-br"><div class="ryzom-ui-b"></div></div></div>
				<p class="ryzom-ui-notice"><?php echo __('powered by') ;?> <a class="ryzom-ui-notice" href="http://dev.ryzom.com/projects/ryzom-api/wiki">ryzom-api</a></p>
			</div>
	
		</div>
	</body></html>