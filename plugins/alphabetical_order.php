<?php

/**
 * Call page in alphabetical order
 *
 * @author Sarah Garcin
 */
class Alphabetical_Order {

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
  	sort($pages);
  	$cur_let = null;
    $pages = $this->removeElementWithValue($pages, 'title', 'Welcome');
    $pages = $this->removeElementWithValue($pages, 'title', 'Définitions');
    $pages = $this->removeElementWithValue($pages, 'title', 'À propos');
    $this->build_list($pages);
  }// end function 

  public function before_render(&$twig_vars, &$twig){
    $twig_vars['alphabetical']['navigation'] = " ";
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
    foreach ($pages as $key => $page) {
      $titre = $page['title'];
      $excerpt = $page['excerpt'];
      $first_let = (is_numeric(strtoupper(substr($titre,0,1))) ? '#' : strtoupper(substr($titre,0,1)));
      if ($cur_let !== $first_let){
          $cur_let = $first_let;
          ?></ul></ul>
            <li class="letter large-2 columns"><?php echo $cur_let;?></li>
            <?php
      }
      ?>
      <li class="large-2 columns"><a href="<?php echo $page['url']?>" title= "<?php echo $title?>"><h3><?php echo $titre ?></h3></a><p><?php echo $excerpt?></p></li>
      <?php
    }
  }

} // end file

?>