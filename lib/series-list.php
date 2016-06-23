<?php
if(!class_exists('Series_List')){
  class Series_List {

    public function CheckSortBy($sort_by){
      $order = ['ASC', 'DESC'];
      $is_sort_by = in_array(strtoupper($sort_by), $order);
      return $is_sort_by ? $order[array_search(strtoupper($sort_by), $order)] : 'ASC';
    }

    public function CheckOrderBy($order_by){
      $columns = [];
      $is_order_by = in_array(strtolower($order_by), array_map('strtolower', $columns));
      return $is_order_by ? $columns[array_search(strtolower($order_by), array_map('strtolower', $columns))] : 'id';
    }

    public function SearchIds($post_type, $sort_by){
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

    public function RetList($ids) {
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

  }
}
