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

	public static function display($total_count, $rpp){
		global $pagination;
		$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
		$pagination = new Pagination($rpp, $current_page, $total_count);

		if ($current_page < 1 || $current_page > $pagination->total_pages()) {
		  $pagination->current_page = 1;
		} 

    	if(isset($_GET)){
			foreach ($_GET as $key => $value) {
				$q = $key != "page" ? "&{$key}={$value}" : null;
			}
		}
		$q = isset($q) ? $q :null;
		if ($total_count > $rpp):
			if ($pagination->has_prev_page()) {
				$vis = ($pagination->prev_page() > 1) ? "visible;" : "hidden;";

				echo "<a style=visibility:{$vis} href=?page=1{$q}> &laquo; </a>";
				echo "<a href=?page=". $pagination->prev_page() ."{$q}> &lsaquo; </a>";
			}
			
			for ($i=1; $i <= $pagination->total_pages(); $i++) { 
			    if ($i == $current_page) {
			        echo "<span>{$i}</span>";
			    }else {
			        echo "<a href=?page={$i}{$q}>{$i}</a>";
			    }

			}
			if ($pagination->has_next_page()) {
				echo "<a href=?page=". $pagination->next_page() ."{$q}>&rsaquo;</a>";
				if($pagination->next_page() != $pagination->lastPage()){
					echo "<a href=?page=" . $pagination->lastPage() . "{$q}>&raquo;</a>";
				}
			}
		endif;
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

	public function firstPage(){
		return $this->total_pages();
	}

	public function lastPage(){
		return $this->total_pages();
	}



} ?>