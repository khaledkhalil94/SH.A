     <a <?php if ($prev_page < 1){echo 'style="visibility:hidden;"';} ?>
     	href="./?pg=<?php echo $prev_page; ?>">«</a>

    <?php $i = 0; ?>
    <?php while ($i < $total_pages) :?>
      <?php $i += 1; ?>
      <?php if ($i == $current_page) : ?>
        <span><?php echo $i ?></span>
      <?php else : ?>
        <a href="./?pg=<?php echo $i; ?>"><?php echo $i; ?></a>
      <?php endif; ?>
    <?php endwhile; ?>

    
    <a <?php if ($next_page > $total_pages){echo 'style="visibility:hidden;"';} ?>
     	href="./?pg=<?php echo $next_page; ?>">»
     </a>

