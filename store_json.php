<?php
// Ïñßóôå ôï URL ôïõ éóôüôïðïõ ðïõ ðåñéÝ÷åé ôï JSON
$url = 'http://usidas.ceid.upatras.gr/web/2023/export.php';

// ÅëÝãîôå áí õðÜñ÷åé óýíäåóç óôï äéáäßêôõï
if (check_internet_connection()) {
    // ËÜâåôå ôï JSON áðü ôïí éóôüôïðï
    $json_data = file_get_contents($url);

    // Ïñßóôå ôï üíïìá ôïõ ôïðéêïý áñ÷åßïõ JSON
    $local_file = 'internet_data.json';

    // Áðïèçêåýóôå ôï JSON óôï ôïðéêü áñ÷åßï
    file_put_contents($local_file, $json_data);

    echo 'Json stored successfuly ' . $local_file;
} else {
    echo 'No internet connection.';
}

// ÓõíÜñôçóç ãéá Ýëåã÷ï óýíäåóçò óôï äéáäßêôõï
function check_internet_connection() {
    $connected = @fsockopen('www.example.com', 80);
    if ($connected) {
        fclose($connected);
        return true;
    }
    return false;
}
?>