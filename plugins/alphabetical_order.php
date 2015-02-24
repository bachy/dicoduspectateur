<?php

/**
 * Call page in alphabetical order
 * Alphabetical Letter 
 *
 * @author Sarah Garcin
 */
class Alphabetical_Order { 
  private $pages;

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
  	sort($pages);
    $pages = $this->removeElementWithValue($pages, 'title', 'Welcome');
    $pages = $this->removeElementWithValue($pages, 'title', 'Définitions');
    $pages = $this->removeElementWithValue($pages, 'title', 'À propos');
    $this->pages = array();
    $this->pages = $pages;
  }// end function 

  public function before_render(&$twig_vars, &$twig){
    $twig_vars['alphabetical'] = $this->build_list($this->pages);
  }

  private function removeElementWithValue($array, $key, $value){
    foreach($array as $subKey => $subArray){
      if($subArray[$key] == $value){
        unset($array[$subKey]);
      }
    }
    return $array;
  }

  private function build_list($pages){
    $cur_let = null;
    $html = '<ul>';
    foreach ($pages as $page) {
      $titre = $page['title'];
      $excerpt = $page['excerpt'];
      $first_let = (is_numeric(strtoupper(substr($titre,0,1))) ? '#' : strtoupper(substr($titre,0,1)));
      if ($cur_let !== $first_let){
        $cur_let = $first_let;
        $letter = '<li class="letter large-2 columns">' . $cur_let . '</li>';
        $html .= $letter;
      }
      $html .= '<li class="definition large-2 columns"><a href="' . $page['url']. '" title="' . $titre .'"><h3>' . $titre . '</h3></a><p>' . $excerpt . '</p></li>';
    }
    $html .= '</ul>';
    return $html;
  }

} // end file

?>