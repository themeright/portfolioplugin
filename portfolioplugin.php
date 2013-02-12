<?php
/** 
 * Plugin name: portfolio plugin
 * Plugin URI: http://wordpress.org/extend/plugins/crop-thumbnails/
 * Author: Pengyi Wang
 * Author URI: http://www.totalmedial.de
 * Version: 1.0
 * Description: create one page portfolio, easy way
 * 
 * License: GPL v3
 * Copyright 2012  Volkmar Kantor  (email : info@totalmedial.de)

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

register_activation_hook( __FILE__, 'portfolio_set_default_options');

function portfolio_set_default_options(){
        
        if (get_option('portfolio_options') === false) {

              $new_options['sidebar_display'] = true;
              $new_options['category'] = "work";
              $new_options['tag_filter'] = true;
              add_option('portfolio_options', $new_options);

        }
        

	}


include_once(dirname(__FILE__).'/functions/settings.php');
include_once(dirname(__FILE__).'/html/workpage.php');
?>