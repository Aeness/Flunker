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
 * Delet all session information except language, error message, 'comeFrom' and skin.
 */
function reinit_session()
{
	$_lang = $_SESSION['lang'];
	$_error = $_SESSION['error'];
	$_come_from = $_SESSION['comeFrom'];
	$_skin = $_SESSION['skin'];
	session_destroy();
	session_start();
	$_SESSION['lang'] = $_lang;
	$_SESSION['error'] = $_error;
	$_SESSION['comeFrom'] = $_come_from;
	$_SESSION['skin'] = $_skin;
}

?>