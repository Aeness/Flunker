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
define("PATTERN_REFUGEE",	'/^icr(.*)?\.sitem/');
define("PATTERN_AMPLI",		'/^ic(.|(oka[rm]))m2ms([lbwe]|_[123])?\.sitem/');
define("PATTERN_WEAPON",		'/^ic(.|(oka[rm]))m(1|2)(.{2})([lbwe]|_[123])?\.sitem/');
define("PATTERN_RANGE",		'/^ic(.|(oka[rm]))r(1|2)(.)([lbwe]|_[123])?\.sitem/');
define("PATTERN_ARMOR",		'/^ic(.)a([h|l|m])([bghpsv])(_[23b])?\.sitem/');
define("PATTERN_ARMOR_CP",	'/^ic(.)a(cp)(_[23])?\.sitem/');
define("PATTERN_SHIELD",		'/^ic(.)s[sb]([lbwe]|_[23])?\.sitem/');
define("PATTERN_JEWEL",		'/^ic(.)j([abdepr])(_[23b])?\.sitem/');
define("PATTERN_MATERIAL_LOOT",	'/^m([0-9]{4})c(.)(.)(.)(.)01\.sitem/');
define("PATTERN_MATERIAL_HARVEST",	'/^m([0-9]{4})dxa(.)(.)01\.sitem/');
/**
 * For one guild, parses its xml file and builds its object which are in its hall.
 * @param xml 	&lt;<b>simplexml</b>&gt;	the xml file
 */
function parse_guild(&$xml) {

	return new guild((string)$xml->gid,(string)$xml->name,(string)$xml->icon);
}

/**
 * For one character, parses its xml file and builds its object which are in his apartment.
 * @param xml 	&lt;<b>simplexml</b>&gt;	the xml file
 */
function parse_character(&$xml) {

	return new apartment((string)$xml->cid,(string)$xml->name,(string)$xml->guild->icon);
}

/**
 * For one chest, parses its xml file and builds tab filter used by Filter and Item.
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
}

/**
 * Use global variables : items, sorter_items.
 */
function parse_an_item(&$item,$chest) {
	global $items;				///< &lt;<b>array{item]</b>&gt;  Where items are stoked
	global $sorter_items;		///< &lt;<b>array{item]</b>&gt;  Will use for sorting the array items
	
	
	$room = null;
	$item_obj = null;
	
	$patern_list[1]=PATTERN_REFUGEE;
	$patern_list[2]=PATTERN_AMPLI;
	$patern_list[3]=PATTERN_WEAPON;
	$patern_list[4]=PATTERN_RANGE;
	$patern_list[5]=PATTERN_ARMOR;
	$patern_list[6]=PATTERN_ARMOR_CP;
	$patern_list[7]=PATTERN_SHIELD;
	$patern_list[8]=PATTERN_JEWEL;
	$patern_list[9]=PATTERN_MATERIAL_LOOT;
	$patern_list[10]=PATTERN_MATERIAL_HARVEST;
	$patern_list[11]='/^(.)*/';

	$pfonc_list[1]="creat1";
	$pfonc_list[2]="creat2";
	$pfonc_list[3]="creat3";
	$pfonc_list[4]="creat4";
	$pfonc_list[5]="creat5";
	$pfonc_list[6]="creat6";
	$pfonc_list[7]="creat7";
	$pfonc_list[8]="creat8";
	$pfonc_list[9]="creat9";
	$pfonc_list[10]="creat10";
	$pfonc_list[11]="creat11";
	
	dynamic_pattern($patern_list,$pfonc_list,$item,$chest,$item_obj,$room);
	
	# creat sorters
	build_simple_sorter($room,$item_obj);
	$room = null;
}

function dynamic_pattern($patern_list,$pfonc_list,&$item,$chest,&$item_obj,&$room) {
	$matches = array();

	foreach($patern_list as $key => $patern) {
		$pfonc = $pfonc_list[$key];
		if( preg_match($patern, $item, $matches) == 1 ) {
			$pfonc($item,$chest,$matches,$item_obj,$room);
			break;
		}
	}
}
	
	function creat1(&$item,$chest,$matches,&$item_obj,&$room)  {
		$item_obj = new item($chest,(string)$item['slot'],(string)$item,(string)$item['q'],(string)$item['s']);
		$room = ROOM_OTHER;
	}
	function creat2(&$item,$chest,$matches,&$item_obj,&$room) {
		$nation = $matches[1];
		$skin = $matches[3];
		$sap = (empty($item['sap']))?-1:1;
		$room = ROOM_AMPLI;

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
	function creat3(&$item,$chest,$matches,&$item_obj,&$room)  {
		$sap = (empty($item['sap']))?-1:1;
		$item_id = $matches[4];
		$nb_hand = $matches[3];
		$nation = $matches[1];
		$skin = $matches[5];
		$room = ROOM_ARMORY;
	
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
	function creat4(&$item,$chest,$matches,&$item_obj,&$room)  {
		$sap = (empty($item['sap']))?-1:1;
		$item_id = $matches[4];
		$nb_hand = $matches[3];
		$nation = $matches[1];
		$skin = $matches[5];
		$room = ROOM_RANGE;
	
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
	function creat5(&$item,$chest,$matches,&$item_obj,&$room)  {
		$nation = $matches[1];
		$skin = $matches[4];
		$id_type = $matches[2];
		$id_piece = $matches[3];
		$room = ROOM_DRESSING;
		
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
	function creat6(&$item,$chest,$matches,&$item_obj,&$room)  {
		$nation = $matches[1];
		$skin = $matches[3];
		$id_type = "l";
		$id_piece = "c";
		$room = ROOM_DRESSING;
		
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
	function creat7(&$item,$chest,$matches,&$item_obj,&$room)  {
		$nation = $matches[1];
		$skin = $matches[2];
		$id_type = "h";
		$id_piece = "ash";
		$room = ROOM_DRESSING;
		
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
	function creat8(&$item,$chest,$matches,&$item_obj,&$room)  {
		$item_id = $matches[2];
		$nation = $matches[1];
		$skin = $matches[3];
		$room = ROOM_JEWEL;
	
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
	function creat9(&$item,$chest,$matches,&$item_obj,&$room)  {
		$tmp_e = $matches[5];
		$nation = $matches[4];
		$id_name = $matches[1];
		$room = ROOM_MATERIAL;

		$trans = array( 'a'=>'b', 'b'=>'f', 'c'=>'c', 'd'=>'e', 'e'=>'s');
		$e = $trans[$tmp_e];
		
		$item_obj = new material(
					$chest,(string)$item['slot'],(string)$item,
					(string)$item['q'],(string)$item['s'],
					$nation,
					'loot',(string)$item['c'],
					$id_name,
					$e);
	}
	function creat10(&$item,$chest,$matches,&$item_obj,&$room)  {
		$tmp_e = $matches[3];
		$nation = $matches[2];
		$id_name = $matches[1];
		$room = ROOM_MATERIAL;
		
		$trans = array( 'b'=>'b', 'c'=>'f', 'd'=>'c', 'e'=>'e', 'f'=>'s');
		$e = $trans[$tmp_e];

		$item_obj = new material(
					$chest,(string)$item['slot'],(string)$item,
					(string)$item['q'],(string)$item['s'],
					$nation,
					'harvest',(string)$item['c'],
					$id_name,
					$e);
	}
	function creat11(&$item,$chest,$matches,&$item_obj,&$room)  {
		$item_obj = new item($chest,(string)$item['slot'],(string)$item,(string)$item['q'],(string)$item['s']);
		$room = ROOM_OTHER;
	}
?>