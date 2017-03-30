<?php

// This will be the managers panel with links to all important pages

require_once('../../config.php');

require_login();
echo '<a href="batch/index.php">Batches</a><br>';
echo '<hr>';
echo '<a href="notification/index.php">Notifications</a>';