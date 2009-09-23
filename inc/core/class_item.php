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
 * These classes have a couple of uses :
 * <ul>
 * <li>Stock information about item.</li>
 * <li>Display information about item.</li>
 * </ul>
 */
 
class item
{
	public $ryzom_code;		///< &lt;<b>string</b>&gt; the "Ryzom" code : the Icon Name without ".sitem" at the end
	public $flunker_code;	///< &lt;<b>string</b>&gt; the Flunker code : The code corresponding to the title of the Item

	public $guild;			///< &lt;<b>guild</b>&gt; the guild which owns the item
	public $slot;			///< &lt;<b>int</b>&gt; the position of the item in the inventory

	public $icon_name;		///< &lt;<b>string</b>&gt; the Ryzom name of the icon

	public $q;			///< &lt;<b>int</b>&gt; quality
	public $s;			///< &lt;<b>int</b>&gt; stack size
	
	/**
	 * @param	guild		&lt;<b>Guild</b>&gt;  the guild which owns the item
	 * @param	slot		&lt;<b>int</b>&gt;  the position of the item in the inventory
	* @param	icon_name	&lt;<b>string</b>&gt;  the Ryzom name of the icon
	* @param	q		&lt;<b>int</b>&gt;  quality
	* @param	s		&lt;<b>int</b>&gt;  stack size
	 */
	public function __construct($guild,$slot,$icon_name,$q,$s)
	{
		$this->guild= $guild;
		$this->icon_name = $icon_name;
		$this->ryzom_code = str_replace(".sitem","",$icon_name);
		$this->flunker_code = $this->ryzom_code;
		
		$this->slot = $slot;
		$this->q = $q;
		$this->s = $s;
	}

	/**
	 * Get the Flunker identifiant of the Item.
	 * id : item_{id}_{slot}
	 * @return &lt;<b>string</b>&gt;
	 */
	public function flunkerId() {
		return "item_".$this->guild->id."_".$this->slot;
	}
	
	/**
	*  Give the Ryzom title for the item.
	 * Need to load the file local/{lang}.code_{room}.php before.
	 * @return &lt;<b>string</b>&gt;  the item title in the curent language
	 */
	public function getTitle() {
		return __($this->ryzom_code);
	}

	/**
	 * Get the Flunker bar code of the Item. The code contains almost all information for filtring the item.
	 * The bar code : BC_{the Flunker/Ryzom code}_
	 * @return &lt;<b>string</b>&gt;
	 */
	public function barCode() {
		if ($this->ryzom_code == "ixpca01" || $this->ryzom_code == "ixpca02") {
			return "BC_crystal".$this->q."_";
		}
		return "BC_".$this->flunker_code."_";
	}

	/**
	 * Get some word you do not find in the title and that can help the texte search.
	 * @return &lt;<b>string</b>&gt;a set of word, not a text
	 */
	protected function _description() {
		return /*__($this->flunker_code)." ".*/$this->guild->name;
	}

	/**
	 * Get the description of the Item.
	 * @return &lt;<b>string</b>&gt;a set of word, not a text
	 */
	public function description() {
		return withoutAccent($this->_description()." ".$this->getTitle());
	}

