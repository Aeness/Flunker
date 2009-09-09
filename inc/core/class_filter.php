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
 * Uses only for stocking which filter can be displayed in one specific room.
 * and for displaying this filter in the html page.
 */
abstract class formFilter
{
	public $id;
	public $en_name;
	public $type;

	protected function __construct($id,$en_name,$type)
	{
		$this->id= $id;
		$this->en_name= $en_name;
		$this->type = $type;
	}
	
	/**
	 * @param class &lt;<b>string</b>&gt;  a css class you want to apply to the root tag
	 * @return the html code which display the filter
	 */
	abstract function getHtmlTag($class="");
}

/**
 * @see formFilter
 */
class listFormFilter extends formFilter
{
	public $list_code;
	public $checked;

	public function __construct($id,$en_name,$list_code,$checked=false)
	{
		parent::__construct($id,$en_name,'list');
		
		$this->list_code = $list_code;
		$this->checked = $checked;
		
	}
	
	function getHtmlTag($class="") {
	
		$html = "
					<ul class=\"search_boxes {$class}\" id=\"list_".$this->id."\" style=\"width: ".((count($this->list_code)*51)+10+14)."px;\">
						<li class=\"select_all_none\">
							<img style=\"width: 10px;\" src=\"img/all.png\" alt=\"".__("Select all buttons")."\" title=\"".__("Select all buttons")."\"/>
							<img style=\"width: 10px;\" src=\"img/none.png\" alt=\"".__("Deselect all buttons")."\" title=\"".__("Deselect all buttons")."\"/>
						</li>";
		foreach ($this->list_code as $code) {
			$html .=  checkbox("img",$this->id,$this->checked,$code,__($code),__($code),$class="");
		}
		$html .= "
					</ul>";
					
		return $html;
	
	}
}

/**
 * @see formFilter
 */
class choiceFormFilter extends formFilter
{
	public $list_code;

	public function __construct($id,$en_name,$list_code)
	{
		parent::__construct($id,$en_name,'choice');
		
		$this->list_code = $list_code;
		
	}
	
	function getHtmlTag($class="") {
		$html = "
					<ul class=\"search_boxes2 {$class}\" id=\"choice_".$this->id."\" style=\"width: ".((count($this->list_code)*51)+10+14)."px;\">
						<li style=\"width: 10px;\" class=\"select_all_none\"></li>";
		foreach ($this->list_code as $code) {
		
			$html .= "
							<li>
								".radio("img",$this->id,$code,__($this->en_name).__(":")." ".__($code),__($this->en_name).__(":")." ".__($code))."
							</li>";
		}
		$html .= "
					</ul>";
					
		return $html;
	
	}
}

/**
 * @see formFilter
 */
class intervalFormFilter extends formFilter
{
	public $min;
	public $max;

	public function __construct($id,$en_name,$min,$max)
	{
		parent::__construct($id,$en_name,'interval');
		
		$this->min = $min;
		$this->max = $max;
		
	}
	
	function getHtmlTag($class="") {
		
		$html = "
						<div id=\"".$this->id."\" class=\"{$class}\">
							<span style=\"vertical-align: middle;\">".__($this->en_name)."
							&nbsp;<input style=\"width: 2em; margin: 0px;\" type=\"text\" size=\"3\" maxlength=\"3\" name=\"min_{$this->id}\" id=\"min_{$this->id}\" value=\"{$this->min}\" />
							&nbsp;&nbsp;&nbsp;&nbsp;<input style=\"width: 2em; margin: 0px\" type=\"text\" size=\"3\" maxlength=\"3\" name=\"max_{$this->id}\" id=\"max_{$this->id}\" value=\"{$this->max}\" /></span>
						</div>";
					
		return $html;
	}
}

/**
 * @see formFilter
 */
class ghFormFilter extends formFilter
{
	public $list_guild;

	public function __construct($list_guild)
	{
		parent::__construct('guild_hall','Guild Hall','list');
		
		$this->list_guild = $list_guild;
		
	}
	
	function getHtmlTag($class = "") {
		$html = "
					<ul class=\"search_boxes3 {$class}\" id=\"choice_".$this->id."\" style=\"width: ".((count($this->list_guild)*51)+10+14)."px;\">
						<li style=\"width: 10px;\" class=\"select_all_none\"></li>";
		
		foreach ($this->list_guild as $guild) {
			$html .= "
							".ghButton("img",$this->id,$guild->id,$guild->getUrl(),__('Icon Guild'),$guild->name);
			$i++;
		}
		$html .= "
					</ul>";
					
		return $html;
	
	}
}

/**
 * @see formFilter
 */
class textFormFilter extends formFilter
{

	public function __construct()
	{
		parent::__construct('text_search','Search','text');
	}
	
	function getHtmlTag($class="") {
		
		$html = "
						<div class=\"{$class}\">
							<span style=\"vertical-align: middle;\">".__($this->en_name)."
							&nbsp;<input style=\"width: 20em; margin: 0px;\" type=\"text\" size=\"20\" maxlength=\"50\" id=\"{$this->id}\" value=\"\" />
							</span>
						</div>";
					
		return $html;
	}
}

?>