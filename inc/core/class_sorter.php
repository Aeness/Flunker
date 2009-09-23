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
 * Uses only for stocking which sorter can be displayed in one specific room.
 * and for displaying this sorter in the html page.
 */
abstract class formSorter
{
	public $id;
	public $en_name;
	public $order;
	public $typeOfContent;
	public $values;

	public function __construct($id,$en_name,$typeOfContent,$order,$values)
	{
		$this->id= $id;
		$this->en_name= $en_name;
		$this->order = $order;
		$this->typeOfContent = $typeOfContent;
		$this->values = $values;
	}

	/**
	 * @return the html code which display the sorter used directly
	 */
	function getHtmlTag() {
		return "
		<li id=\"".$this->id."\"><span style=\"padding-left: 10px;\">&nbsp;".__($this->en_name)."</span></li>";
	}
	

	/**
	 * @return the html code which display the sorter you can add 
	 */
	function getWaitingHtmlTag() {
		return "
		<li id=\"waiting_".$this->id."\"><span style=\"padding-left: 10px;\">&nbsp;".__($this->en_name)."</span></li>";
	}
	
	/**
	 * Inserse the order.
	 */
	function inverseOrder() {
		$inverse = array( SORT_DESC => SORT_ASC , SORT_ASC => SORT_DESC );
		$this->order = $inverse[$this->order];
	}
}

/**
 * Uses for alphanumeric sorter.
 * @see formSorter
 */
class formSorterStr extends formSorter
{

	public function __construct($id,$en_name,$order,$values)
	{
		parent::__construct($id,$en_name,SORT_STRING,$order,$values);
	}
}

/**
 * Uses for numeric sorter, uses an arrow for choosing the order (asc or desc).
 * @see formSorter
 */
class formSorterInt extends formSorter
{
	public function __construct($id,$en_name,$default_order,$values)
	{
		parent::__construct($id,$en_name,SORT_NUMERIC,$default_order,$values);
	}
	
	/**
	 * @return the html code which display the sorter used directly
	 */
	function getHtmlTag() {
		$orientation = "up";
		$alt = "^";
		$title = "Ascendant sorter";
		if( $this->order==SORT_DESC ) {
			$orientation = "down";
			$alt = "v";
			$title = "Descendant sorter";
		}
		return "
		<li id=\"".$this->id."\"><span><img alt=\"{$alt}\" title=\"".__($title)."\" src=\"./img/{$orientation}_green.png\"/>&nbsp;".__($this->en_name)."</span></li>";
	}
	
	/**
	 * @return the html code which display the sorter you can add 
	 */
	function getWaitingHtmlTag() {
		$orientation = "up";
		$alt = "^";
		$title = "Ascendant sorter";
		if( $this->order==SORT_DESC ) {
			$orientation = "down";
			$alt = "v";
			$title = "Descendant sorter";
		}
		return "
		<li id=\"waiting_".$this->id."\"><span><img alt=\"{$alt}\" title=\"".__($title)."\" src=\"./img/{$orientation}_green.png\"/>&nbsp;".__($this->en_name)."</span></li>";
	}
}
?>