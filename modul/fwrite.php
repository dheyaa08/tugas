<?php
function writeToFile($filename, $content, $mode = "w") {
    $file = fopen($filename, $mode);
    
    if ($file) {
        $bytesWritten = fwrite($file, $content);
        fclose($file);
        
        if ($bytesWritten !== false) {
            echo "Berhasil menulis $bytesWritten bytes ke file: $filename<br>";
            return true;
        } else {
            echo "Gagal menulis ke file: $filename<br>";
            return false;
        }
    } else {
        echo "Gagal membuka file untuk ditulis: $filename<br>";
        return false;
    }
}

// Contoh penggunaan
$content = "Ini adalah contoh text yang akan ditulis ke file.\n";
$content .= "Baris kedua dari file.\n";
$content .= "Tanggal: " . date("Y-m-d H:i:s") . "\n";

writeToFile("output.txt", $content);

// Menambahkan content ke file yang sudah ada (append mode)
$additionalContent = "Ini adalah text tambahan.\n";
writeToFile("output.txt", $additionalContent, "a");
?>