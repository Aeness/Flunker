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

function init_css(&$get_style,&$session_style) {
	if ( !empty($get_style) ) {
		$session_style = $get_style;
	}
	if (empty($session_style)) {
		$session_style = 'ryzom';
	}
}

/**
 * Display a checkbox.
 */
function checkbox($img_path,$name,$checked,$value,$alt,$title,$class="") {
	$str_checked = "";
	$str_on_off = "off";
	
	$style = ($_SESSION['style']!="ryzom")?"_".$_SESSION['style']:"";
		
	if ($checked==true ) {
		$str_checked = "checked=\"checked\"";
		$str_on_off = "on";
	}

	return "
			<li class=\"li_checkbox {$class}\" style=\"background-image: url($img_path/{$value}.png);\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" {$str_checked} style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"$img_path/button_{$str_on_off}{$style}.png\" title=\"$title\" style=\"margin: 0px;\"/>
			</li>";
}

/**
 * Display a radio.
 */
function radio($img_path,$name,$value,$alt,$title) {
	$style = ($_SESSION['style']!="ryzom")?"_".$_SESSION['style']:"";

	return "
			<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" style=\"display: none;\"/>
			<div style=\"background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\"><img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" src=\"$img_path/button_off{$style}.png\" alt=\"$alt\" title=\"$title\"/></div>";
}

/**
 * Display a checkbox for the guild hall choice.
 */
function ghButton($img_path,$name,$value,$url,$alt,$title,$class="") {

	$style = ($_SESSION['style']!="ryzom")?"_".$_SESSION['style']:"";
	
	return "
			<li class=\"li_checkbox_gh_on{$style} {$class}\" style=\" background-repeat: no-repeat; background-position: left top;\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" checked=\"checked\" style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"{$url}\" title=\"$title\" style=\"margin: 8px 0 0 8px;\"/>
			</li>";
}

/**
 * Display "flag" corresponding to the skins.
 */ 
function style_list($str_list_arg='') {
	return '
			<a href="'.htmlentities('?style=ryzom'.$str_list_arg).'"><img style="margin-left: 30px;" hspace="5" border="0" src="img/ryzom.png" alt="'.__("Ryzom Style").'" /></a>
			<a href="'.htmlentities('?style=tryker'.$str_list_arg).'"><img hspace="5" border="0" src="img/tryker.png" alt="'.__("Tryker Style").'" /></a>
			<a href="'.htmlentities('?style=zorai'.$str_list_arg).'"><img hspace="5" border="0" src="img/zorai.png" alt="'.__("Zorai Style").'" /></a>
			<a href="'.htmlentities('?style=matis'.$str_list_arg).'"><img hspace="5" border="0" src="img/matis.png" alt="'.__("Matis Style").'" /></a>
			<a href="'.htmlentities('?style=fyros'.$str_list_arg).'"><img hspace="5" border="0" src="img/fyros.png" alt="'.__("Fyros Style").'" /></a>';

}
?>