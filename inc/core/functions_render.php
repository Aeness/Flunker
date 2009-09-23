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
	
	$skin = ($_SESSION['skin']!="ryzom")?"_".$_SESSION['skin']:"";
		
	if ($checked==true ) {
		$str_checked = "checked=\"checked\"";
		$str_on_off = "on";
	}

	return "
			<li class=\"li_checkbox {$class}\" style=\"background-image: url($img_path/{$value}.png);\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" {$str_checked} style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"$img_path/button_{$str_on_off}{$skin}.png\" title=\"$title\" style=\"margin: 0px;\"/>
			</li>";
}

/**
 * Display a radio.
 */
function radio($img_path,$name,$value,$alt,$title) {
	$skin = ($_SESSION['skin']!="ryzom")?"_".$_SESSION['skin']:"";

	return "
			<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" style=\"display: none;\"/>
			<div style=\"background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\"><img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" src=\"$img_path/button_off{$skin}.png\" alt=\"$alt\" title=\"$title\"/></div>";
}

/**
 * Display a checkbox for the guild hall choice.
 */
function ghButton($img_path,$name,$value,$url,$alt,$title,$class="") {

	$skin = ($_SESSION['skin']!="ryzom")?"_".$_SESSION['skin']:"";
	
	return "
			<li class=\"li_checkbox_gh_on{$skin} {$class}\" style=\" background-repeat: no-repeat; background-position: left top;\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" checked=\"checked\" style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"{$url}\" title=\"$title\" style=\"margin: 8px 0 0 8px;\"/>
			</li>";
}

/**
 * Display "flag" corresponding to the skins.
 */ 
function skin_flags_list($str_list_arg='') {
	return '
			<a href="'.htmlentities('?skin=ryzom'.$str_list_arg).'"><img style="margin-left: 30px;" hspace="5" border="0" src="img/ryzom.png" alt="'.__("Ryzom skin").'" /></a>
			<a href="'.htmlentities('?skin=tryker'.$str_list_arg).'"><img hspace="5" border="0" src="img/tryker.png" alt="'.__("Tryker skin").'" /></a>
			<a href="'.htmlentities('?skin=zorai'.$str_list_arg).'"><img hspace="5" border="0" src="img/zorai.png" alt="'.__("Zorai skin").'" /></a>
			<a href="'.htmlentities('?skin=matis'.$str_list_arg).'"><img hspace="5" border="0" src="img/matis.png" alt="'.__("Matis skin").'" /></a>
			<a href="'.htmlentities('?skin=fyros'.$str_list_arg).'"><img hspace="5" border="0" src="img/fyros.png" alt="'.__("Fyros skin").'" /></a>';

}

/**
 * Display "flag" corresponding to the languages.
 */ 
function language_flags_list($str_list_arg='') {
	return '
			<a href="'.htmlentities("?language=en".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/en_v6.jpg" alt="English" /></a>
			<a href="'.htmlentities("?language=fr".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/fr_v6.jpg" alt="Français" /></a>
			<a href="'.htmlentities("?language=de".$str_list_arg).'"><img hspace="5" border="0" src="http://www.ryzom.com/data/de_v6.jpg" alt="Deutsch" /></a>';
}
?>