	/**
	 *  Give the HTML code for displaying the item icon.
	 * @see Use in _getFormContent, getIconLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getIcon() {
		return flunker_item_icon_image($this->icon_name,null,$this->q,$this->s,-1,$this->getTitle());
	}

	/**
	*  Give the HTML code for displaying the item icon and a hidden item form(start with the &lt;li&gt; balise).
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	public function getFormAndIconLi() {
		$res = "
			<li class='icon_box' id=\"".$this->flunkerId()."\">
				<div class='icon_div'>".$this->_getIcon("tooltip")."</div>
				<div class='form_div' style=\"display: none; width: 200px\">
				<div class='form_div_ryzom-ui-tl'><div class='form_div_ryzom-ui-tr'><div class='form_div_ryzom-ui-t'></div></div></div>
				
				<div class='ryzom-ui-l'><div class='ryzom-ui-r'><div class='ryzom-ui-m'>
					<ul class=\"attribut\">";
		$res .= $this->_getFormContent();
		$res .= "
					</ul>
					<br style='clear: left;' />
				</div></div></div>
				<div class='ryzom-ui-bl'><div class='ryzom-ui-br'><div class='ryzom-ui-b'></div></div></div>
				</div>
			</li>";
		return $res;
	}

	/**
	*  Give the HTML code for displaying the item icon(start with the &lt;li&gt; balise).
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	public function getIconLi() {
		return "
			<li class='icon_box' id=\"".$this->flunkerId()."\">
				<div class='icon_div'>".$this->_getIcon()."</div>
			</li>";
	}

	/**
	*  Give the HTML code for displaying the item form(start with the &lt;li&gt; balise).
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	public function getFormLi() {
		$res = "
			<li class='icon_box' id=\"".$this->flunkerId()."\">
				<div class='form_div'>
				<div class='form_div_ryzom-ui-tl'><div class='form_div_ryzom-ui-tr'><div class='form_div_ryzom-ui-t'></div></div></div>
				
				<div class='ryzom-ui-l'><div class='ryzom-ui-r'><div class='ryzom-ui-m'>
					<ul class=\"attribut\">";
		$res .= $this->_getFormContent();
		$res .= "
					</ul>
					<br style='clear: left;' />
				</div></div></div>
				<div class='ryzom-ui-bl'><div class='ryzom-ui-br'><div class='ryzom-ui-b'></div></div></div>
				</div>
			</li>";
		return $res;
	}

	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut all\">".$this->guild->name."</li>";
						
		return $res;
	}
};

class itemCraft extends item
{
	public $id_peuple;	///< &lt;<b>string</b>&gt;  Flunker identifiant of the "nationnality skin"
	public $skin;		///< &lt;<b>string</b>&gt; skin of the item

	public $text;	///< the text specified by the crafter
	
	public $hp;	///< &lt;<b>int</b>&gt; hp of the item (between 0 and dur)
	public $dur;	///< &lt;<b>int</b>&gt; durability of the item

	# bonus
	public $hpb;	///< &lt;<b>int</b>&gt; hp buff
	public $sab;	///< &lt;<b>int</b>&gt; sab buff
	public $stb;	///< &lt;<b>int</b>&gt; sta buff
	public $fob;	///< &lt;<b>int</b>&gt; focus buff

	public $e;	///< &lt;<b>string</b>&gt; energy of the item (b=basic f=fine c=choice e=excelent s=supreme)

	/**
	 * Build the Item and, if necessary, translate a Ryzom code to a Flunker code.
	 */
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text)
	{
		parent::__construct($guild,$slot,$icon_name,$q,null);
		
		$this->id_peuple = "n".$id_peuple;
		
		if ($skin == "") $skin = "1";
		else if ($skin[0]=="_") $skin = $skin[1];
		$this->skin = "s".$skin;
		
		$this->text = substr($text,4);

		$this->hp = $hp;
		$this->dur = $dur;
		
		$this->hpb = $hpb;
		$this->sab = $sab;
		$this->stb = $stb;
		$this->fob = $fob;
		
		$this->e = "e".$e;
	}

	/**
	*  Give the Funker title for the item (build with the object information).
	 * Need to load the file local/{lang}.code_{room}.php before.
	 * @return &lt;<b>string</b>&gt;  the item title in the curent language
	 */
	public function getTitle() {
		return parent::getTitle()." (".__($this->e).")";
	}

	/**
	 * Get some word you do not find in the title and that can help the texte search.
	 * @return &lt;<b>string</b>&gt;a set of word, not a text
	 */
	protected function _description() {
		return parent::_description()." ".__($this->id_peuple)." ".__($this->text);
	}
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->hp."/".(int)$this->dur."</li>

						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>";

		$res .= $this->_getEndFormContent();
						
		return $res;
	}
	
	public function _getEndFormContent() {
		if ($this->text!=null) {
			$res = "
						<li class=\"attribut decohr\"><hr/></li>
						<li class=\"attribut all\" title=\"{$this->text}\">".$this->_resumeText()."</li>";
		}
		else {
			$res = "
						<li class=\"attribut decohr\"><hr/></li>
						<li class=\"attribut all\">&nbsp;</li>";
		}
		$res .= "
						<li class=\"attribut decohr\"><hr/></li>
						<li class=\"attribut all\">".$this->guild->name."</li>";
						
		return $res;
	}
	
	protected function _resumeText() {
		if( strlen($this->text) > 29 ){
			return substr($this->text,0,25)."...";
		}
		return $this->text;
	}
	
	/**
	 * Tool Method finding the buffer(s) max of the itemCraft.
	 * @return &lt;<b>string</b>&gt;  list of the max buffer separated by '0'
	 */
	protected function _buffMax() {
		$buffMax[] = "hpb";
		$max = $this->hpb;
		$liste = array( "sab", "stb", "fob" );
		foreach( $liste as $var ){
			if( (int)$this->{$var} > (int)$max ) {
				unset($buffMax);
				$buffMax[] = $var;
				$max = $this->{$var};
			}
			elseif( (int)$this->{$var} == (int)$max ) {
				$buffMax[] = $var;
			}
		}
		
		return implode('0',$buffMax);
	}
	
	public function isArmiloBoost() {
		$somme_buff = $this->hpb;
		$liste = array( "sab", "stb", "fob" );
		foreach( $liste as $var ){
			$somme_buff += (int)$this->{$var};
		}
		if ($somme_buff > ((int)$this->q)/2) {
			return true;
		}
		return false;
	}
};

