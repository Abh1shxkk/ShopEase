<?php

/**
 * Laravel Application Entry Point (for XAMPP/Apache deployment)
 * This redirects to the public/index.php
 */

// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Change the current directory to public
chdir(__DIR__ . '/public');

// Include the public index.php
require __DIR__ . '/public/index.php';