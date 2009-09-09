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
 * Give the  Guild Icon.
 * The result is better with a icon smaller than the 's' Ryzom size.
 * @param icon		&lt;<b>string</b>&gt;	Ryzom Id of the icon
 * @param px_size 	&lt;<b>id</b>&gt;	Size (height/width) in pixel
 * @return the tag img of the Guild Icon
 */
function flunker_guild_icon_image($icon, $px_size) {
	return '<img src="'.flunker_guild_icon_url($icon, 's').'" style="height:{$px_size}px; width: {$px_size}px;" alt="'.__('Icon Guild').'"/>';
}

/**
 * Give the  Guild Icon URL ( validated by w3c).
 * @param icon	&lt;<b>string</b>&gt;	Ryzom Id of the icon
 * @param size 	&lt;<b>string</b>&gt;	Ryzom Size
 * @return the url of the Guild Icon
 */
function flunker_guild_icon_url($icon, $size) {
	return ryzom_api_base_url()."guild_icon.php?".htmlentities("icon=$icon&size=$size");
}

?>