class cloth extends itemCraft
{
	public $id_type;	///< &lt;<b>string</b>&gt;  Flunker identifiant of the armor type
	public $id_piece;	///< &lt;<b>string</b>&gt;  Flunker identifiant of the piece of the armor

	public $c;	///< &lt;<b>int</b>&gt; color

	public $pf;	///< &lt;<b>int</b>&gt; protection factor
	public $msp;	///< &lt;<b>int</b>&gt; max slashing protection
	public $mbp;	///< &lt;<b>int</b>&gt; max blunt protection
	public $mpp;	///< &lt;<b>int</b>&gt; max piercing protection
	public $dm;	///< dodge modifier 
	public $pm;	///< parry modifier 

	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type,$id_piece,$c,$pf,$msp,$mbp,$mpp,$dm,$pm)
	{
		# specific treatement
		if ($id_peuple == "c" && $skin == "") $skin = "m";
		if ($skin == "_b") $skin = "m";
		if ($id_peuple == "c") $id_peuple = "cocd";
		if ($c == null) $c="1";
		
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text);		
		
		$this->id_type = "t".$id_type;
		$this->id_piece = "ap".$id_piece;
		
		$this->flunker_code = $this->id_piece;
		
		$this->c = "c".$c;
	
		$this->pf = $pf;
		$this->msp = $msp;
		$this->mbp = $mbp;
		$this->mpp = $mpp;
		
		$this->dm=$dm;
		$this->pm=$pm;
	}

	/**
	 * Get the Flunker bar code of the Item. The code contains almost all information for filtring the item.
	 * The bar code : BC_{id_peuple}_{id_type}_{id_piece}_{skin}_{energy}_{buff max}_
	 */
	public function barCode() {
		return "BC_".$this->id_peuple."_".$this->id_type."_".$this->id_piece."_".$this->skin."_".$this->c."_".$this->e."_".$this->_buffMax()."_";
	}

	/**
	 * Get some word you do not find in the title and that can help the texte search.
	 * @return &lt;<b>string</b>&gt;a set of word, not a text
	 */
	protected function _description() {
		return parent::_description()." ".__($this->c);
	}

	/**
	 *  Give the HTML code for displaying the item icon.
	 * @see Use in _getFormContent, getIconLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getIcon() {
		return flunker_item_icon_image($this->icon_name,($this->c!=nul)?$this->c[1]:"",$this->q,null,-1,$this->getTitle());
	}
	
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Skin").__(":")."</li>
						<li class=\"attribut\"> ".__($this->skin)."</li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->hp."/".(int)$this->dur."</li>
						
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>
						
						
						<li class=\"attribut decohr\"><hr/></li>
						<li class=\"attribut left\">".__("Dodge mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->dm."</li>
						<li class=\"attribut left\">".__("Parry mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->pm."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						<li class=\"attribut left\" style=\"text-align: left;\">".__("protection factor").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->pf."%</li>
						<li class=\"attribut left\" style=\"text-align: left;\">".__("max protection").__(":")."</li>
						<li class=\"attribut right\">&nbsp;</li>
						<li class=\"attribut left\">".__("slashing").__(":")."</li>
						<li class=\"attribut right\">".(int)$this->msp."</li>
						<li class=\"attribut left\">".__("blunt").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->mbp."</li>
						<li class=\"attribut left\">".__("piercing").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->mpp."</li>";

		$res .= $this->_getEndFormContent();
						
		return $res;
	}
};

class shield extends cloth
{
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type,$id_piece,$c,$pf,$msp,$mbp,$mpp,$dm,$pm)
	{
		# specific treatement
		if ($id_peuple == "c") $id_peuple = "cocd";
		
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type,$id_piece,$c,$pf,$msp,$mbp,$mpp,$dm,$pm);
	}
};

class jewel extends itemCraft
{
	public $id_piece;	///< &lt;<b>string</b>&gt;  identifiant of the piece
	
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_piece)
	{
		# specific treatement
		if ($e == null) $e = "b";
		
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text);
		$this->flunker_code = $id_piece;
		
		$this->id_piece = $id_piece;
	}
	
	/**
	 * Get the Flunker bar code of the Item. The code contains almost all information for filtring the item.
	 * The bar code : BC_{id_piece}_{id_peuple}_{energy}_{buff max}_
	 */
	public function barCode() {
		return "BC_".$this->id_piece."_".$this->id_peuple."_".$this->e."_".$this->_buffMax()."_";
	}
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->hp."/".(int)$this->dur."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>";

		$res .= $this->_getEndFormContent();
						
		return $res;
	}
};

