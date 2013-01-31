<?php

class Pagination {
	public $itemCount;
	public $pageSize;
	public $currentPage;
	public $pageCount;
	public $offset;
	public $limit;
	public $route;

	public function __construct($options) {
		//copy paste the options...
		foreach ($options as $key => $value) {
			if (property_exists('Pagination', $key)) {
				$this->{$key} = $value;
			}
		}
	}

	public function calculate () {
		if (!$this->itemCount) {
			$this->itemCount = 0;
		}
		//get the currentPage from $_REQUEST or from property
		if (!$this->currentPage) {
			$this->currentPage = isset($_REQUEST['page'])? $_REQUEST['page'] : 1;
		}
		//get the pageSize from $_REQUEST or from property
		if (!$this->pageSize) {
			$this->pageSize = isset($_REQUEST['pagesize'])? $_REQUEST['pagesize'] : 20;
		}
		//getting the url...
		if (!$this->route) {
			$this->route = $_SERVER['PHP_SELF'];	
		}
		//getting the limit and the offset
		$this->limit = $this->pageSize;
		$this->pageCount = ceil($this->itemCount/$this->pageSize);
		$this->offset = ($this->pageSize * ($this->currentPage-1));
	}

}

?>