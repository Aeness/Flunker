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

function checkbox($img_path,$name,$checked,$value,$alt,$title,$class="") {
	$str_checked = "";
	$str_on_off = "off";
		
	if ($checked==true ) {
		$str_checked = "checked=\"checked\"";
		$str_on_off = "on";
	}

	return "
			<li class=\"li_checkbox {$class}\" style=\"background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" {$str_checked} style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"$img_path/botton_{$str_on_off}.png\" title=\"$title\" style=\"margin: 0px;\"/>
			</li>";
}

function radio($img_path,$name,$value,$alt,$title) {

	return "
			<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" style=\"display: none;\"/>
			<div style=\"background-image: url($img_path/{$value}.png); background-repeat: no-repeat; background-position: left top;\"><img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" src=\"$img_path/botton_off.png\" alt=\"$alt\" title=\"$title\"/></div>";
}

function ghButton($img_path,$name,$value,$url,$alt,$title,$class="") {

	return "
			<li class=\"{$class}\" style=\"background-image: url($img_path/back_button_on.png); background-repeat: no-repeat; background-position: left top;\">
				<input type=\"checkbox\" name=\"$name\" id=\"{$name}_{$value}\" value=\"$value\" checked=\"checked\" style=\"display: none;\" />
				<img name=\"hidden_$name\" id=\"hidden_{$name}_{$value}\" alt=\"$alt\" src=\"{$url}\" title=\"$title\" style=\"margin: 8px 0 0 8px;\"/>
			</li>";
}
?>