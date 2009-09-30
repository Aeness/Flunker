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
/** \file
 * The php sorter need colum arrays.
 * These functions build the arrays and use them.
 * See the php documentation about array_multisort.
 */

/**
 * Buid sorter which needs item information and start to build sorter which needs global information.
 * Use global variables : items, sorter_items, total_stack, material_by_code.
 * @param room 	&lt;<b>string</b>&gt;		the room where the item is put
 * @param item_obj 	&lt;<b>item</b>&gt;		the item added to sorter_items and item
 */
function build_simple_sorter($room,&$item_obj) {
	global $items;				///< &lt;<b>array{item}</b>&gt;  Where items are stoked
	global $sorter_items;		///< &lt;<b>array{item}</b>&gt;  Will use for sorting the array items
	global $total_stack;		///< &lt;<b>array{int}</b>&gt;  Will use for writins the total amount of material, give the total for each material
	global $material_by_code;	///< &lt;<b>array{int}</b>&gt;  Will use for writins the total amount of material, give the material object
	
	
	
	if( $room == ROOM_AMPLI ) {	
		$key = $item_obj->flunkerId();
		$items[ROOM_AMPLI][$key] = array(
			QUALITY	 => (string)$item_obj->q,
			DURATION	 => (string)$item_obj->dur,
			HP		 => (string)$item_obj->hp,
			'str_object'  => serialize($item_obj)
		);
		$sorter_items[ROOM_AMPLI][QUALITY][$key]	 = (int)$item_obj->q;
		$sorter_items[ROOM_AMPLI][DURATION][$key]	 = (int)$item_obj->dur;
		$sorter_items[ROOM_AMPLI][HP][$key]	 	 = (int)$item_obj->hp;
	}
	else if( $room == ROOM_ARMORY ){
		$key = $item_obj->flunkerId();
		$items[ROOM_ARMORY][$key] = array(
			TYPE			 => (string)$item_obj->id_weapon,
			HIT_RATE		 => (string)$item_obj->hr,
			QUALITY		 => (string)$item_obj->q,
			DURATION		 => (string)$item_obj->dur,
			HP			 => (string)$item_obj->hp,
			WEIGHT		 => (string)($item_obj->w*100),
			DODGE_MOD		 => (string)$item_obj->dm,
			PARRY_MOD		 => (string)$item_obj->pm,
			ADV_DODGE_MOD	 => (string)$item_obj->adm,
			ADV_PARRY_MOD	 => (string)$item_obj->apm,
			'str_object'	 => serialize($item_obj)
		);
		$sorter_items[ROOM_ARMORY][TYPE][$key]			 = (string)$item_obj->id_weapon;
		$sorter_items[ROOM_ARMORY][HIT_RATE][$key]		 = (int)$item_obj->hr;
		$sorter_items[ROOM_ARMORY][QUALITY][$key]		 = (int)$item_obj->q;
		$sorter_items[ROOM_ARMORY][DURATION][$key]		 = (int)$item_obj->dur;
		$sorter_items[ROOM_ARMORY][HP][$key]		 	 = (int)$item_obj->hp;
		$sorter_items[ROOM_ARMORY][WEIGHT][$key]		 = (int)($item_obj->w*100);
		$sorter_items[ROOM_ARMORY][DODGE_MOD][$key]		 = (int)$item_obj->dm;
		$sorter_items[ROOM_ARMORY][PARRY_MOD][$key]		 = (int)$item_obj->pm;
		$sorter_items[ROOM_ARMORY][ADV_DODGE_MOD][$key]	 = (int)$item_obj->adm;
		$sorter_items[ROOM_ARMORY][ADV_PARRY_MOD][$key]	 = (int)$item_obj->apm;
	}
	else if( $room == ROOM_RANGE ){
		$key = $item_obj->flunkerId();
		$items[ROOM_RANGE][$key] = array(
			TYPE		 => (string)$item_obj->id_weapon,
			HIT_RATE	 => (string)$item_obj->hr,
			QUALITY	 => (string)$item_obj->q,
			DURATION	 => (string)$item_obj->dur,
			HP		 => (string)$item_obj->hp,
			WEIGHT	 => (string)$item_obj->w,
			RANGE	 => (string)$item_obj->r,
			'str_object'  => serialize($item_obj)
		);
		$sorter_items[ROOM_RANGE][TYPE][$key]		 = (string)$item_obj->id_weapon;
		$sorter_items[ROOM_RANGE][HIT_RATE][$key]	 = (int)$item_obj->hr;
		$sorter_items[ROOM_RANGE][QUALITY][$key]	 = (int)$item_obj->q;
		$sorter_items[ROOM_RANGE][DURATION][$key]	 = (int)$item_obj->dur;
		$sorter_items[ROOM_RANGE][HP][$key]		 = (int)$item_obj->hp;
		$sorter_items[ROOM_RANGE][WEIGHT][$key]		 = (int)$item_obj->w;
		$sorter_items[ROOM_RANGE][RANGE][$key]		 = (int)$item_obj->r;
	}
	else if( $room == ROOM_DRESSING ) {
		
		$key = $item_obj->flunkerId();
		$items['dressing'][$key] = array(
			QUALITY		=> (string)$item_obj->q,
			TYPE			=> (string)$item_obj->id_type,
			PIECE		=> (string)$item_obj->id_piece,
			ORIGIN		=> (string)$item_obj->id_peuple,
			PROTECTION	=> (string)$item_obj->pf,
			MAX_SLASHING	=> (string)$item_obj->msp,
			MAX_BLUNT		=> (string)$item_obj->mbp,
			MAX_PIERCING	=> (string)$item_obj->mpp,
			DODGE_MOD		=> (string)$item_obj->dm,
			PARRY_MOD		=> (string)$item_obj->pm,
			ENERGY		=> (string)$item_obj->e,
			COLOR		=> (string)$item_obj->c,
			'str_object'	=> serialize($item_obj)
		);
		$sorter_items[ROOM_DRESSING][QUALITY][$key]		 = (int)$item_obj->q;
		$sorter_items[ROOM_DRESSING][TYPE][$key]		 = (string)$item_obj->id_type;
		$sorter_items[ROOM_DRESSING][PIECE][$key]		 = (string)$item_obj->id_piece;
		$sorter_items[ROOM_DRESSING][ORIGIN][$key]		 = (string)$item_obj->id_peuple;
		$sorter_items[ROOM_DRESSING][PROTECTION][$key]	 = (int)$item_obj->pf;
		$sorter_items[ROOM_DRESSING][MAX_SLASHING][$key]	 = (int)$item_obj->msp;
		$sorter_items[ROOM_DRESSING][MAX_BLUNT][$key] 	 = (int)$item_obj->mbp;
		$sorter_items[ROOM_DRESSING][MAX_PIERCING][$key]	 = (int)$item_obj->mpp;
		$sorter_items[ROOM_DRESSING][DODGE_MOD][$key]	 = (int)$item_obj->dm;
		$sorter_items[ROOM_DRESSING][PARRY_MOD][$key]	 = (int)$item_obj->pm;
		$sorter_items[ROOM_DRESSING][ENERGY][$key]		 = (string)$item_obj->e;
		$sorter_items[ROOM_DRESSING][COLOR][$key]		 = (string)$item_obj->c;
	}
	else if( $room == ROOM_JEWEL ) {
		$key = $item_obj->flunkerId();
		$items[ROOM_JEWEL][$key] = array(
			PIECE	=> (string)$item_obj->id_piece,
			QUALITY	=> (string)$item_obj->q,
			ORIGIN	=> (string)$item_obj->id_peuple,
			ENERGY	=> (string)$item_obj->e,
			'str_object'  => serialize($item_obj)
		);
		$sorter_items[ROOM_JEWEL][PIECE][$key]	 = (string)$item_obj->id_piece;
		$sorter_items[ROOM_JEWEL][QUALITY][$key] = (int)$item_obj->q;
		$sorter_items[ROOM_JEWEL][ORIGIN][$key]	 = (string)$item_obj->id_peuple;
		$sorter_items[ROOM_JEWEL][ENERGY][$key]	 = (string)$item_obj->e;
	}
	else if( $room == ROOM_MATERIAL ) {
		$code = $item_obj->q.$item_obj->ryzom_code;
		
		if ($total_stack[$code] == null) $total_stack[$code]=0;
		$total_stack[$code]	 += (int)$item_obj->s;
		$material_by_code[$code][] = $item_obj;
	}
	else if( $room == ROOM_OTHER ) {
		$key = $item_obj->flunkerId();
		$items[ROOM_OTHER][$key] = array(
			FLUNKER_CODE	 => (string)$item_obj->flunker_code,
			'str_object'	 => serialize($item_obj)
		);
		$sorter_items[ROOM_OTHER][FLUNKER_CODE][$key] = (string)$item_obj->flunker_code;
	}
}
/**
 * Buid sorter which needs global information.
 * Use global variables : items, sorter_items, total_stack, material_by_code.
 */
