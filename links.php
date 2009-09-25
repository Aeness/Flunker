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
reinit_session();
//ryzom_log_start('Flunker');

if ((isset($_GET['ckey']) && $_GET['ckey'] != '')
	|| isset($_GET['test']) ){
	$display_link = false;
	if( isset($_SESSION['comeFrom']) && $_SESSION['comeFrom'] == 'index') {
		$display_link = true;
	}
	
	$list_ckey = array();
	if(is_array($_GET['ckey'])) {
		$list_ckey = $_GET['ckey'];
	}
	else {
		$list_ckey[] =  $_GET['ckey'];
	}
	
	$error_msg = "";
	$items = array();
	$sorter_items = array();
	$total_stack	 = array();
	$material_by_code = array();
	
	$list_ckey_treated = array();
	$list_correct_ckey = array();
	foreach( $list_ckey as $ckey ) {
		if( $ckey != "") {
			# if a key is already treated, we ignore it
			if(!in_array($ckey, $list_ckey_treated)) {
				$list_ckey_treated[] = $ckey;
				
				$key = ryzom_decrypt($ckey,FLUNKER_CRYPT_KEY);
				
				try {
					$uid=0;$gid=0;$slot=0;$full=false;
					if( ryzom_guild_valid_key($key, $gid)) {
						$list_correct_ckey[] = $ckey;
						$xml = ryzom_guild_simplexml($key);
						if ($xml==null) {
							throw new Exception(__("Connection with ryzom_api impossible."));
						}
						$guild = parse_guild($xml);
						parse_item($xml,'/guild/room/*',$guild);
						$list_guild[$guild->id] = $guild;
					}
					else if (ryzom_character_valid_key($key, $uid, $slot, $full)) {
						$list_correct_ckey[] = $ckey;
						$xml = ryzom_character_simplexml($key, 'full');
						if ($xml==null) {
							throw new Exception(__("Connection with ryzom_api impossible."));
						}
						$character = parse_character($xml);
						$xml_inv = ryzom_character_simplexml($key, 'items');
						if ($xml==null) {
							throw new Exception(__("Connection with ryzom_api impossible."));
						}
						parse_item($xml_inv,'/items/room/*',$character);
						$list_guild[$character->id] = $character;
					}
					else {
						$error_msg .= __('Invalid key')."<br />";
					}
				}
				catch  (Exception $e) {
					$error_msg .= $e->getMessage()."<br />";
					array_pop($list_correct_ckey);
				}
			}
		}
	}
	if ( $error_msg != "" ) {
	
		$error_msg = '<div class="error">'.$error_msg.'</div>';
		$error_msg.= '<br /><a class="ryzom-ui-button" href="?">'.__("Select your API Key again").'</a><hr/><br />';
	}
	if (count($list_guild)>0) {
		build_complexe_sorter();
		
		## form sorter : creat and choose
		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterInt('hr',	"Hit Rate",	SORT_DESC,$sorter_items[ROOM_ARMORY][HIT_RATE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('q',	"Quality",	SORT_ASC,	$sorter_items[ROOM_ARMORY][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('tt', "Weapon Type",	SORT_ASC,	$sorter_items[ROOM_ARMORY][TYPE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bd', "Duration",	SORT_DESC,$sorter_items[ROOM_ARMORY][DURATION]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bhp', "Hit Points",	SORT_DESC,$sorter_items[ROOM_ARMORY][HP]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('w', "Weight",		SORT_ASC, $sorter_items[ROOM_ARMORY][WEIGHT]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('dm', "Dodge mod.",	SORT_DESC,$sorter_items[ROOM_ARMORY][DODGE_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('pm', "Parry mod.",	SORT_DESC,$sorter_items[ROOM_ARMORY][PARRY_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('adm', "Adv. Dodge mod.",SORT_ASC,$sorter_items[ROOM_ARMORY][ADV_DODGE_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('apm', "Adv. Parry mod.",SORT_ASC,$sorter_items[ROOM_ARMORY][ADV_PARRY_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$_SESSION[ROOM_ARMORY]['possibility_and_order_of_sorters'] = serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_ARMORY]['nb_activated_sorters'] = 2;
		$_SESSION[ROOM_ARMORY]['items'] = $items[ROOM_ARMORY];
		$_SESSION[ROOM_ARMORY]['nb_items'] = count($items[ROOM_ARMORY]);
		
		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterInt('q', "Quality",		SORT_ASC, $sorter_items[ROOM_AMPLI][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bd', "Duration",	SORT_DESC,$sorter_items[ROOM_AMPLI][DURATION]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bhp', "Hit Points",	SORT_DESC,$sorter_items[ROOM_AMPLI][HP]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$_SESSION[ROOM_AMPLI]['possibility_and_order_of_sorters'] = serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_AMPLI]['nb_activated_sorters'] = 1;
		$_SESSION[ROOM_AMPLI]['items'] =	$items[ROOM_AMPLI];
		$_SESSION[ROOM_AMPLI]['nb_items'] = count($items[ROOM_AMPLI]);

		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterInt('hr',	"Hit Rate",	SORT_DESC,$sorter_items[ROOM_RANGE][HIT_RATE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('q',	"Quality",	SORT_ASC, $sorter_items[ROOM_RANGE][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('tt', "Weapon Type",	SORT_ASC,	$sorter_items[ROOM_RANGE][TYPE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bd', "Duration"	,	SORT_DESC,$sorter_items[ROOM_RANGE][DURATION]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('Bhp', "Hit Points"	,SORT_DESC,$sorter_items[ROOM_RANGE][HP]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('w', "Weight"		,SORT_ASC, $sorter_items[ROOM_RANGE][WEIGHT]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('r', "Range"		,SORT_DESC,$sorter_items[ROOM_RANGE][RANGE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$_SESSION[ROOM_RANGE]['nb_items'] = count($items[ROOM_RANGE]);
		
		$_SESSION[ROOM_RANGE]['possibility_and_order_of_sorters'] = serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_RANGE]['nb_activated_sorters'] = 2;
		$_SESSION[ROOM_RANGE]['items'] = $items[ROOM_RANGE];

		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterInt('q', "Quality",		SORT_ASC ,$sorter_items[ROOM_DRESSING][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('n', "Nationality",	SORT_ASC,	$sorter_items[ROOM_DRESSING][ORIGIN]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('p', "Piece",		SORT_ASC, $sorter_items[ROOM_DRESSING][PIECE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('tt', "Clothes Type",	SORT_ASC, $sorter_items[ROOM_DRESSING][TYPE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('e', "Energy",		SORT_ASC,	$sorter_items[ROOM_DRESSING][ENERGY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('c', "Color",		SORT_ASC,	$sorter_items[ROOM_DRESSING][COLOR]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('pf', "Protection (%)",	SORT_DESC,$sorter_items[ROOM_DRESSING][PROTECTION]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('msp', "Slashing Protec.",	SORT_DESC,$sorter_items[ROOM_DRESSING][MAX_SLASHING]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('mbp', "Blunt Protec.",	SORT_DESC,$sorter_items[ROOM_DRESSING][MAX_BLUNT]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('mpp', "Piercing Protec.",	SORT_DESC,$sorter_items[ROOM_DRESSING][MAX_PIERCING]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('dm', "Dodge mod.",	SORT_DESC,$sorter_items[ROOM_DRESSING][DODGE_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('pm', "Parry mod.",	SORT_DESC,$sorter_items[ROOM_DRESSING][PARRY_MOD]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		
		$_SESSION[ROOM_DRESSING]['possibility_and_order_of_sorters'] = serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_DRESSING]['nb_activated_sorters'] = 3;
		$_SESSION[ROOM_DRESSING]['items'] = $items[ROOM_DRESSING];
		$_SESSION[ROOM_DRESSING]['nb_items'] = count($items[ROOM_DRESSING]);

		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterInt('q',	"Quality",	SORT_ASC,	$sorter_items[ROOM_JEWEL][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('n', "Nationality",	SORT_ASC,	$sorter_items[ROOM_JEWEL][ORIGIN]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('e', "Energy",		SORT_ASC,	$sorter_items[ROOM_JEWEL][ENERGY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterStr('p', "Jewel Piece",	SORT_ASC,	$sorter_items[ROOM_JEWEL][PIECE]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$_SESSION[ROOM_JEWEL]['possibility_and_order_of_sorters'] = serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_JEWEL]['nb_activated_sorters'] = 4;
		$_SESSION[ROOM_JEWEL]['items'] = $items[ROOM_JEWEL];
		$_SESSION[ROOM_JEWEL]['nb_items'] = count($items[ROOM_JEWEL]);

		unset($possibility_and_order_of_sorters);
		$sorter = new formSorterStr('u', "Purpose",		SORT_ASC,$sorter_items[ROOM_MATERIAL][UTILITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('q', "Quality",		SORT_ASC,$sorter_items[ROOM_MATERIAL][QUALITY]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('ts', "Total Amount ",SORT_ASC,$sorter_items[ROOM_MATERIAL][TOTAL_STACK]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$sorter = new formSorterInt('s', "Stack Size",	SORT_ASC,$sorter_items[ROOM_MATERIAL][STACK]);
		$possibility_and_order_of_sorters[$sorter->id] = $sorter;
		$_SESSION[ROOM_MATERIAL]['possibility_and_order_of_sorters'] =  serialize($possibility_and_order_of_sorters);
		$_SESSION[ROOM_MATERIAL]['nb_activated_sorters'] = 2;
		$_SESSION[ROOM_MATERIAL]['items'] = $items[ROOM_MATERIAL];
		$_SESSION[ROOM_MATERIAL]['nb_items'] = count($items[ROOM_MATERIAL]);

		unset($possibility_and_order_of_sorters);
		$display_order[] = new formSorterStr('fc', "Flunker Code",SORT_ASC,$sorter_items[ROOM_OTHER][FLUNKER_CODE]);
		$_SESSION[ROOM_OTHER]['possibility_and_order_of_sorters'] =  null;
		sorterBy($items[ROOM_OTHER],$display_order);
		$_SESSION[ROOM_OTHER]['items'] = $items[ROOM_OTHER];
		$_SESSION[ROOM_OTHER]['nb_items'] = count($items[ROOM_OTHER]);

		## form filter : creat and place
		$list_ordered_weapon = array (
			'pd1', 'bs1', 'ps1', 'bm1', 'ss1', 'sa1', 'pp2', 'bm2', 'ss2', 'sa2'
		);
		$list_ordered_range = array (
			'p1', 'b1', 'r2', 'b2', 'a2', 'l2'
		);
		$list_ordered_type_cloth = array (
			'tl', 'tm', 'th'
		);
		$list_ordered_piece_cloth = array (
			'apash', 'aph', 'apv', 'aps', 'apg', 'app', 'apc', 'apb'
		);
		$list_ordered_jewel = array (
			'a', 'b', 'd', 'e', 'p', 'r'
		);
		$list_ordered_nation_weapon = array (
			'nt', 'nz', 'nm', 'nf', 'nokam', 'nokar', 'nc'
		);
		$list_ordered_origin_cloth = array (
			'nt', 'nz', 'nm', 'nf', 'ncocd'
		);
		$list_ordered_nation_jewel = array (
			'nt', 'nz', 'nm', 'nf'
		);
		$list_ordered_place_material = array (
			'pl', 'pj', 'pf', 'pd', 'pp', 'pc'
		);
		$list_ordered_utility = array (
			'u01', 'u02', 'u03', 'u04', 'u05', 'u06', 'u07', 'u08', 'u09', 'u10', 'uot'
		);
		$list_ordered_energy =  array (
			'eb', 'ef', 'ec', 'ee', 'es'
		);
		$list_ordered_buff =  array (
			"hpb", "sab", "stb", "fob"
		);
		$list_ordered_skin_cloth =  array (
			"s1", "s2", "s3", "sm"
		);
		$list_ordered_material_origine =  array (
			"loot", "harvest"
		);
		$list_ordered_jewel_cloth =  array (
			"boost_armilo"
		);
		$list_ordered_weapon_boost =  array (
			"boost_rubbarn"
		);
		$list_ordered_cloth_color =  array (
			"c0", "c1", "c2", "c3", "c4", "c5", "c6", "c7"
		);
		$list_ordered_crystal =  array (
			"crystal50", "crystal100", "crystal150", "crystal200", "crystal250", 
		);

		$_SESSION[ROOM_ARMORY]['filter_form'][] = serialize(new listFormFilter('name_weapon','Weapon Name',$list_ordered_weapon,false));
		$_SESSION[ROOM_ARMORY]['filter_form'][] = serialize(new choiceFormFilter('boost','Boost type',$list_ordered_weapon_boost));
		$_SESSION[ROOM_ARMORY]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_ARMORY]['filter_form'][] = serialize(new listFormFilter('name_nation','Nation Name',$list_ordered_nation_weapon,true));
		$_SESSION[ROOM_ARMORY]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_ARMORY]['last_filter_line']["left"] = serialize(new intervalFormFilter('quality','Quality',0,250));
		$_SESSION[ROOM_ARMORY]['last_filter_line']["right"] = serialize(new textFormFilter());
		
		$_SESSION[ROOM_AMPLI]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_AMPLI]['filter_form'][] = serialize(new listFormFilter('name_nation','Nation Name',$list_ordered_nation_weapon,true));
		$_SESSION[ROOM_AMPLI]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_AMPLI]['last_filter_line']["left"] = serialize(new intervalFormFilter('quality','Quality',0,250));
		$_SESSION[ROOM_AMPLI]['last_filter_line']["right"] = serialize(new textFormFilter());

		$_SESSION[ROOM_RANGE]['filter_form'][] = serialize(new listFormFilter('name_range','Weapon Name',$list_ordered_range,false));
		$_SESSION[ROOM_RANGE]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_RANGE]['filter_form'][] = serialize(new listFormFilter('name_nation','Nation Name',$list_ordered_nation_weapon,true));
		$_SESSION[ROOM_RANGE]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_RANGE]['last_filter_line']["left"] = serialize(new intervalFormFilter('quality','Quality',0,250));
		$_SESSION[ROOM_RANGE]['last_filter_line']["right"] = serialize(new textFormFilter());

		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter('type_cloth','Clothes Type',$list_ordered_type_cloth,null));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter('name_cloth','Clothes Name',$list_ordered_piece_cloth,true));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new choiceFormFilter('max_buff','Max Buff',$list_ordered_buff));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new choiceFormFilter('boost','Boost type',$list_ordered_jewel_cloth));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter(SKIN,'Skin',$list_ordered_skin_cloth,true));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter(COLOR,'Color',$list_ordered_cloth_color,true));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new listFormFilter('name_nation','Nation Name',$list_ordered_origin_cloth,true));
		$_SESSION[ROOM_DRESSING]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_DRESSING]['last_filter_line']["left"] = serialize(new intervalFormFilter('quality','Quality',0,250));
		$_SESSION[ROOM_DRESSING]['last_filter_line']["right"] = serialize(new textFormFilter());

		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new listFormFilter('name_jewel','Jewel Type',$list_ordered_jewel,true));
		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new choiceFormFilter('max_buff','Max Buff',$list_ordered_buff));
		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new choiceFormFilter('boost','Boost type',$list_ordered_jewel_cloth));
		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new listFormFilter('name_nation','Nation Name',$list_ordered_nation_jewel,true));
		$_SESSION[ROOM_JEWEL]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_JEWEL]['last_filter_line']["left"] = serialize(new intervalFormFilter('quality','Quality',0,250));
		$_SESSION[ROOM_JEWEL]['last_filter_line']["right"] = serialize(new textFormFilter());
		
		$_SESSION[ROOM_MATERIAL]['filter_form'][] = serialize(new listFormFilter('origin','Origin',$list_ordered_material_origine,true));
		$_SESSION[ROOM_MATERIAL]['filter_form'][] = serialize(new listFormFilter('utility','Materiel Utility',$list_ordered_utility,false));
		$_SESSION[ROOM_MATERIAL]['filter_form'][] = serialize(new listFormFilter('energy','Energy',$list_ordered_energy,true));
		$_SESSION[ROOM_MATERIAL]['filter_form'][] = serialize(new listFormFilter('name_nation','Place Name',$list_ordered_place_material,true));
		$_SESSION[ROOM_MATERIAL]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_MATERIAL]['last_filter_line']["left"] = serialize(new intervalFormFilter(QUALITY,'Quality',0,270));
		$_SESSION[ROOM_MATERIAL]['last_filter_line']["right"] = serialize(new textFormFilter());
		
		$_SESSION[ROOM_OTHER]['filter_form'][] = serialize(new choiceFormFilter('crystal','Crystal',$list_ordered_crystal));
		$_SESSION[ROOM_OTHER]['filter_form'][] = serialize(new ghFormFilter($list_guild));
		$_SESSION[ROOM_OTHER]['last_filter_line']["left"] = serialize(new textFormFilter());

		## save the guild list
		$_SESSION['list_guild'] = serialize($list_guild);
		
		session_write_close();
		if ($display_link == false) {
			header('Location: entrance.php?');
		}
		
		## build links
		$str_list_ckey = "";
		$value_of_param = urlencode("ckey[]");
		if(is_array($list_correct_ckey)) {
			foreach ($list_correct_ckey as $val) {
				$str_list_ckey .= "&".$value_of_param."=".urlencode((string)$val);
			}
		}
		$str_language = "language=".$_SESSION['lang'];
		$str_skin = "&skin=".$_SESSION['skin'];
		$str_url = htmlentities(flunker_base_url()."links.php?".$str_language.$str_skin.$str_list_ckey);
		$str_href = htmlentities("<a xml:lang=\"".$_SESSION['lang']."\" lang=\"".$_SESSION['lang']."\" href=\"".htmlentities(flunker_base_url()."links.php?".$str_language.$str_skin.$str_list_ckey)."\">".__("Funker")."</a>");
		$str_bb1 = htmlentities("[url=".flunker_base_url()."links.php?".$str_language.$str_skin.$str_list_ckey."]".__("Flunker")."[/url]");
	}
}
else {
	header('Location: index.php?');
	exit();
}


header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_SESSION['lang']; ?>" lang="<?php echo $_SESSION['lang']; ?>">
	<head>
		<title><?php echo __("Flunker"); ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php echo flunker_render_header(); ?>
	</head>
	
	<body>
		<div id="main">
			<div style="text-align: right;">
				<a href="http://www.ryzom.com/"><img border="0" src="http://www.ryzom.com/data/logo.gif" alt=""/></a>
			</div>
			<?php echo language_flags_list($str_list_ckey); ?>
			<?php echo skin_flags_list($str_list_ckey); ?>
			
			<div class="ryzom-ui ryzom-ui-header">
				<div class="ryzom-ui-tl"><div class="ryzom-ui-tr">
					<div class="ryzom-ui-t">
						<div style="position:absolute;">
						<?php  echo __("Flunker"); ?>
						</div>
						<div style="text-align: right;">
							<a href="./?"><?php echo __("Sign Out"); ?></a>
						</div>
					</div>
				</div></div>

				<div class="ryzom-ui-l"><div class="ryzom-ui-r"><div id="search_content" class="ryzom-ui-m">
					<div id="result_content" class="ryzom-ui-body">
						<?php 
						if( $GLOBALS['__error'] != "" ) {
							echo '<div class="error">'.$GLOBALS['__error'].'</div>';
							$GLOBALS['__error'] = "";
						}
						?>
						<?php echo $error_msg; ?>
						<?php 
						$list_guild = unserialize($_SESSION['list_guild']);
						echo __('Welcome to Flunker, your guild halls are:')."<br />";
						$list = "";
						if( isset($list_guild) && is_array($list_guild) ) {
							foreach($list_guild as $guild) {
								$list .= $guild->getHtml();
							}
							echo $list;
						}
						?>
						<form action=""><p style="clear: left;">
							<?php echo __("Ensure that you have selected the right language and skin."); ?><br />
							<?php echo __("For a direct access (during your next session) to your guild halls, bookmark this page or use one of the following links:"); ?><p/><br/>
							
							<?php echo __("URL").__(":"); ?><br/>
							<input type="text" onclick="javascript:this.form.i1.focus();this.form.i1.select();" size="100" value="<?php echo $str_url; ?>" id="i1" readonly="readonly"/><br/><br/>
							<?php echo __("HTML Link").__(":"); ?><br/>
							<input type="text" onclick="javascript:this.form.i2.focus();this.form.i2.select();" size="100" value="<?php echo $str_href; ?>" id="i2" readonly="readonly"/><br/><br/>
							<?php echo __("bbcode Link").__(":"); ?><br/>
							<input type="text" onclick="javascript:this.form.i3.focus();this.form.i3.select();" size="100" value="<?php echo $str_bb1; ?>" id="i3" readonly="readonly"/><br/><br/>
						</form>
						<a href="entrance.php?" class="ryzom-ui-button"><?php echo __("Enter"); ?></a>
					</div>
				</div></div></div>

				<div class="ryzom-ui-bl"><div class="ryzom-ui-br"><div class="ryzom-ui-b"></div></div></div>
				<p class="ryzom-ui-notice"><?php echo __('powered by') ;?> <a class="ryzom-ui-notice" href="http://dev.ryzom.com/projects/ryzom-api/wiki">ryzom-api</a></p>
			</div>
	
		</div>
	</body></html>