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
 @param get_language	$_GET['language']
 @param session_lang	$_SESSION['lang']
 */
function init_language(&$get_language,&$session_lang) {
	if ( !empty($get_language) ) {
		$GLOBALS['__lang']=null;
		$session_lang = $get_language;
	}
	if (empty($GLOBALS['__lang']) && !empty($session_lang)) {
		@include(dirname(__FILE__).'/../../local/'.$session_lang.'.lang.php');
		@include(dirname(__FILE__).'/../../local/'.$session_lang.'.flunker_code.php');
	}
	else if (empty($GLOBALS['__lang']) && empty($session_lang)) {
		@include(dirname(__FILE__).'/../../local/en.flunker_code.php');
		$session_lang = 'en';
	}
}

/**
 * Translate in the curent language.
  */
function __($str)
{
	return (!empty($GLOBALS['__lang'][$str])) ? $GLOBALS['__lang'][$str] : $str;
}
?>