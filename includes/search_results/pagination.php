<?php
  $total_pages = ceil($total_records / $num_rec_per_page);
  $pagination_start = $current_page > 3 ? $current_page - 3 : $current_page;
  $pagination_end = $total_pages >= $pagination_start + 8 ? $pagination_start + 8 : $total_pages;
?>
<div class="results-pagination text-center" style='display: flex; justify-content: center;'>
  <nav aria-label="...">
    <ul class="pagination pagination-sm">
      <li><a href="#" data-page="1"><span aria-hidden="true">First</span></a></li>
      <li <?php if ($current_page == 1) { ?> class="disabled" <?php } ?>><a href="#" data-previous-page data-current-page="<?php echo $current_page ?>"><span aria-hidden="true">&laquo;</span></a></li>
      <?php for($page = $pagination_start; $page <= $pagination_end; $page++) { ?>
        <li <?php if ($current_page == $page) {?>class="active"<?php } ?>><a href="#" data-page="<?php echo $page; ?>"><span aria-hidden="true"><?php echo $page; ?></span></a></li>
      <?php } ?>
      <li <?php if ($current_page == $total_pages) { ?> class="disabled" <?php } ?>><a href="#" data-next-page data-current-page="<?php echo $current_page; ?>"><span aria-hidden="true">&raquo;</span></a></li>
      <li><a href="#" data-page="<?php echo $total_pages; ?>"><span aria-hidden="true">Last</span></a></li>
    </ul>
  </nav>
</div>
