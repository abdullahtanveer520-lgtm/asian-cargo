<?php
/**
 * App bootstrap. Every public-facing PHP page includes this first.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0'); // set to '1' temporarily if you need to debug an error

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';
