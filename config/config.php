<?php
//read from local_config.php if it exists
include(__DIR__ . '/local_config.php'); // read from local_config.php
// If local_config.php is not found, define constants here
// filled with some test values currently
// This is useful for testing purposes, but in production, you should use local_config.php
if (!defined("DB_HOST"))
    DEFINE('DB_HOST', 'localhost');
if (!defined("DB_USER"))
    DEFINE('DB_USER', 'crm');
if (!defined("DB_PASS"))
    DEFINE('DB_PASS', 'password');
if (!defined("DB_NAME"))
    DEFINE('DB_NAME', 'crm');
