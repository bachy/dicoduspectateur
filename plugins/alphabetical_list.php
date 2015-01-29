<?php

/**
 * 
 *
 * @author Sarah Garcin
 * @license http://opensource.org/licenses/MIT
 */
class Alphabetical_List {

	private $settings = array();
	private $navigation = array();
	
	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
		sort($pages);
		foreach ($pages as $page)
		{
			if (!$this->at_exclude($page)){
				$_split = explode('/', substr($page['url'], strlen($this->settings['base_url'])+1));
			}
		}

	}

	public function config_loaded(&$settings){
		$this->settings = $settings;		
		// default excludes
		$this->settings['alphabetical_list']['exclude'] = array_merge_recursive(
			array('single' => array(), 'folder' => array()),
			isset($this->settings['alphabetical_list']['exclude']) ? $this->settings['alphabetical_list']['exclude'] : array()
		);
	}

	public function before_render(&$twig_vars, &$twig){
		$twig_vars['alphabetical_list']['navigation'] = $this->at_build_navigation($pages, true);
	}

	private function at_build_navigation(&$pages, $start = false){
		$cur_let = null;
	    foreach ($pages as $page) {
	    	$titre = $page['title'];
		    $first_let = (is_numeric(strtoupper(substr($titre,0,1))) ? '#' : strtoupper(substr($titre,0,1)));
		    if ($cur_let !== $first_let){
		        $cur_let = $first_let;
		        print($cur_let."\n");
		    }
		    echo $titre . "\n";
	    }
	}

	private function at_exclude($page){
		$exclude = $this->settings['alphabetical_list']['exclude'];
		$url = substr($page['url'], strlen($this->settings['base_url'])+1);
		$url = (substr($url, -1) == '/') ? $url : $url.'/';
		
		foreach ($exclude['single'] as $s)
		{	
			$s = (substr($s, -1*strlen('index')) == 'index') ? substr($s, 0, -1*strlen('index')) : $s;
			$s = (substr($s, -1) == '/') ? $s : $s.'/';
			
			if ($url == $s)
			{
				return true;
			}
		}
		
		foreach ($exclude['folder'] as $f)
		{
			$f = (substr($f, -1) == '/') ? $f : $f.'/';
			$is_index = ($f == '' || $f == '/') ? true : false;
			
			if (substr($url, 0, strlen($f)) == $f || $is_index)
			{
				return true;
			}
		}
		
		return false;
	}
}	