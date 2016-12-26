<?php
if(!class_exists('SeriesList')){
  class SeriesList {
    private static $instance;

    public static function instance($post_type) {
      if (!$instance) {
        $instance = new SeriesList($post_type);
      }
      return $instance;
    }

    function SeriesList($post_type){
      $this->post_type = $post_type;
    }

    public function createHtml($params = []) {
      $neighborMenu = $this->createNeighborMenuHtml($params);

      $ret_list = "<ol class='series_list'>\n";
      $post_id = $this->getPostId();
      $ids = $this->searchIds();
      foreach ($ids as $id) {
        if ($post_id == $id ) {
          $ret_list .= "<li>" . get_post( $id )->post_title . "</li>\n";
        } else {
          $ret_list .= "<li><a href='" . get_permalink( $id ) . "'>" . get_post( $id )->post_title . "</a></li>\n";
        }
      }
      $ret_list .= "</ol>\n";
      return $neighborMenu . $ret_list . $neighborMenu;
    }

    public function getNeighborIds() {
      $ids = $this->searchIds();
      $findout = false;
      $post_id = $this->getPostId();
      foreach ($ids as $id) {
        if ($findout) {
          $next_id = $id;
          break;
        }

        if ($post_id == $id ) {
          $findout = true;
        }

        if (!$findout) {
          $prev_id = $id;
        }
      }
      if (!$findout) {
        return ['prev_id' => false, 'next_id' => false];
      }

      return ['prev_id' => $prev_id, 'next_id' => $next_id];
    }

    public function createNeighborMenuHtml($params = []) {
      $params = $params + [
        'prev_icon' => '▲',
        'next_icon' => '▼',
        'actions' => 0
      ];

      if (!$params['actions']) {
        return '';
      }

      $neighbors = $this->getNeighborIds();
      $neighborMenu = '';
      $prev_id = $neighbors['prev_id'];
      $next_id = $neighbors['next_id'];
      $prev_icon = $params['prev_icon'];
      $next_icon = $params['next_icon'];

      if($prev_id || $next_id) {
        $neighborMenu .= '<div class="series_list_actions">';
        if($prev_id) {
          $link = get_permalink( $prev_id );
          $neighborMenu .= "<a href=\"$link\">$prev_icon</a> ";
        }
        if($next_id) {
          $link = get_permalink( $next_id );
          $neighborMenu .= "<a href=\"$link\">$next_icon</a>";
        }
        $neighborMenu .= '</div>';
      }
      return $neighborMenu;
    }

    private function searchIds(){
      if (!$this->ids) {
        global $wpdb;
        $this->ids = $wpdb->get_col($wpdb->prepare(
            "
            SELECT id
            FROM $wpdb->posts
            WHERE post_type = %s
                  AND post_status = 'publish'
            ORDER BY menu_order
            ",
            $this->post_type
        ));
      }
      return $this->ids;
    }

    private function getPostId() {
      global $wp_query;
      return $wp_query->post->ID;
    }
  }
}
