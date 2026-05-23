<?php

session_start();

session_unset();

session_destroy();

$projectRoot = explode('/modules/', $_SERVER['SCRIPT_NAME'])[0];

header("Location: " . $projectRoot . "/public/");

exit();

?>