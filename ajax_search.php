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
require_once('./inc/flunker_api.php');
//ryzom_log_start('Flunker');
if( !isset($_SESSION['list_guild']) || empty($_SESSION['list_guild']) ) {
	header('Content-type: text/plain; charset=utf-8');
	echo '
	<div class="error">
		'.html_err_lost_session(true).'
	</div>';
	exit;
}

	if ( !empty($_POST['room']) ) {
		$room = $_POST['room'];
	}
	else {
		$room = 'armory';
	}
	
	@include(dirname(__FILE__).'/local/'.$_SESSION['lang'].'.code_'.$room.'.php');
	
	$items = $_SESSION[$room]['items'];
	
	$possibility_of_order = unserialize($_SESSION[$room]['possibility_and_order_of_sorters']);
	
	if ( !empty($_POST['order']) ) {
		$sortway = array();
		if ( !empty($_POST['sortway']) ) $sortway = $_POST['sortway'];
		$new_order = array();
		foreach( $_POST['order'] as $key => $order ) {
			$tmp = $possibility_of_order[$order];
			if(isset($sortway[$key]) && $sortway[$key] == "reverse") $tmp->inverseOrder();
			$new_order[] = $tmp;
		}
		
		sorterBy($items,$new_order);
		
	}
	
	
	$pattern = "/^BC" ;
	if( $room == 'armory' || $room == 'range' ) {
		# weapon_name
		if ( !empty($_POST['name_weapon']) ) {
			$pattern .= "_(".implode('|',$_POST['name_weapon']).")";
		}
		else if ( !empty($_POST['name_range']) ) {
			$pattern .= "_(".implode('|',$_POST['name_range']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# nation
		if ( !empty($_POST['name_nation']) ) {
			$pattern .= "_(".implode('|',$_POST['name_nation']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# energy
		if ( !empty($_POST['energy']) ) {
			$pattern .= "_(".implode('|',$_POST['energy']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
	}
	elseif( $room == 'ampli' ) {
		# weapon_name
		$pattern .= "_(ms2)";
		
		# nation
		if ( !empty($_POST['name_nation']) ) {
			$pattern .= "_(".implode('|',$_POST['name_nation']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# energy
		if ( !empty($_POST['energy']) ) {
			$pattern .= "_(".implode('|',$_POST['energy']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
	}
	else if ( $room == 'jewel' ) {
		# type and name
		if ( !empty($_POST['name_jewel']) ) {
			$pattern .= "_(".implode('|',$_POST['name_jewel']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# nation
		if ( !empty($_POST['name_nation']) ) {
			$pattern .= "_(".implode('|',$_POST['name_nation']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# energy
		if ( !empty($_POST['energy']) ) {
			$pattern .= "_(".implode('|',$_POST['energy']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# buff max
		if ( !empty($_POST['max_buff']) ) {
			$pattern .= "_([^_]*".$_POST['max_buff']."[^_]*)";
		}
		else  {
			$pattern .= "_([^_]*)";
		}
	}
	else if ( $room == 'dressing' ) {
		# nation
		if ( !empty($_POST['name_nation']) ) {
			$pattern .= "_(".implode('|',$_POST['name_nation']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# type and name
		if ( !empty($_POST['type_cloth']) ) {
			$pattern .= "_(".implode('|',$_POST['type_cloth']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		if ( !empty($_POST['name_cloth']) ) {
			$pattern .= "_(".implode('|',$_POST['name_cloth']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# skin
		if ( !empty($_POST[SKIN]) ) {
			$pattern .= "_((".implode('|',$_POST[SKIN]).")|s[lbwe])";
		}
		else  {
			$pattern .= "_((XXX)|s[lbwe])";
		}
		# color
		if ( !empty($_POST[COLOR]) ) {
			$pattern .= "_(".implode('|',$_POST[COLOR]).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# energy
		if ( !empty($_POST['energy']) ) {
			$pattern .= "_(".implode('|',$_POST['energy']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# buff max
		if ( !empty($_POST['max_buff']) ) {
			$pattern .= "_([^_]*".$_POST['max_buff']."[^_]*)";
		}
		else  {
			$pattern .= "_([^_]*)";
		}
	}
	else if ( $room == 'material' ) {
		$pattern .= "_([^_]*)";
		# nation
		if ( !empty($_POST['name_nation']) ) {
			$pattern .= "_(".implode('|',$_POST['name_nation']).")";
		}
		else {
			$pattern .= "_(XXX)";
		}
		# origin
		if ( !empty($_POST['origin']) ) {
			$pattern .= "_(".implode('|',$_POST['origin']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# utility
		if ( !empty($_POST['utility']) ) {
			$pattern .= "_(".implode('|',$_POST['utility']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
		# energy
		if ( !empty($_POST['energy']) ) {
			$pattern .= "_(".implode('|',$_POST['energy']).")";
		}
		else  {
			$pattern .= "_(XXX)";
		}
	}
	else if ( $room == ROOM_OTHER ) {
		# crystal
		if ( !empty($_POST['crystal']) ) {
			$pattern .= "_(".$_POST['crystal'].")";
		}
		else {
			$pattern .= "_([^_]*)";
		}
	}
	$pattern .= "_/" ;
	
	#quality
	if ( !empty($_POST['min_quality']) ) {
		$min_quality = $_POST['min_quality'];
	}
	else {
		$min_quality = 0;
	}
	if ( !empty($_POST['max_quality']) ) {
		$max_quality = $_POST['max_quality'];
	}
	else {
		$max_quality = 270;
	}
	
	#hall
	if ( !empty($_POST['guild_hall']) ) {
		$guild_hall = $_POST['guild_hall'];
	}
	
	#boost
	$boost_armilo = false;
	$boost_rubbarn = false;
	if ( !empty($_POST['boost']) && $_POST['boost']=='boost_armilo') {
		$boost_armilo = true;
	}
	if ( !empty($_POST['boost']) && $_POST['boost']=='boost_rubbarn') {
		$boost_rubbarn = true;
	}
	
	# text search
	if ( !empty($_POST['text_search']) ) {
		$text_search = explode(' ',without_accent(trim($_POST['text_search'])));
	}
	
	$items_filtered_and_unserialize = array();
	if ( isset($guild_hall)
		&& isset($items) && is_array($items)) {
		foreach( $items as $val) {
			$item = unserialize($val['str_object']);

			$display = true;
			if( preg_match($pattern, $item->barCode(), $matches) != 1 ) {
				$display = false;
			}
			if(  $room != 'other' ) {
				if( (int)$item->q < (int)$min_quality 
					|| (int)$item->q > (int)$max_quality ) {
					$display = false;
				}
				if( $display == true && !in_array($item->guild->id, $guild_hall) ) {
					$display = false;
				}
				if ($display == true && $boost_armilo==true && !$item->isArmiloBoost()) {
					$display = false;
				}
			}
			else {
				if( $display == true && !in_array($item->guild->id, $guild_hall) ) {
					$display = false;
				}
			}
			if ($display == true && $boost_rubbarn==true && !$item->isRubbarnBoost()) {
				$display = false;
			}
			if ($display == true && isset($text_search))  {
				foreach($text_search as $text) {
					if (preg_match("/".$text."/i", $item->description(), $matches) != 1) {
						$display = false;
					}
				}
			}
			if( $display == true ) {
				$items_filtered_and_unserialize[] = $item;
			}
		}
	}
	
	header('Content-type: text/plain; charset=utf-8');
	if (isset($items_filtered_and_unserialize) && is_array($items_filtered_and_unserialize)) {
		echo "<li id=\"ajax_nb_items\">".count($items_filtered_and_unserialize)."</li>\n";
		if (isset($_POST['reduce_hidden']) && $_POST['reduce_hidden']=='1') {
			foreach( $items_filtered_and_unserialize as $item) {
				echo $item->getFormLi();
			}
		}
		else {
			foreach( $items_filtered_and_unserialize as $item) {
				echo $item->getFormAndIconLi();
			}	
		}
	}
?>