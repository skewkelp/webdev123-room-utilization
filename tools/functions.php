<?php
$_title_ = "";

function clean_input($input)
{
    // trim() removes any whitespace or predefined characters from both sides of a string.
    $input = trim($input);

    // stripslashes() removes any backslashes from the input.
    $input = stripslashes($input);

    // htmlspecialchars() converts special characters to HTML entities to prevent XSS attacks.
    $input = htmlspecialchars($input);

    // Return the sanitized input.
    return $input;
}
