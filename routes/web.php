<?php
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/updater.php';



// Default theme is mounted at "/".
$defaultThemeRoutePath = __DIR__ . '/frontend.php';
if (file_exists($defaultThemeRoutePath)) {
    require $defaultThemeRoutePath;
}