class weapon extends itemCraft
{
	public $id_weapon;	///< &lt;<b>string</b>&gt;  Flunker identifiant for the weapon
	
	public $sap;	///< &lt;<b>int</b>&gt;  0 means no sap, 1 mean sap icon
	public $sl;	///< &lt;<b>int</b>&gt; ap load
	public $csl;	///< &lt;<b>int</b>&gt; current sap load 
	
	public $w;	///< &lt;<b>int</b>&gt; weight
	public $hr;	///< &lt;<b>int</b>&gt; hit rate
	
	public $special_ability;	///< &lt;<b>string</b>&gt; "Tekorn", "Vedice", "Maga" or "Cheng"

	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr)
	{
		# specific teatelent
		if (($id_peuple == "okam" || $id_peuple == "okar") && $skin=="_1") {
			$this->special_ability = "Tekorn";
			$skin = "";
		}
		else if (($id_peuple == "okam" || $id_peuple == "okar") && $skin=="_2") {
			$this->special_ability = "Vedice";
			$skin = "";
		}
		
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text);
		$this->flunker_code = $id_type_weapon.$nb_hand;
		$this->id_weapon = $id_type_weapon.$nb_hand;
		
		$this->sap = $sap;
		$this->sl = $sl;
		$this->csl = $csl;
		$this->w = $w;
		$this->hr = $hr;
	}
	
	/**
	 * Get the Flunker bar code of the Item. The code contains almost all information for filtring the item.
	 * The bar code : BC_{id_weapon}_{id_peuple}_{energy}_
	 */
	public function barCode() {
		return "BC_".$this->id_weapon."_".$this->id_peuple."_".$this->e."_";
	}

	/**
	 * Get some word you do not find in the title and that can help the texte search.
	 * @return &lt;<b>string</b>&gt;a set of word, not a text
	 */
	protected function _description() {
		return parent::_description()." ".__($this->special_ability);
	}

	/**
	 *  Give the HTML code for displaying the item icon.
	 * @see Use in _getFormContent, getIconLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getIcon() {
		return flunker_item_icon_image($this->icon_name,null,$this->q,$this->s,$this->sap,$this->getTitle());
	}
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->hp."/".(int)$this->dur."</li>
						<li class=\"attribut tright\">".__("Sap load").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->csl."/".(int)$this->sl."</li>
						
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>
						
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut left\">".__("Weight").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->w."Kg</li>
						<li class=\"attribut left\">".__("Hit Rate").__(":")."</li>
						<li class=\"attribut right\" style=\"\">".(int)$this->hr."</li>";

		$res .= $this->_getEndFormContent();
						
		return $res;
	}
};

class amplifier extends weapon
{
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr)
	{
		# specific teatelent
		if (($id_peuple == "okam" || $id_peuple == "okar") && $skin=="_1") {
			$this->special_ability = "Maga";
			$skin = "";
		}
		else if (($id_peuple == "okam" || $id_peuple == "okar") && $skin=="_2") {
			$this->special_ability = "Cheng";
			$skin = "";
		}
		
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr);
	}
};

class meleeWeapon extends weapon
{
	public $dm;	///< dodge modifier 
	public $pm;	///< parry modifier 
	public $adm;	///< adversary dodge modifier
	public $apm;	///< adversary parry modifier
	
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr,$dm,$pm,$adm,$apm)
	{
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr);
		
		$this->dm=$dm;
		$this->pm=$pm;
		$this->adm=$adm;
		$this->apm=$apm;
	}
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
	
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".$this->hp."/".$this->dur."</li>
						<li class=\"attribut tright\">".__("Sap load").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->csl."/".(int)$this->sl."</li>
						
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>
						
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut left\">".__("Weight").__(":")."</li>
						<li class=\"attribut right\"> ".$this->w."Kg</li>
						<li class=\"attribut left\">".__("Hit Rate").__(":")."</li>
						<li class=\"attribut right\" style=\"\">".$this->hr."</li>
						
						<li class=\"attribut left\">".__("Dodge mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->dm."</li>
						<li class=\"attribut left\">".__("Parry mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->pm."</li>
						<li class=\"attribut left\">".__("Adv. Dodge mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->adm."</li>
						<li class=\"attribut left\">".__("Adv. Parry mod.").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->apm."</li>";

		$res .= $this->_getEndFormContent();
						
		return $res;
	}
	
	/**
	 * Tool method which give the max hit rate for each no boosted meleeWeapon.
	 */
	public function _maxHr() {
		if ( $this->id_weapon == "pd1") return 60;
		if ( $this->id_weapon == "bs1") return 39;
		if ( $this->id_weapon == "bm1") return 39;
		if ( $this->id_weapon == "ss1") return 39;
		if ( $this->id_weapon == "sa1") return 39;
		if ( $this->id_weapon == "pp2") return 33;
		if ( $this->id_weapon == "bm2") return 27;
		if ( $this->id_weapon == "ss2") return 30;
		if ( $this->id_weapon == "sa2") return 27;
	}
	
	/**
	 * Try to determinate if the weapon was boosted by a rubbarn tool.
	 */
	public function isRubbarnBoost() {

		if ($this->_maxHr() < (int)$this->hr) {
			return true;
		}
		return false;
	}
};

