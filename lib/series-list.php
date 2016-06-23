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
      $current_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") .
                     $_SERVER["HTTP_HOST"] .
                     $_SERVER["REQUEST_URI"];

      $ret_list = "<ol class='series_list'>" . "\n";

      foreach ($ids as $id) {
        if ($current_url === get_permalink( $id )) {
          $ret_list .= "<li>" . get_post( $id )->post_title . "</li>" . "\n";
        } else {
          $ret_list .= "<li><a href='" . get_permalink( $id ) . "'>" . get_post( $id )->post_title . "</a></li>" . "\n";
        }
      }

      $ret_list .= "</ol>\n";
      return $ret_list;
    }

  }
}
