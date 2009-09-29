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

function init_css(&$get_skin,&$session_skin) {
	if ( !empty($get_skin) ) {
		$session_skin = $get_skin;
	}
	if (empty($session_skin)) {
		$session_skin = 'ryzom';
	}
}

/**
 * Display a checkbox.
 */
function checkbox($img_path,$name,$checked,$value,$alt,$title,$class="") {
	$str_checked = "";
	$str_on_off = "off";
		
	if ($checked==true ) {
		$str_checked = "checked=\"checked\"";
		$str_on_off = "on";
	}
	
	return "
			<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" {$str_checked} style=\"display: none;\" />
			<div style=\"width: 47px; height: 47px; background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\">
				<div title=\"$title\" name=\"hidden_{$name}\" id=\"hidden_{$name}_{$value}\" class=\"span_checkbox_{$str_on_off}\"></div>
			</div>";
}

/**
 * Display a radio.
 */
function radio($img_path,$name,$value,$alt,$title) {
	return "
			<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" style=\"display: none;\"/>
			<div style=\"width: 47px; height: 47px; background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\">
				<div title=\"$title\" id=\"hidden_{$name}_{$value}\" class=\"span_radio_off\"></div>
			</div>";
}

/**
 * Display a checkbox for the guild hall choice.
 */
function ghButton($img_path,$name,$value,$url,$alt,$title,$class="") {
	
	return "
			<li class=\"li_checkbox_gh_on {$class}\" style=\" background-repeat: no-repeat; background-position: left top;\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" checked=\"checked\" style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"{$url}\" title=\"$title\" style=\"margin: 8px 0 0 8px;\"/>
			</li>";
}

/**
 * Display "flag" corresponding to the skins.
 */ 
function skin_flags_list($str_list_arg='') {
	return '
			<a href="'.htmlentities('?skin=ryzom'.$str_list_arg).'"><img style="margin-left: 30px;" hspace="5" border="0" src="img/ryzom.png" alt="'.__("Ryzom skin").'" title="'.__("Ryzom skin").'" /></a>
			<a href="'.htmlentities('?skin=tryker'.$str_list_arg).'"><img hspace="5" border="0" src="img/tryker.png" alt="'.__("Tryker skin").'" title="'.__("Tryker skin").'" /></a>
			<a href="'.htmlentities('?skin=zorai'.$str_list_arg).'"><img hspace="5" border="0" src="img/zorai.png" alt="'.__("Zorai skin").'" title="'.__("Zorai skin").'" /></a>
			<a href="'.htmlentities('?skin=matis'.$str_list_arg).'"><img hspace="5" border="0" src="img/matis.png" alt="'.__("Matis skin").'" title="'.__("Matis skin").'" /></a>
			<a href="'.htmlentities('?skin=fyros'.$str_list_arg).'"><img hspace="5" border="0" src="img/fyros.png" alt="'.__("Fyros skin").'" title="'.__("Fyros skin").'" /></a>';

}

/**
 * Display "flag" corresponding to the languages.
 */ 
function language_flags_list($str_list_arg='') {
	return '
			<a href="'.htmlentities("?language=en".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/en_v6.jpg" alt="English"  title="English" /></a>
			<a href="'.htmlentities("?language=fr".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/fr_v6.jpg" alt="Français" title="Français" /></a>
			<a href="'.htmlentities("?language=de".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/de_v6.jpg" alt="Deutsch" title="Deutsch" /></a>';
}


function flunker_render_header() {
	return '
		<link type="text/css" href="inc/ryzom_api/ryzom_api/render/ryzom_ui.css" rel="stylesheet" media="all" />
		'.ryzom_render_header_www().'
		<link type="text/css" href="css/flunker.css" rel="stylesheet"  media="all"/>
		<link type="text/css" href="css/'.$_SESSION['skin'].'.css" rel="stylesheet"  media="all"/>
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
		<script type="text/javascript">
		var Flunker={
			msg:{
				err_request:"'.__('XMLHttpRequest not supported by your browser.').'"
			},
			nationality: "'.$_SESSION['skin'].'"
		};
		</script>
		<script type="text/javascript" src="js/background.js"></script>';
}
?>