class rangeWeapon extends weapon
{
	public $r;	///< &lt;<b>int</b>&gt; range
	
	public function __construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr,$r)
	{
		parent::__construct($guild,$slot,$icon_name,$q,$id_peuple,$skin,$hp,$dur,$hpb,$sab,$stb,$fob,$e,$text,$id_type_weapon,$nb_hand,$sap,$sl,$csl,$w,$hr);
		
		$this->r=$r;
	}
	
	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
	
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Hit Points").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->hp."/".(int)$this->dur."</li>
						<li class=\"attribut tright\">".__("Sap load").__(":")."</li>
						<li class=\"attribut\"> ".(int)$this->csl."/".(int)$this->sl."</li>
						
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_hpb.png' title='".__("hpb")."' alt='hpb'/>".(int)$this->hpb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_sab.png' title='".__("sab")."' alt='sab'/>".(int)$this->sab."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_stb.png' title='".__("stb")."' alt='stb'/>".(int)$this->stb."</li>
						<li class=\"attribut quart\"><img class=\"buffer\" src='./img/form_fob.png' title='".__("fob")."' alt='fob'/>".(int)$this->fob."</li>
						
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut tright\">".__("Weight").__(":")."</li>
						<li class=\"attribut \"> ".(int)$this->w."Kg</li>
						<li class=\"attribut tright\">".__("Hit Rate").__(":")."</li>
						<li class=\"attribut \" style=\"\">".(int)$this->hr."</li>
						
						<li class=\"attribut tright\">".__("Range").__(":")."</li>
						<li class=\"attribut \"> ".(int)$this->r."</li>";

		$res .= $this->_getEndFormContent();
		
		return $res;
	}
};

class material extends item
{
	public $id_place;	///< &lt;<b>string</b>&gt;  Flunker identifiant of where the material come from
	
