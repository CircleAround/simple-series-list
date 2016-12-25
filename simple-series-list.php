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

add_filter('widget_text', 'do_shortcode' );

function simple_series_instance($post_type, $block) {
  $exclude_post_types = ['post', 'page', 'attachment', 'revision', 'nav_menu_item'];
  $post_type = get_post_type();
  if(!$post_type || array_search($post_type, $exclude_post_types) !== FALSE) {
    return null;
  }

  include_once(dirname(__file__) . "/lib/series-list.php");
  return $block(SeriesList::instance($post_type));
}

function simple_series_list($params = array()) {
  extract(shortcode_atts(array(
    'post_type' => 'post'
  ), $params));

  return simple_series_instance($post_type, function($simpleSeries){
    return $simpleSeries->createHtml();
  });
}

add_shortcode('series_list', 'simple_series_list');

function simple_series_neighbor_menu($params = array()) {
  extract(shortcode_atts(array(
    'post_type' => 'post'
  ), $params));

  return simple_series_instance($post_type, function($simpleSeries){
    return $simpleSeries->createNeighborMenuHtml();
  });
}

add_shortcode('simple_series_neighbor_menu', 'simple_series_neighbor_menu');
