<?php
session_start();
session_unset();
session_destroy();

// Disable caching to prevent back access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: index.php");
exit;
?>
