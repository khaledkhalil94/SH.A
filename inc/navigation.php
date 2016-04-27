<?php 
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

 ?>