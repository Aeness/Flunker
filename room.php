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
if( !isset($_SESSION['list_guild']) ) {
	reinit_session();
	session_write_close();
	header('Location: index.php?');

}

if( isset($_GET['room']) == true ) {
	$_SESSION['room_type'] = $_GET['room'];
}

$room_type = $_SESSION['room_type'];

$possibility_of_order = unserialize($_SESSION[$room_type]['possibility_and_order_of_sorters']);
$nb_activated_sorters = (int)$_SESSION[$room_type]['nb_activated_sorters'];

$filter_form = $_SESSION[$room_type]['filter_form'];
$last_filter_line = $_SESSION[$room_type]['last_filter_line'];
$nb_items = $_SESSION[$room_type]['nb_items'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_SESSION['lang']; ?>" lang="<?php echo $_SESSION['lang']; ?>">
	<head>
		<title><?php echo __("Flunker")." - ".__($room_type); ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link type="text/css" href="inc/ryzom_api/ryzom_api/render/ryzom_ui.css" rel="stylesheet" media="all" />
		<link type="text/css" href="css/flunker.css" rel="stylesheet"  media="all"/>
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
		<script type="text/javascript">
		var Flunker={
			msg:{
				err_request:"<?php echo __('XMLHttpRequest not supported by your browser.'); ?>"
			}
		};
		</script>
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/criteria.js"></script>
		<?php  //echo ryzom_render_header_www(); ?>
		<style type="text/css">
			body{background-image:url(http://www.ryzom.com/data/bg.jpg);background-repeat:no-repeat;background-color:black}
			#main{width:96%;height:300px;margin-left:auto;margin-right:auto;text-align:left}
			a, a:visited{text-decoration:none;color:#ffff11}
			a:hover{color:white}
			.error{padding:.5em;background:#ff5555;color:white;font-weight:bold;}
		</style>
	</head>
	
	<body>
		<div id="main">
			<!--div style="text-align: right;">
				<a href="http://www.ryzom.com/"><img border="0" src="http://www.ryzom.com/data/logo.gif" alt=""/></a>
			</div-->
			<a href="?language=en"><img hspace="5" border="0" src="http://www.ryzom.com/data/en_v6.jpg" alt="English" /></a>
			<a href="?language=fr"><img hspace="5" border="0" src="http://www.ryzom.com/data/fr_v6.jpg" alt="Français" /></a>
			<a href="?language=de"><img hspace="5" border="0" src="http://www.ryzom.com/data/de_v6.jpg" alt="Deutsch" /></a>
			
			<div class="ryzom-ui ryzom-ui-header">
				<div class="ryzom-ui-tl"><div class="ryzom-ui-tr">
					<div class="ryzom-ui-t">
						<div style="position:absolute; ">
						<a href="entrance.php">Home</a>&nbsp;&gt;&nbsp;
					
						<?php 
						echo __($room_type)."&nbsp;&gt;&nbsp;<span id='nb_items'>0</span> / {$nb_items} ".__("items");
						?>
						</div>
						<div style="text-align: right;">
							<a href="./?"><?php echo __("Sign Out"); ?></a>
						</div>
					</div>
				</div></div>
			
				<div class="ryzom-ui-l"><div class="ryzom-ui-r"><div class="ryzom-ui-m">
					<form id="search_form" action=""  style="margin: 0px;">
						<input type="hidden" id="room" value="<?php echo $room_type; ?>"/>
						<?php
							if (!empty($filter_form)) {
								foreach( $filter_form as $str_filter ) {
									$filter = unserialize($str_filter);
									echo $filter->getHtmlTag("filter_quart");
								}
						?>
						<hr style="clear: left;"/>
						<?php
							}
						?>
					</form>
					
					<form action="" style="margin: 0px;">
						<div class="invisible">
						<div id="reduce_div">
							<img id="reduce_img" src="img/reduce_on.png" alt="<?php echo __('Expand/Collapse'); ?>" title="<?php echo __('Expand/Collapse'); ?>"/>
							<input style="display: none" type="checkbox" id="reduce_hidden" value="1"/>
						</div>
						<div id="last_filter_left">
							<?php
								if (!empty($last_filter_line) && !empty($last_filter_line["left"])) {
									$filter = unserialize($last_filter_line["left"]);
									echo $filter->getHtmlTag();
								}
							?>
						</div>	
						<div id="last_filter_right">
							<?php
								if (!empty($last_filter_line) && !empty($last_filter_line["right"])) {
									$filter = unserialize($last_filter_line["right"]);
									echo $filter->getHtmlTag();
								}
							?>
						</div>
						<p class="invisible_break">&nbsp;</p>
						</div>
					</form>
							
						<div style="float: left; width: 10.7em;">
							<?php if (!empty($possibility_of_order)) { ?>
							<form id="order_form" action="">
								<span><?php echo __("Sort by"); ?></span>
								<br />
								<ol id="order_boxes">
								<?php
									$i = 0;
									foreach( $possibility_of_order as $key => $formSorter ) {
										
										if ($i < $nb_activated_sorters) {
											echo $formSorter->getHtmlTag();
										}
										else if ($i < 5) {
											echo "
											<li class=\"add\"><span></span></li>";
										}
										else {
											break;
										}
										$i++;
									}
									for ($j = $i; $j < 5; $j++) {
										echo "
											<li class=\"add\"><span></span></li>";
									}
										echo "
											<li class=\"add\" style=\"display: none\"><span></span></li>";
								?>
								</ol>
								<span><?php echo __("Possible sorters"); ?></span>
								<br />
								<div style="margin: 0px; overflow: auto">
								<ol id="order_boxes_waited">
								<?php
									
									foreach( $possibility_of_order as $key => $formSorter ) {
										echo $formSorter->getWaitingHtmlTag();
									}
								?>
								</ol>
								<br style="clear: left;"/>
								</div>
							</form>
							<?php } ?>
						</div>
						<div class="ryzom-ui-body" style="position: static; margin-left:11em;">
						
							<div id="ajax_waiting"></div>
							<div id="ajax_waiting_img"><img src="img/ajax-loader.gif" alt="ajax-loader" /></div>

							<!-- begining of content -->
							<ul id="icon_boxes">
								<li></li>
							</ul>
							<p style="clear: left; border: 0px solid red; margin: 0; padding: 0; height: 2px">&nbsp;</p>
							<!-- end of content -->

						</div>
						<p style="clear: left; border: 0px solid red; margin: 0; padding: 0; height: 2px">&nbsp;</p>

				</div></div></div>

				<div class="ryzom-ui-bl"><div class="ryzom-ui-br"><div class="ryzom-ui-b"></div></div></div>
		<p class="ryzom-ui-notice"><?php echo __("powered by"); ?> <a class="ryzom-ui-notice" href="http://dev.ryzom.com/projects/ryzom-api/wiki">ryzom-api</a></p>
		</div>
	
		</div>
	</body>
</html>