function build_complexe_sorter() {
	global $items;				///< &lt;<b>array{item]</b>&gt;  Where items are stoked
	global $sorter_items;		///< &lt;<b>array{item]</b>&gt;  Will use for sorting the array items
	global $total_stack;		///< &lt;<b>array{int]</b>&gt;  Will use for writins the total amount of material, give the total for each material
	global $material_by_code;	///< &lt;<b>array{int]</b>&gt;  Will use for writins the total amount of material, give the material object
	
	## calcul of total stack size with all the halls 
	foreach ($material_by_code as $code => $list_item) {
		foreach ($list_item as $material) {
			$key = $material->flunkerId();
			$material->total_stack = (string)$total_stack[$code];
			$items[ROOM_MATERIAL][$key] = array(
				QUALITY		=> (string)$material->q,
				UTILITY		=> (string)$material->id_utility,
				TOTAL_STACK	=> (string)$material->total_stack,
				STACK		=> (string)$material->s,
				'str_object' 	=> null
			);
			$sorter_items[ROOM_MATERIAL][QUALITY][$key]		 = (int)$material->q;
			$sorter_items[ROOM_MATERIAL][UTILITY][$key]		 = (string)$material->id_utility;
			$sorter_items[ROOM_MATERIAL][TOTAL_STACK][$key]	 = (int)$material->total_stack;
			$sorter_items[ROOM_MATERIAL][STACK][$key]		 = (int)$material->s;

			$items[ROOM_MATERIAL][$key]['str_object'] = serialize($material);
		}
	}
	
}

