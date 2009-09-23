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
$_SESSION['comeFrom'] = null;
//ryzom_log_start('Flunker');
if( !isset($_SESSION['list_guild']) ) {
	$_SESSION['error'] =  html_err_lost_session();
	session_write_close();
	header('Location: index.php?');
}
$skin = ($_SESSION['skin']!="ryzom")?"_".$_SESSION['skin']:"";
header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_SESSION['lang']; ?>" lang="<?php echo $_SESSION['lang']; ?>">
	<head>
		<title><?php echo __("Flunker"); ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php echo flunker_render_header(); ?>
		<script type="text/javascript">
		$(function(){
		
			$('#enter_armory').click(
				function(){
					window.location.href = "room.php?room=armory";
				}
			);
		
			$('#enter_ampli').click(
				function(){
					window.location.href = "room.php?room=ampli";
				}
			);
		
			$('#enter_range').click(
				function(){
					window.location.href = "room.php?room=range";
				}
			);
		
			$('#enter_dressing').click(
				function(){
					window.location.href = "room.php?room=dressing";
				}
			);
		
			$('#enter_jewel').click(
				function(){
					window.location.href = "room.php?room=jewel";
				}
			);
		
			$('#enter_material').click(
				function(){
					window.location.href = "room.php?room=material";
				}
			);
		
			$('#enter_other').click(
				function(){
					window.location.href = "room.php?room=other";
				}
			);
		});
		</script>

		<style type="text/css">
		.enter_div {
			float: left;
			cursor: pointer;
			width :214px ;
			height: 128px;
			margin: 1px;
			border: 1px solid black;
		}
		.sub_enter_div {
			width :214px ;
			height: 128px;
			margin: 0px;
		}
		</style>
	</head>
	
	<body>
		<div id="main">
				
			<div style="text-align: right;">
				<a href="http://www.ryzom.com/"><img border="0" src="http://www.ryzom.com/data/logo.gif" alt=""/></a>
			</div>
			<?php echo language_flags_list(); ?>
			<?php echo skin_flags_list(); ?>
			
			<div class="ryzom-ui ryzom-ui-header">
				<div class="ryzom-ui-tl"><div class="ryzom-ui-tr">
					<div class="ryzom-ui-t">
						<div style="position:absolute;">
						<?php  echo __("Flunker"); ?>
						</div>
						<div style="text-align: right;">
							<a href="./?"><?php echo __("Sign Out"); ?></a>
						</div>
					</div>
				</div></div>
			
				<div class="ryzom-ui-l"><div class="ryzom-ui-r"><div id="search_content" class="ryzom-ui-m">
					<div id="result_content" class="ryzom-ui-body">
						<?php 
						if( $GLOBALS['__error'] != "" ) {
							echo '<div class="error">'.$GLOBALS['__error'].'</div>';
							$GLOBALS['__error'] = "";
						}
						?>
					
						<!-- begining of content -->
						<?php 
						$list_guild = unserialize($_SESSION['list_guild']);
						echo __('Welcome to Flunker, your guild halls are:')."<br />";
						$list = "";
						foreach($list_guild as $guild) {
							$list .= $guild->getHtml();
						}
						echo $list;
						?>
						<p class="invisible_break">&nbsp;</p><br />

						<div id='enter_armory' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');">
							<div class='sub_enter_div' style="background: url('img/Enseigne_melee.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Armory"); ?>
							</div>
						</div>
						<div id='enter_ampli' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');">
							<div class='sub_enter_div' style="background: url('img/Enseigne_melee.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Magic Amplifier Armory"); ?>
							</div>
						</div>
						<div id='enter_range' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');">
							<div class='sub_enter_div' style='background: url("img/Enseigne_range.png") repeat-y top center;text-align: center;'>
								<?php echo __("Enter in the Range Amory"); ?>
							</div>
						</div>
						<div id='enter_dressing' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');margin-left: 107px;">
							<div class='sub_enter_div' style="background: url('img/Enseigne_jewel.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Jeweller's"); ?>
							</div>
						</div>
						<div id='enter_jewel' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');">
							<div class='sub_enter_div' style="background: url('img/Enseigne_dressing.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Wardrobe"); ?>
							</div>
						</div>
						<div id='enter_material' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png'); margin-left: 107px;">
							<div class='sub_enter_div' style="background: url('img/Enseigne_material.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Material Bazaar"); ?>
							</div>
						</div>
						<div id='enter_other' class='enter_div' style="background: url('img/Enseigne_back<?php echo $skin; ?>.png');">
							<div class='sub_enter_div' style="background: url('img/Enseigne_other.png') repeat-y top center;text-align: center;">
								<?php echo __("Enter in the Bazaar"); ?>
							</div>
						</div>
						<p style="clear: left">&nbsp;</p>
						<!-- end of content -->
					</div>
				</div></div></div>

				<div class="ryzom-ui-bl"><div class="ryzom-ui-br"><div class="ryzom-ui-b"></div></div></div>
				<p class="ryzom-ui-notice"><?php echo __('powered by') ;?> <a class="ryzom-ui-notice" href="http://dev.ryzom.com/projects/ryzom-api/wiki">ryzom-api</a></p>
			</div>
		<p class="invisible_break">&nbsp;</p>
		</div>
	</body></html>