<?php
session_start();
$name="";
if (isset($_SESSION['userdeatil'])) {
    $name=isset($_SESSION['userdeatil']['username']) ? $_SESSION['userdeatil']['username'] : '';
}
else{
    header("Location: login.php");
    die();
}
?>
<div class="topmenu">
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
            <!--<li class="active2"><a href="#">Data Base</a></li> -->
                <li class="active3"><a href="#">Scheduled Scrap</a>
                    <ul class="sub-menu">
                        <li><a href="lead_schedulesearch.php">Schedule a New Scrap</a></li>
                        <li><a href="lead_scheduledata.php">Scheduled Scrap</a></li>
                        <li class="active4"><a href="lead_schedulebatch.php">Scrap Results</a></li>
                    </ul>
                </li>
                <li class="active1"><a href="#">Custom Search</a>
                    <ul class="sub-menu">
                        <li><a href="lead_customdatabase_search.php">Custom Database Search</a></li>
                        <li><a href="lead_custombatch.php">Lead Batches</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="leadtop"></div>
