

<?php
// PHP program to Remove
// Special Character From String

// Function to remove the special
function RemoveSpecialChar($str){

    // Using str_ireplace() function
    // to replace the word
    $res = str_ireplace( array( '\'', '"',
        ',' , ';', '<', '>' ), ' ^ ', $str);

    // returning the result
    return $res;
}

// Given string
$str = "Example,to remove<the>Special'Char;";

echo $str;

echo " After <br>";

// Function calling
$str1 = RemoveSpecialChar($str);

// Printing the result
echo $str1;
?>