/**
 * Sort the items.
 * @param sortered 			&lt;<b>array{item}</b>&gt;	the array sortered
 * @param my_sorters_with_key 	&lt;<b>array</b>&gt;		the arrays sorting sortered
 */
function sorterBy(&$sortered,$my_sorters_with_key) {

	if (!is_array($my_sorters_with_key)) { return; }
	if (!is_array($sortered)) { return; }
	$my_sorters = array_values($my_sorters_with_key) ;
	$nb_sorter = count($my_sorters);
	
	if ($nb_sorter == 1) {
		list( $sorter1 ) = $my_sorters;
		array_multisort(
			$sorter1->values,$sorter1->typeOfContent,$sorter1->order,
			$sortered
		);
	}
	else if ($nb_sorter == 2) {
		list( $sorter1,$sorter2) = $my_sorters;
		array_multisort(
			$sorter1->values,$sorter1->typeOfContent,$sorter1->order,
			$sorter2->values,$sorter2->typeOfContent,$sorter2->order,
			$sortered
		);
	}
	else if ($nb_sorter == 3) {
		list( $sorter1,$sorter2,$sorter3) = $my_sorters;
		array_multisort(
			$sorter1->values,$sorter1->order,$sorter1->typeOfContent,
			$sorter2->values,$sorter2->order,$sorter2->typeOfContent,
			$sorter3->values,$sorter3->order,$sorter3->typeOfContent,
			$sortered
		);
	}
	else if ($nb_sorter == 4) {
		list( $sorter1,$sorter2,$sorter3,$sorter4) = $my_sorters;
		array_multisort(
			$sorter1->values,$sorter1->order,$sorter1->typeOfContent,
			$sorter2->values,$sorter2->order,$sorter2->typeOfContent,
			$sorter3->values,$sorter3->order,$sorter3->typeOfContent,
			$sorter4->values,$sorter4->order,$sorter4->typeOfContent,
			$sortered
		);
	}
	else /*if ($nb_sorter == 5)*/ {
		list( $sorter1,$sorter2,$sorter3,$sorter4,$sorter5) = $my_sorters;
		array_multisort(
			$sorter1->values,$sorter1->order,$sorter1->typeOfContent,
			$sorter2->values,$sorter2->order,$sorter2->typeOfContent,
			$sorter3->values,$sorter3->order,$sorter3->typeOfContent,
			$sorter4->values,$sorter4->order,$sorter4->typeOfContent,
			$sorter5->values,$sorter5->order,$sorter5->typeOfContent,
			$sortered
		);
	}
}
?>