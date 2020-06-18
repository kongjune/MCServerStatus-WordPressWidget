<?php
if ($pingServer !== null) {
    $server = $pingServer;
    require dirname(__FILE__) . '/partials/online.php';
} else {
    require dirname(__FILE__) . '/partials/offline.php';
}
?>
