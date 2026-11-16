<?php

$meatlab_theme = [
    "dir" => "meatlab/default",
    "template" => "meatlab.php",
];

$themes['meatlab'] = $meatlab_theme;


$dashboard_theme = [
    "dir" => "meatlab/default",
    "template" => "dashboard.php",
];

$themes['dashboard'] = $dashboard_theme;


$login_theme = [
    "dir" => "meatlab/default",
    "template" => "login.php",
];


$themes['login'] = $login_theme;


define('THEMES', $themes);
