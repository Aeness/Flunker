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
define("PATTERN_REFUGEE",		1);
define("PATTERN_AMPLI",			2);
define("PATTERN_WEAPON",			3);
define("PATTERN_RANGE",			4);
define("PATTERN_ARMOR",			5);
define("PATTERN_ARMOR_CP",		6);
define("PATTERN_SHIELD",			7);
define("PATTERN_JEWEL",			8);
define("PATTERN_MATERIAL_LOOT",	9);
define("PATTERN_MATERIAL_HARVEST",	10);
define("PATTERN_ELSE",			11);

# we use variables for re-using them in tool as 'generate_local_files.php'
$pattern_list = array (
	PATTERN_REFUGEE		=>	'/^icr(.*)?\.sitem/',
	PATTERN_AMPLI			=>	'/^ic(.|(oka[rm]))m2ms([lbwe]|_[123])?\.sitem/',
	PATTERN_WEAPON			=>	'/^ic(.|(oka[rm]))m(1|2)(.{2})([lbwe]|_[123])?\.sitem/',
	PATTERN_RANGE			=>	'/^ic(.|(oka[rm]))r(1|2)(.)([lbwe]|_[123])?\.sitem/',
	PATTERN_ARMOR			=>	'/^ic(.)a([h|l|m])([bghpsv])(_[23b])?\.sitem/',
	PATTERN_ARMOR_CP		=>	'/^ic(.)a(cp)(_[23])?\.sitem/',
	PATTERN_SHIELD			=>	'/^ic(.)s[sb]([lbwe]|_[23])?\.sitem/',
	PATTERN_JEWEL			=>	'/^ic(.)j([abdepr])(_[23b])?\.sitem/',
	PATTERN_MATERIAL_LOOT	=>	'/^m([0-9]{4})c(.)(.)(.)(.)01\.sitem/',
	PATTERN_MATERIAL_HARVEST	=>	'/^m([0-9]{4})dxa(.)(.)01\.sitem/'
);

$room_list = array (
	PATTERN_REFUGEE		=>	ROOM_OTHER,
	PATTERN_AMPLI			=>	ROOM_AMPLI,
	PATTERN_WEAPON			=>	ROOM_ARMORY,
	PATTERN_RANGE			=>	ROOM_RANGE,
	PATTERN_ARMOR			=>	ROOM_DRESSING,
	PATTERN_ARMOR_CP		=>	ROOM_DRESSING,
	PATTERN_SHIELD			=>	ROOM_DRESSING,
	PATTERN_JEWEL			=>	ROOM_JEWEL,
	PATTERN_MATERIAL_LOOT	=>	ROOM_MATERIAL,
	PATTERN_MATERIAL_HARVEST	=>	ROOM_MATERIAL,
	PATTERN_ELSE			=>	ROOM_OTHER
);

$pfonc_list= array (
	PATTERN_REFUGEE		=>	"creat1",
	PATTERN_AMPLI			=>	"creat2",
	PATTERN_WEAPON			=>	"creat3",
	PATTERN_RANGE			=>	"creat4",
	PATTERN_ARMOR			=>	"creat5",
	PATTERN_ARMOR_CP		=>	"creat6",
	PATTERN_SHIELD			=>	"creat7",
	PATTERN_JEWEL			=>	"creat8",
	PATTERN_MATERIAL_LOOT	=>	"creat9",
	PATTERN_MATERIAL_HARVEST	=>	"creat10",
	PATTERN_ELSE			=>	"creat11"
);
/**
 * For one guild, parses its xml file and builds its object which are in its hall.
 * @param xml 	&lt;<b>simplexml</b>&gt;	the xml file
 */
function parse_guild(&$xml) {

	$guild = new guild((string)$xml->gid,(string)$xml->name,(string)$xml->icon);
	$guild->money=(string)$xml->money;
	return $guild;
}

/**
 * For one character, parses its xml file and builds its object which are in his apartment.
 * @param xml 	&lt;<b>simplexml</b>&gt;	the xml file
 */
function parse_character(&$xml) {

	$apartment = new apartment((string)$xml->cid,(string)$xml->name,(string)$xml->guild->icon);
	$apartment->money=(string)$xml->money;
	return $apartment;
}

/**
 * For one chest, parses its xml file and builds tab filter used by Filter and Item.
 * And give to the chest the nb items found.
 * @param xml 		&lt;<b>simplexml</b>&gt;		the xml file
 * @param xml_path 	&lt;<b>string</b>&gt;		the xpath for finding item tag
 * @param chest 	&lt;<b>apartment</b>&gt;	the apartment or guild object where item are
 */
function parse_item(&$xml,$xml_path,$chest) {
	
	$result = $xml->xpath($xml_path);
	
	if (count($result)==0) {
		throw new Exception($chest->name." ".__("does not have any item."));
	}
	
	while(list(,$item)=each($result)) {
		parse_an_item($item,$chest);
	}
	
	$chest->nbItems=count($result);
}

/**
 * Use global variables : items, sorter_items.
 */
