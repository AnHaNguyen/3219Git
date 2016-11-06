<?php
  function isActive($page) {
    global $current_page;
    if ($page == $current_page) {
      return "active";
    } else {
      return "";
    }
  }
?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">GIT-Guard</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="<?php echo isActive('Home'); ?>"><a href="index.php">1a</a></li>
                <li class="<?php echo isActive('Commit History'); ?>"><a href="commitHistory.php">1b</a></li>
				<li class="<?php echo isActive('Commit History Diff'); ?>"><a href="commitHistoryDiff.php">1c</a></li>
                <li class="<?php echo isActive('File History'); ?>"><a href="fileHistory.php">1d</a></li>
				<li class="<?php echo isActive('Num of Lines'); ?>"><a href="numOfLinesCode.php">1e</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
				<?php
				  if (isset($_SESSION["loginuser"])) {
					?>
					 <li class="<?php echo isActive('Subscription'); ?>"><a href="subscription.php">Subscription</a></li>
					<li class="<?php echo isActive('Logout'); ?>"><a href="logout.php">Log Out</a></li>
					<?php
				  } else {
					?>
					<li class="<?php echo isActive('Login'); ?>"><a href="login.php">Login</a></li>
					<?php
				  }
        		?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
