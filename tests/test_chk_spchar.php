<?php
/**
 * Checks if a string contains special characters
 * using regular expressions (preg_match()).
 *
 * @param string $str The string to check.
 * @return bool True if the string contains special characters, false otherwise.
 */

include('../config/connect_sqlserver.php');
include('../cond_file/doc_info_sale_daily_cp.php');

function containsSpecialCharacters($str)
{
    return preg_match('/[^a-zA-Z0-9\s]/', $str) === 1;
}

$strValue = "Last Coder!";

if (containsSpecialCharacters($strValue)) {
    echo "The string contains special characters.";
} else {
    echo "The string does not contain special characters.";
}
?>