	public $id_origin;	///< &lt;<b>string</b>&gt;  Flunker identifiant how the material was founded (=by loot, =by haverst)
	public $id_type;	///< &lt;<b>string</b>&gt;  Flunker identifiant(ta=amber,twn=Wood Node,tp=Pelvis,tfa=Fang,tle=Leather,tc=Carapace,tto=Tooth,tba=Bark,two=Wood,tfi=Fiber,twi=Wings,tsp=Spine,ty=Eye,tsh=Shell,tma=Mandible,tsk=Skin,tta=Tail,tro=Rostrum,tho=Hoof,tse=Seed,toi=Oil,tli=Ligament,tmo=Moss,twh=Whiskers,tn=Nail,tbo=Bone,tre=Resin,tsc=Secretion,tsa=Sap,ttr=Trunk,tbe=Beak,thr=Horn,tsg=Sting,tcl=Claw,tbu=Bud,tmu=Mushroom,tot=other)
	public $id_utility;	///< &lt;<b>string</b>&gt;  Flunker identifiant(uot=other)
	public $c;		///< &lt;<b>int</b>&gt;  color

	public $e;		//< energy of the item (b=basic f=fine c=choice e=excelent s=supreme)
	
	public $total_stack;

	public function __construct($guild,$slot,$icon_name,$q,$s,$id_place,$id_origin,$c,$id_name,$e)
	{
		$type = array(
			'0015' => 'ta', '0050' => 'ta', '0102' => 'ta', '0117' => 'ta', '0124' => 'ta', '0155' => 'ta',
			'0629' => 'twn', '0100' => 'twn', '0652' => 'twn', '0662' => 'twn', '0679' => 'twn',
			'0500' => 'tp', '0504' => 'tp', '0507' => 'tp', '0510' => 'tp', '0515' => 'tp', '0539' => 'tp', '0546' => 'tp', '0555' => 'tp', '0612' => 'tp', '0616' => 'tp', '0620' => 'tp', '0638' => 'tp', '0645' => 'tp', '0650' => 'tp', '0656' => 'tp', '0665' => 'tp', '0676' => 'tp', '0683' => 'tp', '0691' => 'tp',
			'0348' => 'tfa', '0347' => 'tfa', '0385' => 'tfa', '0626' => 'tfa', '0632' => 'tfa', '0346' => 'tfa', '0135' => 'tfa', '0668' => 'tfa', '0140' => 'tfa', '0465' => 'tfa', '0349' => 'tfa', '0356' => 'tfa',
			'0376' => 'tle', '0044' => 'tle', '0372' => 'tle', '0371' => 'tle', '0574' => 'tle', '0579' => 'tle', '0369' => 'tle', '0107' => 'tle', '0137' => 'tle', '0670' => 'tle', '0141' => 'tle', '0463' => 'tle', '0145' => 'tle', '0687' => 'tle', '0694' => 'tle',
			'0336' => 'tc', '0470' => 'tc', '0048' => 'tc', '0368' => 'tc', '0068' => 'tc', '0485' => 'tc', '0066' => 'tc', '0479' => 'tc', '0072' => 'tc', '0069' => 'tc', '0073' => 'tc', '0602' => 'tc', '0490' => 'tc', '0607' => 'tc', '0634' => 'tc',
			'0501' => 'tto', '0505' => 'tto', '0508' => 'tto', '0511' => 'tto', '0516' => 'tto', '0520' => 'tto', '0540' => 'tto', '0383' => 'tto', '0617' => 'tto', '0350' => 'tto', '0639' => 'tto', '0646' => 'tto', '0651' => 'tto', '0657' => 'tto', '0345' => 'tto', '0692' => 'tto',
			'0497' => 'tba', '0014' => 'tba', '0623' => 'tba', '0630' => 'tba', '0101' => 'tba',
			'0469' => 'two', '0001' => 'two', '0040' => 'two', '0064' => 'two', '0093' => 'two', '0128' => 'two',
			'0006' => 'tfi', '0021' => 'tfi', '0037' => 'tfi', '0118' => 'tfi',
			'0564' => 'twi', '0570' => 'twi', '0335' => 'twi', '0596' => 'twi', '0609' => 'twi', '0366' => 'twi',
			'0009' => 'tsp', '0082' => 'tsp', '0677' => 'tsp',
			'0498' => 'ty', '0502' => 'ty', '0506' => 'ty', '0509' => 'ty', '0512' => 'ty', '0517' => 'ty', '0536' => 'ty', '0543' => 'ty', '0552' => 'ty', '0611' => 'ty', '0613' => 'ty', '0618' => 'ty', '0621' => 'ty', '0635' => 'ty', '0642' => 'ty', '0647' => 'ty', '0653' => 'ty', '0663' => 'ty', '0675' => 'ty', '0688' => 'ty',
			'0123' => 'tsh', '0125' => 'tsh', '0053' => 'tsh', '0031' => 'tsh', '0016' => 'tsh',
			'0521' => 'tma', '0527' => 'tma', '0548' => 'tma', '0556' => 'tma', '0580' => 'tma', '0487' => 'tma', '0585' => 'tma', '0481' => 'tma', '0590' => 'tma', '0593' => 'tma', '0598' => 'tma', '0600' => 'tma', '0492' => 'tma', '0606' => 'tma',
			'0503' => 'tsk', '0367' => 'tsk', '0019' => 'tsk', '0364' => 'tsk', '0518' => 'tsk', '0380' => 'tsk', '0544' => 'tsk', '0553' => 'tsk', '0081' => 'tsk', '0471' => 'tsk', '0086' => 'tsk', '0365' => 'tsk', '0636' => 'tsk', '0643' => 'tsk', '0648' => 'tsk', '0654' => 'tsk', '0133' => 'tsk', '0363' => 'tsk', '0682' => 'tsk', '0152' => 'tsk',
			'0524' => 'tta', '0529' => 'tta', '0551' => 'tta', '0559' => 'tta', '0583' => 'tta', '0488' => 'tta', '0587' => 'tta', '0589' => 'tta', '0592' => 'tta', '0595' => 'tta', '0603' => 'tta', '0605' => 'tta', '0078' => 'tta',
			'0074' => 'tro',
			'0025' => 'tho', '0378' => 'tho',
			'0023' => 'tse', '0113' => 'tse', '0115' => 'tse', '0659' => 'tse',
			'0049' => 'toi', '0565' => 'toi', '0610' => 'toi', '0103' => 'toi',
			'0531' => 'tli', '0542' => 'tli', '0562' => 'tli', '0568' => 'tli', '0573' => 'tli', '0578' => 'tli', '0627' => 'tli', '0633' => 'tli', '0641' => 'tli', '0666' => 'tli', '0669' => 'tli', '0672' => 'tli', '0673' => 'tli', '0681' => 'tli', '0686' => 'tli', '0693' => 'tli',
			'0525' => 'tmo', '0575' => 'tmo', '0640' => 'tmo', '0658' => 'tmo', '0660' => 'tmo', '0661' => 'tmo', '0147' => 'tmo',
			'0083' => 'twh',
			'0499' => 'tn', '0374' => 'tn', '0020' => 'tn', '0514' => 'tn', '0519' => 'tn', '0538' => 'tn', '0545' => 'tn', '0554' => 'tn', '0615' => 'tn', '0619' => 'tn', '0359' => 'tn', '0637' => 'tn', '0644' => 'tn', '0649' => 'tn', '0655' => 'tn', '0664' => 'tn', '0149' => 'tn', '0690' => 'tn',
			'0341' => 'tbo', '0339' => 'tbo', '0561' => 'tbo', '0567' => 'tbo', '0572' => 'tbo', '0576' => 'tbo', '0625' => 'tbo', '0384' => 'tbo', '0343' => 'tbo', '0667' => 'tbo', '0338' => 'tbo', '0464' => 'tbo', '0462' => 'tbo', '0684' => 'tbo', '0153' => 'tbo',
			'0046' => 'tre', '0534' => 'tre', '0541' => 'tre', '0624' => 'tre',
			'0522' => 'tsc', '0528' => 'tsc', '0549' => 'tsc', '0557' => 'tsc', '0581' => 'tsc', '0584' => 'tsc', '0586' => 'tsc', '0588' => 'tsc', '0591' => 'tsc', '0599' => 'tsc', '0601' => 'tsc', '0604' => 'tsc',
			'0109' => 'tsa', '0533' => 'tsa', '0535' => 'tsa', '0119' => 'tsa', '0142' => 'tsa',
			'0547' => 'ttr', '0087' => 'ttr', '0678' => 'ttr',
			'0560' => 'tbe', '0566' => 'tbe', '0571' => 'tbe', '0680' => 'tbe',
			'0018' => 'thr', '0136' => 'thr',
			'0523' => 'tsg', '0550' => 'tsg', '0558' => 'tsg', '0582' => 'tsg', '0067' => 'tsg', '0480' => 'tsg', '0496' => 'tsg', '0594' => 'tsg', '0076' => 'tsg', '0491' => 'tsg', '0608' => 'tsg',
			'0526' => 'tcl', '0530' => 'tcl', '0043' => 'tcl', '0577' => 'tcl', '0387' => 'tcl', '0597' => 'tcl', '0468' => 'tcl', '0467' => 'tcl', '0106' => 'tcl', '0134' => 'tcl', '0386' => 'tcl', '0671' => 'tcl', '0390' => 'tcl', '0685' => 'tcl', '0154' => 'tcl', '0695' => 'tcl',
			'0472' => 'tbu', '0473' => 'tbu', '0474' => 'tbu', '0475' => 'tbu', '0476' => 'tbu', '0477' => 'tbu',
			'0148' => 'tmu'
			
		);
		$utility = array(
			'ta'  => 'u01', // magic
			'twn' => 'u02', // counterweight
			'tp'  => 'u02',
			'tfa' => 'u03', // explosive 
			'tle' => 'u04', // barrel
			'tto' => 'u05', // jacket
			'tba' => 'u06', // shaft
			'two' => 'u04', //barrel
			'tc'  => 'u04', //barrel
			'tfi' => 'u07', // grip
			'twi' => 'u03', // explosive
			'tsp' => 'u08', // trigger
			'ty'  => 'u01', // magic
			'tsh' => 'u10', // blade
			'tma' => 'u06', // shaft
			'tsk' => 'u07', // grip
			'tta' => 'u09', // piring pin
			'tro' => 'u09', // piring pin
			'tho' => 'u02', // counterweight
			'tse' => 'u08', // trigger
			'toi' => 'u03', // explosive
			'tli' => 'u09', // piring pin
			'tmo' => 'u05', // jacket
			'twh' => 'u05', // jacket
			'tn'  => 'u08', // trigger
			'tbo' => 'u06', // shaft
			'tre' => 'u05', // jacket
			'tsc' => 'u03', // explosive
			'tsa' => 'u09', // piring pin
			'ttr' => 'u05', // jacket 
			'tbe' => 'u10', // blade
			'thr' => 'u06', // shaft
			'tsg' => 'u10', // blade
			'tcl' => 'u10', // blade
			'tbu' => 'u01', // magic
			'tmu' => 'u01'  // magic
		);
		
		parent::__construct($guild,$slot,$icon_name,$q,$s);
		$this->id_place = "p".$id_place;
		$this->id_origin = $id_origin;
		if( $c != null )	$this->c = "c".$c;
		$this->e = "e".$e;
		
		if( array_key_exists($id_name,$type)) {
			$this->id_type = $type[$id_name];
			$this->id_utility = $utility[$this->id_type];
		}
		else {
			$this->id_type = "tot";
			$this->id_utility = "uot";
		}
		
	}
	
