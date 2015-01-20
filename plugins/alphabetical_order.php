<?php

/**
 * Call page in alphabetical order
 *
 * @author Sarah Garcin
 */
class Alphabetical_Order {

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
  
    sort($pages);
    // foreach ($pages as $page => $val) {
    //   echo "page = " . $page . "\n";
    // }
  }

}

// End of file
