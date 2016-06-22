<?php

function sslCheckSortBy($sort_by){
  $order = ['ASC', 'DESC'];
  $is_sort_by = in_array(strtoupper($sort_by), $order);
  return $is_sort_by ? $order[array_search(strtoupper($sort_by), $order)] : 'ASC';
}

function sslCheckOrderBy($order_by){
  $columns = [];
  $is_order_by = in_array(strtolower($order_by), array_map('strtolower', $columns));
  return $is_order_by ? $columns[array_search(strtolower($order_by), array_map('strtolower', $columns))] : 'id';
}

function sslSearchIds($post_type, $sort_by){
  global $wpdb;
  $ids = $wpdb->get_col($wpdb->prepare(
      "
      SELECT id
      FROM $wpdb->posts
      WHERE post_type = %s
            AND post_status = 'publish'
      ORDER BY menu_order $sort_by
      ",
      $post_type
  ));
  return $ids;
}

function sslRetList($ids) {
  $series_list = array();

  foreach ($ids as $id) {
    $series_list[get_permalink( $id )] = get_post( $id )->post_title;
  }

  $current_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") .
                 $_SERVER["HTTP_HOST"] .
                 $_SERVER["REQUEST_URI"];

  $ret_list = "<ol class='series_list'>" . "\n";

  foreach ($series_list as $key => $value) {
    if ($current_url === $key) {
      $ret_list .= "<li>" . $value . "</li>" . "\n";
    } else {
      $ret_list .= "<li><a href='" . $key . "'>" . $value . "</a></li>" . "\n";
    }
  }

  $ret_list .= "</ol>\n";
  return $ret_list;
}

$ids = sslSearchIds($post_type, sslCheckSortBy($sort_by));

echo sslRetList($ids);
