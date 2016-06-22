<?php
/*
Plugin Name: Simple Series List
Plugin URI:
Description: 連載記事をリスト化して表示してくれます。
Version: 1.0.0
Author: CircleAround
Author URI:
License: GPL2
*/
/*  Copyright 2016 CircleAround (email : info@circlearound.co.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function simple_series_list($params = array()) {
  extract(shortcode_atts(array(
    'post_type' => 'post',
    'order_by' => 'menu_order',
    'sort_by' => 'ASC'
  ), $params));
  ob_start();
  include(dirname(__file__) . "/lib/series-list.php");
  return ob_get_clean();
}


add_shortcode('simple_series_list', 'simple_series_list');
