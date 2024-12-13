<?php

session_start();
// Clear semester picked status before destroying session
unset($_SESSION['semester_picked']);
unset($_SESSION['selected_semester']);
unset($_SESSION['selected_semester_id']);
session_destroy();

header('location: loginwcss.php');
