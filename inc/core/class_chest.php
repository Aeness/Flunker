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
 * <li>Stock information about chest.</li>
 * <li>Display information about chest.</li>
 * </ul>
 */
class apartment
{
	public $id;		///< &lt;<b>string</b>&gt;  Ryzom id (cid, gid, ...)
	public $name;		///< &lt;<b>string</b>&gt;  Name
	public $money;		///< &lt;<b>int</b>&gt;  Nb dapper
	public $nbItems;	///< &lt;<b>int</b>&gt;  Nb item
	
	public function __construct($gid,$name,$id_icon)
	{
		$this->id = (string)$gid;
		$this->name = $name;
		$this->id_icon = $id_icon;
	}
	
	/**
	 * The picutures size must have the 's' Ryzom size (32x32).
	 * @return the url of the Icon
	 */
	public function getUrl() {
		return 'img/icon_apartment.png';
	}
	
	public function resumeName() {
		if( strlen($this->name) > 19 ){
			return substr($this->name,0,16)."...";
		}
		return $this->name;
	}
	
	/**
	* Give the  Guild Icon.
	 * The result is better with a icon smaller than the 's' Ryzom size.
	 * @param px_size 	&lt;<b>id</b>&gt;	Size (height/width) in pixel
	 * @return the tag img of the Icon
	 */
	public function getSmallIcon($px_size) {
		return '<img src="'.$this->getUrl().'" style="height:'.$px_size.'px; width: '.$px_size.'px;" alt="'.__('Icon Appart.').'"/>';
	}
	
	public function getHtml() {
		return "
				<div class=\"chest_info\">
					<div style=\"width: 34px; float: left;vertical-align: middle; padding: 2px 0 0 2px;\">
						".$this->getSmallIcon(32)."
					</div>
					<div style=\"margin: 6px 0 0 5px;height: 34px;width: 150px; float: left;\">
						<span style=\"vertical-align: middle;\" title=\"".$this->name."\">".$this->resumeName()."</span>
					</div>
					<div style=\"margin: 5px 0 0 5px;height: 34px;float: left; font-size: 11px;\">
						<span style=\"vertical-align: middle;\">".__("Slot").__(":").round(($this->nbItems * 100) / 999,2)."%<br />".$this->money." dappers</span>
					</div>
				</div>";
	}
}

class guild extends apartment
{
	public $id_icon;	///< &lt;<b>string</b>&gt;  Ryzom id of the icon
	
	public function __construct($gid,$name,$id_icon)
	{
		$this->id = (string)$gid;
		$this->name = $name;
		$this->id_icon = $id_icon;
	}
	
	/**
	 * The picutures size is the 's' Ryzom size.
	 * @return the url of the Icon
	 */
	public function getUrl() {
		return flunker_guild_icon_url($this->id_icon, 's');
	}
	
	/**
	 * The picutures sizes are : height:23px; width: 23px; 
	 * @return the tag img of the Icon
	 */
	public function getSmallIcon($px_size) {
		return flunker_guild_icon_image($this->id_icon,$px_size);
	}
}
?>