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
function flunker_base_url() {
	return FLUNKER_BASE_URL;
}

function load_test_xml($filename) {
	$output = "";
	if (file_exists($filename)) {
		$fp = fopen($filename,"r");
		if(isset($fp)) {
			while(!feof($fp)) {
				# On récupère une ligne
				$ligne = fgets($fp,255000);
				if (trim($ligne,"\r\n")!="") {
					$output .= trim($ligne,"\r\n");
				}
			}
		}
		fclose($fp); # On ferme le fichier
	}
	return simplexml_load_string($output);
}

function withoutAccent($str) {

		$search  = utf8_decode('çñÄÂÀÁäâàáËÊÈÉéèëêÏÎÌÍïîìíÖÔÒÓöôòóÜÛÙÚüûùúµ');
		$replace = utf8_decode('cnaaaaaaaeeeeeeeeeiiiiiiiioooooooouuuuuuuuu');
		
		return utf8_encode(strtr(utf8_decode($str), $search, $replace));
}
?>