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



/**
 * Converts a day ID to its corresponding name
 */
function getDayName($day_id) {
    $days = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday'
    ];
    return isset($days[$day_id]) ? $days[$day_id] : 'Unknown Day';
}