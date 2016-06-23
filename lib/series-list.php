<?php
if(!class_exists('Series_List')){
  class SeriesList {

    public function searchIds($post_type){
      global $wpdb;
      $ids = $wpdb->get_col($wpdb->prepare(
          "
          SELECT id
          FROM $wpdb->posts
          WHERE post_type = %s
                AND post_status = 'publish'
          ORDER BY menu_order
          ",
          $post_type
      ));
      return $ids;
    }

    public function retList($ids) {
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
