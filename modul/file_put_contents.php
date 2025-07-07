<?php
function writeFileSimple($filename, $content, $append = false) {
    $flags = $append ? FILE_APPEND | LOCK_EX : LOCK_EX;
    
    $result = file_put_contents($filename, $content, $flags);
    
    if ($result !== false) {
        echo "Berhasil menulis $result bytes ke file: $filename<br>";
        return true;
    } else {
        echo "Gagal menulis ke file: $filename<br>";
        return false;
    }
}

// Contoh penggunaan
$data = [
    "nama" => "John Doe",
    "email" => "john@example.com",
    "tanggal" => date("Y-m-d H:i:s")
];

$jsonContent = json_encode($data, JSON_PRETTY_PRINT);
writeFileSimple("data.json", $jsonContent);
?>