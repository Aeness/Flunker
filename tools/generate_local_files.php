<?php
// patch where to find data sets
require_once(dirname(__FILE__).'/ryzom_extra/ryzom_extra.php');
require_once(dirname(__FILE__).'/../inc/core/constants.php');
require_once(dirname(__FILE__).'/../inc/core/functions_parse.php');
define('FLUKER_LOCAL_PATH', dirname(__FILE__)."/../local/");

$_ext = 'item';
$all_lang = array ("fr","en","de");
$all_room = array ("material","other","armory","ampli","range","jewel","dressing");
$title_room_other = "item in the bazaar room";
$title_room_material = "material";
$title_room_armory = "weapon";
$title_room_ampli = "magic amplifier";
$title_room_range = "range weapon";
$title_room_jewel = "jewel";
$title_room_dressing = "cloth";

$str_language_fr = "a French Label";
$str_language_en = "an English Label";
$str_language_de = "a German Label";

foreach($all_lang as $lang) {

	// include translation file if needed
	if(!isset($cache[$_ext][$lang])){
		// use serialize/unserialize saves lot of memory
		$file = sprintf('%s/data/words_%s_%s.serial', RYZOM_EXTRA_PATH, $lang, $_ext);
		$cache[$_ext][$lang]=ryzom_extra_load_dataset($file);
	}
	
	
	
	foreach($all_room as $room ) {
		$title_room = "title_room_".$room;
		$str_language = "str_language_".$lang;
		$file = FLUKER_LOCAL_PATH.$lang.".code_".$room.".php";
		if (!file_exists($file) || is_writable($file)) {
			$fp = fopen($file,"wb");

			if(isset($fp)) {
				if (fwrite($fp, "<?php\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, "/** \file\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " * \brief Translate ryzom code of {$$title_room} to {$$str_language}.\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " * \n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " * Translate ryzom code of {$$title_room} to {$$str_language}.\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " * Must be encoded in UTF-8\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " * Genrerated with ryzom_extra (http://github.com/Aeness/ryzom_extra/tree/master).\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				if (fwrite($fp, " */\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				
				foreach ( $cache[$_ext][$lang] as $id => $word ) {
					global $pattern_list;
					global $room_list;
					$word['name']=str_replace('""',"'",$word['name']);
					$word['name']=str_replace('"','',$word['name']);
					$word['name']=str_replace('<','&lt;',$word['name']);
					$word['name']=str_replace('>','&gt;',$word['name']);
					
				
					$item = $id.".sitem";
					
					$find = false;
					foreach($pattern_list as $key => $pattern) {
						if( preg_match($pattern, $item, $matches) == 1 ) {
						//echo ("{$room}=={$room_list[$key]}<br/>");
							if($room==$room_list[$key])
							if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
								throw new Exception(__("Can not write in the file")." ".$file.".");
							}/*
							$pfonc = $pfonc_list[$key];
							$pfonc($item,$chest,$matches,$item_obj);
							$room = $room_list[$key];*/
							$find = true;
							break;
						}
					}
					if ($find == false) {
						if($room==$room_list[PATTERN_ELSE])
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					/*
					if( preg_match($pattern_refugee, $item, $matches) == 1 ) {
						if($room=="other")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_ampli, $item, $matches) == 1 ) {
						if($room=="ampli")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_weapon, $item, $matches) == 1 ) {
						if($room=="armory")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_range, $item, $matches) == 1 ) {
						if($room=="range")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_armor, $item, $matches) == 1 ) {
						if($room=="dressing")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_armor_cp, $item, $matches) == 1 ) {
						if($room=="dressing")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_shield, $item, $matches) == 1 ) {
						if($room=="dressing")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_jewel, $item, $matches) == 1 ) {
						if($room=="jewel")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_material_loot, $item, $matches) == 1 ) {
						if($room=="material")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					elseif( preg_match($pattern_material_harvest, $item, $matches) == 1 ) {
						if($room=="material")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}
					else {
						if($room=="other")
						if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
							throw new Exception(__("Can not write in the file")." ".$file.".");
						}
					}*/

				}
				if (fwrite($fp, "?>\n") === FALSE) {
					throw new Exception(__("Can not write in the file")." ".$file.".");
				}
				fflush($fp);
			}
			fclose($fp);
		}
		else {
			throw new Exception(__("Can not write in the file")." ".$file.".");
		}
	}
}

function write_text(&$item,$chest,$matches,&$item_obj,&$room)  {
	if($room==$current_room)
	if (fwrite($fp, "\$GLOBALS['__lang'][\"$id\"]=\"{$word['name']}\";"."\n") === FALSE) {
		throw new Exception(__("Can not write in the file")." ".$file.".");
	}
}
?>
<html><body>Files Generated in <?php echo FLUKER_LOCAL_PATH ;?></body></html>