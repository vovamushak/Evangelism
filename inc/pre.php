<?php
define("DOMAIN", "https://www.hopeforevangelism.com/evangel");
session_start();
include("db_conn.php");
include("functions.php");

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    // Split the header into an array of language tags
    $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

    // Extract the primary language from the first language tag
    $primaryLanguage = explode(';', $languages[0])[0];

    // Trim any leading or trailing spaces
    $primaryLanguage = trim($primaryLanguage);

    $siteLanguage = explode('-', $primaryLanguage)[0];
} else {
    // Return a default language if the header is not set
    $siteLanguage = 'en'; // Default to English if no language is provided
}
?>