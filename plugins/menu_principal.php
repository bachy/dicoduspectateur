<?php

/**
 * navigation plugin which generates a better configurable navigation with endless children navigations
 *
 * @author Ahmet Topal
 * @link http://ahmet-topal.com
 * @license http://opensource.org/licenses/MIT
 */
class Menu_Principal {	
	##
	# VARS
	##
	private $settings = array();
	private $navigation = array();
	
	##
	# HOOKS
	##
	
	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page)
	{
		$navigation = array();
		
		foreach ($pages as $page)
		{
			if (!$this->at_exclude($page))
			{
				$_split = explode('/', substr($page['url'], strlen($this->settings['base_url'])+1));
				$navigation = array_merge_recursive($navigation, $this->at_recursive($_split, $page, $current_page));
			}
		}
		
		array_multisort($navigation);
		$this->navigation = $navigation;

	}
	
	public function config_loaded(&$settings)
	{
		$this->settings = $settings;
		
		// default id
		if (!isset($this->settings['menu_principal']['id'])) { $this->settings['menu_principal']['id'] = 'menu-principal'; }
		
		// default classes
		if (!isset($this->settings['menu_principal']['class'])) { $this->settings['menu_principal']['class'] = 'menu-principal'; }
		if (!isset($this->settings['menu_principal']['class_li'])) { $this->settings['menu_principal']['class_li'] = 'li-item'; }
		if (!isset($this->settings['menu_principal']['class_a'])) { $this->settings['menu_principal']['class_a'] = 'a-item'; }
		
		// default excludes
		$this->settings['menu_principal']['exclude'] = array_merge_recursive(
			array('single' => array(), 'folder' => array()),
			isset($this->settings['menu_principal']['exclude']) ? $this->settings['menu_principal']['exclude'] : array()
		);
	}
	
	public function before_render(&$twig_vars, &$twig)
	{
		$twig_vars['menu_principal']['navigation'] = $this->at_build_navigation($this->navigation, true);
	}

	##
	# HELPER
	##
	
	private function at_build_navigation($navigation = array(), $start = false)
	{
		$id = $start ? $this->settings['menu_principal']['id'] : '';
		$class = $start ? $this->settings['menu_principal']['class'] : '';
		$class_li = $this->settings['menu_principal']['class_li'];
		$class_a = $this->settings['menu_principal']['class_a'];
		$child = '';
		$ul = $start ? '<ul id="%s" class="%s">%s</ul>' : '<ul>%s</ul>';

		if (isset($navigation['_child']))
		{
			$_child = $navigation['_child'];
			array_multisort($_child);

			foreach ($_child as $c)
			{
				$child .= $this->at_build_navigation($c);
			}
			if($start){
				$child = sprintf($ul, $id, $class, $child);
			}
			else{
				$child = sprintf($ul, $child);
			}
		}
		
		if(isset($navigation['title'])){

        	$li = sprintf(
				'<li class="%1$s %5$s large-2 columns"><a href="%2$s" class="%1$s %6$s" title="%3$s"><h3>%3$s</h3></a>%4$s</li>',
				$navigation['class'],
				$navigation['url'],
				$navigation['title'],
				$child,
				$class_li,
				$class_a
			);
		}
		else {
			$li = $child ;
		}

		return $li;
	}
	
	private function at_exclude($page)
	{
		$exclude = $this->settings['menu_principal']['exclude'];
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
	
	private function at_recursive($split = array(), $page = array(), $current_page = array())
	{
		$activeClass = (isset($this->settings['menu_principal']['activeClass'])) ? $this->settings['menu_principal']['activeClass'] : 'is-active';
		if (count($split) == 1)
		{			
			$is_index = ($split[0] == '') ? true : false;

			$ret = array(
				'title'			=> $page['title'],
				'url'			=> $page['url'],
				'excerpt'		=> $page['excerpt'],
				'class'			=> ($page['url'] == $current_page['url']) ? $activeClass : ''
			);
			
			$split0 = ($split[0] == '') ? '_index' : $split[0];
			return array('_child' => array($split0 => $ret));
			return $is_index ? $ret : array('_child' => array($split[0] => $ret));
		}
		else
		{
			if ($split[1] == '')
			{
				array_pop($split);
				return $this->at_recursive($split, $page, $current_page);
			}
			
			$first = array_shift($split);
			return array('_child' => array($first => $this->at_recursive($split, $page, $current_page)));
		}
	}
}
?>