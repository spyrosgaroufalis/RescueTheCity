<?php
include"security.php";
?>

<?php
session_unset();
session_destroy();
include "web_initial_page.html";
?>