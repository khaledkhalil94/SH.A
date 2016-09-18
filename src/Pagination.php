<?php 

class Pagination {
	
	public $rpp;

	public $current_page;

	public $total_count;

	function __construct($total_count, $current_page, $rpp=4) {
		$this->rpp = $rpp;
		$this->current_page = $current_page;
		$this->total_count = $total_count;
	}

	public function display($uq=false){
		global $pagination;

		$current_page = $this->current_page;
		$total_count = $this->total_count;
		$rpp = $this->rpp;

		if ($current_page < 1 || $current_page > $this->total_pages()) {
		  $this->current_page = 1;
		} 

		if ($total_count > $rpp):

			$html = "<div class='ui pagination menu'>";
			
			for ($i=1; $i <= $this->total_pages(); $i++) { 
			    if ($i == $this->current_page) {
			        $html .= "<a class='item active' cp='{$i}'>{$i}</a>";
			    } else {

			    	if($uq){

			        	$html .= "<a href='?cp={$i}' class='item' cp='{$i}'>{$i}</a>";
			    	} else {

			        	$html .= "<a class='item' cp='{$i}'>{$i}</a>";
			    	}
			    }
			}

			$html .= "</div>";

			return $html;

		endif;
	}


	public function offset(){
		return ($this->current_page - 1) * $this->rpp;
	}

	public function total_pages(){
		return ceil($this->total_count/$this->rpp);
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
} 
?>