function parse_an_item(&$item,$chest) {
	global $items;				///< &lt;<b>array{item]</b>&gt;  Where items are stoked
	global $sorter_items;		///< &lt;<b>array{item]</b>&gt;  Will use for sorting the array items
	global $pattern_list;
	global $pfonc_list;
	global $room_list;
	
	$room = null;
	$item_obj = null;
	$matches = array();
	$pfonc_else = $pfonc_list[PATTERN_ELSE];

	$find = false;
	foreach($pattern_list as $key => $pattern) {

		if( preg_match($pattern, $item, $matches) == 1 ) {
			$pfonc = $pfonc_list[$key];
			$pfonc($item,$chest,$matches,$item_obj);
			$room = $room_list[$key];
			$find = true;
			break;
		}
	}
	if ($find == false) {
		$pfonc_else($item,$chest,$matches,$item_obj);
		$room = $room_list[PATTERN_ELSE];
	}
	# creat sorters
	build_simple_sorter($room,$item_obj);
	$room = null;
}
	
function creat1(&$item,$chest,$matches,&$item_obj)  {
	$item_obj = new item($chest,(string)$item['slot'],(string)$item,(string)$item['q'],(string)$item['s']);
}
function creat2(&$item,$chest,$matches,&$item_obj) {
	$nation = $matches[1];
	$skin = $matches[3];
	$sap = (empty($item['sap']))?-1:1;

	$item_obj = new amplifier(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			'ms',"2",
			$sap,(string)$item['sl'],(string)$item['csl'],
			(string)$item['w'],(string)$item['hr']
	);
}
function creat3(&$item,$chest,$matches,&$item_obj)  {
	$sap = (empty($item['sap']))?-1:1;
	$item_id = $matches[4];
	$nb_hand = $matches[3];
	$nation = $matches[1];
	$skin = $matches[5];

	$item_obj = new meleeWeapon(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$item_id,$nb_hand,
			$sap,(string)$item['sl'],(string)$item['csl'],
			(string)$item['w'],(string)$item['hr'],
			(string)$item['dm'],(string)$item['pm'],(string)$item['adm'],(string)$item['apm']
	);
}
function creat4(&$item,$chest,$matches,&$item_obj)  {
	$sap = (empty($item['sap']))?-1:1;
	$item_id = $matches[4];
	$nb_hand = $matches[3];
	$nation = $matches[1];
	$skin = $matches[5];

	$item_obj = new rangeWeapon(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$item_id,$nb_hand,
			$sap,(string)$item['sl'],(string)$item['csl'],
			(string)$item['w'],(string)$item['hr'],
			(string)$item['r']
	);
}
function creat5(&$item,$chest,$matches,&$item_obj)  {
	$nation = $matches[1];
	$skin = $matches[4];
	$id_type = $matches[2];
	$id_piece = $matches[3];
	
	$item_obj = new cloth(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$id_type,$id_piece,
			(string)$item['c'],
			(string)$item['pf'],(string)$item['msp'],(string)$item['mbp'],(string)$item['mpp'],
			(string)$item['dm'],(string)$item['pm']
	);
}
function creat6(&$item,$chest,$matches,&$item_obj)  {
	$nation = $matches[1];
	$skin = $matches[3];
	$id_type = "l";
	$id_piece = "c";
	
	$item_obj = new cloth(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$id_type,$id_piece,
			(string)$item['c'],
			(string)$item['pf'],(string)$item['msp'],(string)$item['mbp'],(string)$item['mpp'],
			(string)$item['dm'],(string)$item['pm']
	);
}
function creat7(&$item,$chest,$matches,&$item_obj)  {
	$nation = $matches[1];
	$skin = $matches[2];
	$id_type = "h";
	$id_piece = "ash";
	
	$item_obj = new shield(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$id_type,$id_piece,
			(string)$item['c'],
			(string)$item['pf'],(string)$item['msp'],(string)$item['mbp'],(string)$item['mpp'],
			(string)$item['dm'],(string)$item['pm']
	);
}
function creat8(&$item,$chest,$matches,&$item_obj)  {
	$item_id = $matches[2];
	$nation = $matches[1];
	$skin = $matches[3];

	$item_obj = new jewel(
			$chest,(string)$item['slot'],(string)$item,
			(string)$item['q'],
			$nation,$skin,
			(string)$item['hp'],(string)$item['dur'],
			(string)$item['hpb'],(string)$item['sab'],(string)$item['stb'],(string)$item['fob'],
			(string)$item['e'],
			(string)$item['text'],
			$item_id
	);
}
function creat9(&$item,$chest,$matches,&$item_obj)  {
	$tmp_e = $matches[5];
	$nation = $matches[4];
	$id_name = $matches[1];
	$trans = array( 'a'=>'b', 'b'=>'f', 'c'=>'c', 'd'=>'e', 'e'=>'s');
	$e = $trans[$tmp_e];
	
	$item_obj = new material(
				$chest,(string)$item['slot'],(string)$item,
				(string)$item['q'],(string)((!isset($item['s']) || $item['s']==null)?"1":$item['s']),
				$nation,
				'loot',(string)$item['c'],
				$id_name,
				$e);
}
function creat10(&$item,$chest,$matches,&$item_obj)  {
	$tmp_e = $matches[3];
	$nation = $matches[2];
	$id_name = $matches[1];
	
	$trans = array( 'b'=>'b', 'c'=>'f', 'd'=>'c', 'e'=>'e', 'f'=>'s');
	$e = $trans[$tmp_e];

	$item_obj = new material(
				$chest,(string)$item['slot'],(string)$item,
				(string)$item['q'],(string)((!isset($item['s']) || $item['s']==null)?"1":$item['s']),
				$nation,
				'harvest',(string)$item['c'],
				$id_name,
				$e);
}
function creat11(&$item,$chest,$matches,&$item_obj)  {
	$item_obj = new item($chest,(string)$item['slot'],(string)$item,(string)$item['q'],(string)$item['s']);
}
?>