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
/*! \mainpage
 *
 * Flunker : http://ryzom.aeness.net/Flunker .<br />
 * <br />
 * Flunker is developed for the Ryzom Summer Coding Contest ( http://dev.ryzom.com/projects/ryzom-api/wiki/Contest ).<br />
  * Flunker is a web application for accessing in-game content from a browser. It is a web site made with PHP, using the Ryzom API which sends in-game information in XML Format.
  * The website analyses information using regular expressions for building an object model. On the front-end, it offers a search engine using JQuery and AJAX.<br />
 * <br />
 * Flunker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.<br />
 * <br />
 * Flunker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.<br />
 * <br />
 * You should have received a copy of the GNU Affero General Public License
 * along with Flunker.  If not, see <http://www.gnu.org/licenses/>.<br /><br />
 */

session_start();
require_once(dirname(__FILE__).'/conf.php');
require_once(dirname(__FILE__).'/core/functions_language.php');
require_once(dirname(__FILE__).'/core/functions_render.php');
init_language($_GET['language'],$_SESSION['lang']);
init_css($_GET['skin'],$_SESSION['skin']);

if (file_exists(dirname(__FILE__).'/conf.php')) {
	require(dirname(__FILE__).'/conf.php');
}
else {
	$GLOBALS['__error'] = __('Unable to find conf.php. Ensure you have created it (use ').dirname(__FILE__).'/conf.php.in'.__(").");
}

require_once(dirname(__FILE__).'/ryzom_api/ryzom_api/ryzom_api.php');
require_once(dirname(__FILE__).'/core/constants.php');
require_once(dirname(__FILE__).'/core/class_chest.php');
require_once(dirname(__FILE__).'/core/class_item.php');
require_once(dirname(__FILE__).'/core/class_filter.php');
require_once(dirname(__FILE__).'/core/class_sorter.php');

require_once(dirname(__FILE__).'/core/functions_guild_icon.php');
require_once(dirname(__FILE__).'/core/functions_common.php');
require_once(dirname(__FILE__).'/core/functions_parse.php');
require_once(dirname(__FILE__).'/core/functions_sorter.php');
require_once(dirname(__FILE__).'/core/functions_session.php');
require_once(dirname(__FILE__).'/core/functions_item_icon.php');
?>