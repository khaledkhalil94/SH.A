<?php 
require_once('init.php');
class Pagination {
	
	public $rpp = 4;
	public $current_page = 1;
	public $total_count;


	function __construct($rpp, $current_page, $total_count) {
		$this->rpp = $rpp;
		$this->current_page = $current_page;
		$this->total_count = $total_count;
	}

	public static function display($total_count){
		global $rpp;
		global $pagination;
		$rpp = 4; //results per page
		$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
		$pagination = new Pagination($rpp, $current_page, $total_count);

		if ($current_page < 1 || $current_page > $pagination->total_pages()) {
		  $pagination->current_page = 1;
		} 

		if ($pagination->has_prev_page()) {
		  echo "<a href=?page=". $pagination->prev_page() ."> &laquo; </a>";
		}


		$total_pages = $pagination->total_pages();
		for ($i=1; $i <= $total_pages ; $i++) { 
		    if ($i == $current_page) {
		        echo "<span>{$i}</span>";
		    }else {
		        echo "<a href=?page={$i}>{$i}</a>";
		    }

		}
		if ($pagination->has_next_page()) {
		  echo "<a href=?page=". $pagination->next_page() ."> &raquo;</a>";
		}
	}

	public function offset(){
		return ($this->current_page - 1) * $this->rpp;
	}

	public function total_pages(){
		return ceil($this->total_count/$this->rpp);
	}

	public function count(){
		return count($this->get_users());
	}

	public function next_page(){
		return $this->current_page + 1;
	}

	public function prev_page(){
		return $this->current_page - 1;
	}

	public function has_next_page(){
		return $this->next_page() <= $this->total_pages() ? true : false;
	}

	public function has_prev_page(){
		return $this->prev_page() >= 1 ? true : false;
	}



} ?>