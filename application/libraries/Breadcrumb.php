<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Breadcrumb Class
 *
 * This class manages the breadcrumb object
 *
 * @package		Breadcrumb
 * @version		1.0
 * @author 		Richard Davey <info@richarddavey.com>
 * @copyright 	Copyright (c) 2011, Richard Davey
 * @link		https://github.com/richarddavey/codeigniter-breadcrumb
 */
class Breadcrumb {
	
	/**
	 * Breadcrumbs stack
	 *
     */
	private $breadcrumbs	= array();
	
	/**
	 * Options
	 *
	 */
	private $_divider 		= ' &nbsp;&#8250;&nbsp; ';
	private $_tag_open 		= '<div id="breadcrumb">';
	private $_tag_close 	= '</div>';
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}
		
		log_message('debug', "Breadcrumb Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	private function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->{'_' . $key}))
				{
					$this->{'_' . $key} = $val;
				}
			}
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Append crumb to stack
	 *
	 * @access	public
	 * @param	string $title
	 * @param	string $href
	 * @return	void
	 */		
	function append_crumb($title, $href)
	{
		// no title or href provided
		if (!$title OR !$href) return;
		
		// add to end
		$this->breadcrumbs[] = array('title' => $title, 'href' => $href);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Prepend crumb to stack
	 *
	 * @access	public
	 * @param	string $title
	 * @param	string $href
	 * @return	void
	 */		
	function prepend_crumb($title, $href)
	{
		// no title or href provided
		if (!$title OR !$href) return;
		
		// add to start
		array_unshift($this->breadcrumbs, array('title' => $title, 'href' => $href));
	}
	
	// --------------------------------------------------------------------

	/**
	 * Generate breadcrumb
	 *
	 * @access	public
	 * @return	string
	 */		
	function output()
	{
		// breadcrumb found
		if ($this->breadcrumbs) {
		
			// set output variable
			$output = $this->_tag_open;
			
			// add html to output
			foreach ($this->breadcrumbs as $key => $crumb) {
				
				// add divider
				if ($key) $output .= $this->_divider;
				
				// if last element
				if (end(array_keys($this->breadcrumbs)) == $key) {
					$output .= '<span class="active">' . $crumb['title'] . '</span>';
					
				// else add link and divider
				} else {
					$output .= '<a href="' . $crumb['href'] . '">' . $crumb['title'] . '</a>';
				}
			}
			
			// return html
			return $output . $this->_tag_close . PHP_EOL;
		}
		
		// return blank string
		return '';
	}
	
	
	function output_customer(){
		// breadcrumb found
		if ($this->breadcrumbs) {
		
			// set output variable
		//	$output = '<div id="breadcrumb"><ul class="customer-crumbs">';
			$output = '<ul id="breadcrumb">';	
			// add html to output
			$counter = 0;
			$crumbcount = count($this->breadcrumbs);
			foreach ($this->breadcrumbs as $key => $crumb) {
		
				// add divider
				if ($key) $output .= "";
		
				// if last element
// 				if (end(array_keys($this->breadcrumbs)) == $key) {
// 					$output .= '<li><a href="#" style="z-index:'.$crumbcount.'"><span class="active">' . $crumb['title'] . '</span></a></li>';
						
					// else add link and divider
// 				} else {
					$class = ($crumbcount == 1) ? "class='active'" : '';
					if($counter == 0)
						$output .= '<li class="first"><a href="' . $crumb['href'] . '" style="z-index:'.$crumbcount.'"><span></span>' . $crumb['title'] . '</a></li>';
					else
						$output .= '<li '.$class.'><a href="' . $crumb['href'] . '" style="z-index:'.$crumbcount.'">' . $crumb['title'] . '</a></li>';
// 				}
				
				$crumbcount--;
				$counter++;
			}
				
			// return html
			return $output . '</ul>' . PHP_EOL;
		}
		
		// return blank string
		return '';
	}

}
// END Breadcrumb Class

/* End of file Breadcrumb.php */
/* Location: ./application/libraries/Breadcrumb.php */