<?php
  require_once('FavoriteProperties.php');
// csmail added set_error_handler, so that this warning goes in to the void
// temporary fix since the way that sessions are handled is wonky and needs to be overhauled eventually
// Currently session_start is called in signin.php and called again here, which causes
// a warning about headers being sent twice
// calling restore_error_handler after this block is executed means that future errors and warning will NOT go in to the void

// swallow any warnings that surface from headers being sent again
set_error_handler(function ($severity, $message, $file, $line) {
    // no op
});
session_start();
$name="";
if (isset($_SESSION['userdetail'])) {
  $user_id = $_SESSION['userdetail']['id'];
  $name=isset($_SESSION['userdetail']['username']) ? $_SESSION['userdetail']['username'] : '';
}
else{
    header("Location: login.php");
    die();
}

// restore the original error handler
restore_error_handler();
?>

<?php

  $favorites = new FavoriteProperties();
  $favorites_folders = $favorites->getAllFoldersForUser($_SESSION['userdetail']['id']);

  $has_unseen_favorites_updates = false;
  foreach ($favorites_folders as $folder) {
    if ($folder->has_unseen_updates) {
      $has_unseen_favorites_updates = true;
      break;
    }
  }

?>

<div class="topmenu" data-user-id="<?php echo $user_id; ?>">
    <link rel="stylesheet" href="css/style.css">
    <div class="welcome">
        <div class="welcome_2">
            <p style="margin:0;">Welcome <span style="text-transform: capitalize;"><?php echo $name;?></span> &nbsp;|&nbsp; <a href="logout.php">Logout</a></p>
        </div>
    </div>
    <div class="menu">
        <div class="menu1">
            <ul id="nav">
                <li class="active5"><a href="#">Single Record Search</a>
                    <ul class="sub-menu">
                        <li><a href="live.php">Single Live Record Search</a></li>
                        <li><a href="index.php">Single Database Record Search</a></li>
                    </ul>
                </li>
                <li class="active3"><a href="#">Scheduled Scrape</a>
                    <ul class="sub-menu">
                        <li><a href="lead_schedulesearch.php">Schedule a New Scrape</a></li>
                        <li><a href="lead_scheduledata.php">Scheduled Scrape</a></li>
                        <li class="active4"><a href="lead_schedulebatch.php">Scrape Results</a></li>
                    </ul>
                </li>
                <li class="active1"><a href="#">Custom Search</a>
                    <ul class="sub-menu">
                        <li><a href="lead_customdatabase_search.php">Custom Database Search</a></li>
                        <li><a href="lead_custombatch.php">Lead Batches</a></li>
                        <li>
                          <a href="favorites_folders.php"><span class="ml-2">Favorites <span class="ml-1" <?php if (!$has_unseen_favorites_updates) { ?> hidden <?php } ?>><i class="fas fa-flag text-danger"></i></span></span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="leadtop"></div>
