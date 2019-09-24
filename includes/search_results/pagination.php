<?php
  $page_size = $search_params['page_size'];
  $total_pages = ceil($total_records / $page_size);
  $current_page = $search_params['page'];
  $pagination_start = $current_page > 3 ? $current_page - 3 : $current_page;
  $pagination_end = $total_pages >= $pagination_start + 8 ? $pagination_start + 8 : $total_pages;
?>
<ul class="pagination pagination-sm justify-content-center">
  <li class="page-item">
    <a href="#" class="page-link" data-page="1"><span aria-hidden="true">First</span></a>
  </li>
  <li class="page-item" <?php if ($current_page == 1) { ?> class="disabled" <?php } ?>>
    <a class="page-link" href="#" data-previous-page data-current-page="<?php echo $current_page ?>"><span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php for($page = $pagination_start; $page <= $pagination_end; $page++) { ?>
    <li class="page-item page-<?php echo($page); ?> <?php if ($current_page == $page) {?>active<?php  } ?>">
      <a class="page-link" href="#" data-page="<?php echo $page; ?>"><span aria-hidden="true"><?php echo $page; ?></span></a>
    </li>
  <?php } ?>
  <li class="page-item" <?php if ($current_page == $total_pages) { ?> class="disabled" <?php } ?>>
    <a class="page-link" href="#" data-next-page data-current-page="<?php echo $current_page; ?>"><span aria-hidden="true">&raquo;</span></a>
  </li>
  <li class="page-item">
    <a class="page-link" href="#" data-page="<?php echo $total_pages; ?>"><span aria-hidden="true">Last</span></a>
  </li>
</ul>
