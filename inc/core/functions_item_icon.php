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

/**
 * Display the html item icon with alt and title information (w3c).
 */
function flunker_item_icon_image($sheetid, $c=-1, $q=-1, $s=-1, $sap=-1, $title, $destroyed=false) {
	return '<img src="'.flunker_item_icon_url($sheetid, $c, $q, $s, $sap, $destroyed).'" title="'.$title.'" alt="'.__("Item").'"/>';
}

/**
 * Display the url item icon with a htmlentities treatement (w3c).
 */
function flunker_item_icon_url($sheetid, $c=-1, $q=-1, $s=-1, $sap=-1, $destroyed=false) {
	return ryzom_api_base_url()."item_icon.php?".htmlentities("sheetid=$sheetid&c=$c&q=$q&s=$s&sap=$sap".($destroyed?'&destroyed=1':''));
}


?>