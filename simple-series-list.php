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
  $custom_post_types = get_post_types(['public' => true, '_builtin' => false]);
  if(!$post_type || array_search($post_type, $custom_post_types) === false) {
    return null;
  }

  include_once(dirname(__file__) . "/lib/series-list.php");
  return $block(SeriesList::instance($post_type));
}

/*
 * [series_list actions="true"] すると、メニューの上下に「次へ」「前へ」の操作アイコンが出る
 */
function simple_series_list_shorcode_params($params) {
  return shortcode_atts([
    'post_type' => get_post_type(),
    'prev_icon' => '▲',
    'next_icon' => '▼',
    'actions' => false
  ], $params);
}

function simple_series_list($params = array()) {
  $params = simple_series_list_shorcode_params($params);
  return simple_series_instance($params['post_type'], function($simpleSeries) use (&$params){
    return $simpleSeries->createHtml($params);
  });
}

add_shortcode('series_list', 'simple_series_list');

function simple_series_neighbor_menu($params = array()) {
  $params = simple_series_list_shorcode_params($params);
  return simple_series_instance($post_type, function($simpleSeries) use (&$params){
    return $simpleSeries->createNeighborMenuHtml($params);
  });
}

add_shortcode('simple_series_neighbor_menu', 'simple_series_neighbor_menu');