	/**
	 * Get the Flunker bar code of the Item. The code contains almost all information for filtring the item.
	 * The bar code : BC_{the Flunker/Ryzom code}_{id_place}_{id_origin}_{id_utility}_{energy}_
	 */
	public function barCode() {
		return "BC_".$this->flunker_code."_".$this->id_place."_".$this->id_origin."_".$this->id_utility."_".$this->e."_";
	}

	/**
	 *  Give the HTML code for displaying the item icon.
	 * @see Use in _getFormContent, getIconLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getIcon() {
		return flunker_item_icon_image($this->icon_name,($this->c!=nul)?$this->c[1]:"",$this->q,$this->s,-1,$this->getTitle());
	}

	/**
	 *  Give the HTML code for displaying the content of the item form.
	 * @see Use in getFormLi and getFormAndIconLi.
	 * @return &lt;<b>string</b>&gt;  the HTML code
	 */
	protected function _getFormContent() {
		
		$res = "
						<li class=\"attribut icon\">".$this->_getIcon()."</li>
						<li class=\"attribut iconright\">".$this->getTitle()."</li>
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut left\">".__("Total Amount").__(":")."</li>
						<li class=\"attribut right\"> ".(int)$this->total_stack."</li>
						
						<li class=\"attribut decohr\"><hr/></li>
						
						<li class=\"attribut all\">".$this->guild->name."</li>";
						
		return $res;
	}
};
?>