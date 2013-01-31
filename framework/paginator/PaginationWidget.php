<?php

class PaginationWidget {
	private $html;
	private $queryString;
	private $params = array();

	public $pagination;
	public $buttonCount;
	public $options = array();

	public function __construct($options) {
		$this->options = array(
			'parentDivClass' => 'PaginationWidget',
			'parentUlClass' => '',
			'itemClass' => 'pagination-item',
			'firstPageLabel' => htmlentities('<<'),
			'previousPageLabel' => htmlentities('<'),
			'nextPageLabel' => htmlentities('>'),
			'lastPageLabel' => htmlentities('>>'),
			'separatorLabel' => htmlentities('...'),
			'firstPageCss' => htmlentities('first-page'),
			'previousPageCss' => htmlentities('previous-page'),
			'nextPageCss' => htmlentities('next-page'),
			'lastPageCss' => htmlentities('last-page'),
			'currentPageCss' => htmlentities('current-page'),
			'separatorCss' => htmlentities('separator'),
		);
		//copy paste the options...
		foreach ($options as $key => $value) {
			if (property_exists('PaginationWidget', $key)) {
				if (is_array($this->{$key})) {
					$this->{$key} = array_merge($this->{$key}, $value);
				}
				else {
					$this->{$key} = $value;	
				}
				
			}
		}
		$this->getQueryString();
	}

	public static function create($options) {
		return new PaginationWidget($options);
	}

	private function getQueryString() {
		$this->queryString =  $_SERVER['QUERY_STRING'];
		if ($this->queryString) {
			$this->params = $this->fromQueryStringToParams();
		}
	}

	private function fromQueryStringToParams() {
		if (!$this->queryString) return;
		$result = array();
		$params = explode('&', $this->queryString);
		foreach ($params as $value) {
			$key_value = explode('=', $value);
			$result[$key_value[0]] = $key_value[1];
		}
		return $result;
	}

	private function fromParamsToQueryString($params) {
		$items = array();
		foreach ($params as $key => $value) {
			$items[] = $key."=".$value;
		}
		return implode("&", $items);
	}

	public function run(){

		$current_page = $this->pagination->currentPage;
		$maxbuttons = $this->buttonCount;
		$num_pages = $this->pagination->pageCount;

		$this->html .= "<div class='{$this->options['parentDivClass']}'>";
		$this->html .= "<ul class='{$this->options['parentUlClass']}'>";

		$counters = 0;
	    //first and previous page
	    if ($current_page > 1) {
	    	$this->html .= "<li>".$this->_createButton(1, $this->options['firstPageLabel'], $this->options['firstPageCss'])."</li>";
	    	$this->html .= "<li>".$this->_createButton($current_page-1, $this->options['previousPageLabel'], $this->options['previousPageCss'])."</li>";
	    	$counters = 2;
	    }

	    //calculate the page range
	    if ($current_page < ($maxbuttons-4)) {
	    	$beginpage = 1;
	    }
	    elseif ($current_page > ($num_pages-floor($maxbuttons/2))) {
	    	$beginpage = $num_pages-$maxbuttons+4;
	    }
	    else {
	    	$beginpage = $current_page - floor($maxbuttons/2);
	    }
		for ( $i = $beginpage; $i <= $num_pages; $i++ )
		{
			$this->html .= "<li>";
			if ( $i == $current_page )
			{
		 		//$this->html .= " &nbsp;" . $i . "&nbsp; ";
		 		$this->html .= "<span class='{$this->options['currentPageCss']}'>" . $i . "</span>";
			}
			else
			{
		 		$this->html .= $this->_createButton($i);
			}
			$counters++;
			if ($counters>=($maxbuttons-($current_page<$num_pages ? 2 : 0))) {
				$this->html .= "<span class='{$this->options['separatorCss']}' >{$this->options['separatorLabel']}</span>";
				break; //end of the for
			}
			$this->html .= "</li>";
		}
	    //next and last
	    if ($current_page < $num_pages) {
	      $this->html .= "<li>".$this->_createButton($current_page+1, $this->options['nextPageLabel'], $this->options['nextPageCss'])."</li>";
	      $this->html .= "<li>".$this->_createButton($num_pages, $this->options['lastPageLabel'], $this->options['lastPageCss'])."</li>";
	    }
	    $this->html .= "</ul>";
		$this->html .= "</div>";
		return $this;
	}

	private function _createButton($page, $label=null, $class=null) {
		//crate the route for pagination...
		$params = $this->params;
		$params['page'] = $page;

		$newqs = $this->fromParamsToQueryString($params);
		$link = $this->pagination->route . '?' . $newqs;
		
		$html = "<a";
		$html .= ($class)? " class='{$class}'" : "";
		$html .= " href='{$link}' >";
		$html .= ($label)? $label : $page;
		$html .= "</a>";
		return $html;
	}


	public function getHtml() {
		return $this->html;
	}

	public function echoHtml() {
		echo $this->html;
	}
}

?>