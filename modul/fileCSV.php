<?php
function readCSVFile($filename) {
    if (file_exists($filename)) {
        echo "<h3>Membaca file CSV: $filename</h3>";
        
        $file = fopen($filename, "r");
        $isFirstRow = true;
        
        echo "<table border='1' cellpadding='10'>";
        
        while (($data = fgetcsv($file)) !== false) {
            echo "<tr>";
            
            if ($isFirstRow) {
                // Header row
                foreach ($data as $cell) {
                    echo "<th>" . htmlspecialchars($cell) . "</th>";
                }
                $isFirstRow = false;
            } else {
                // Data rows
                foreach ($data as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
            }
            
            echo "</tr>";
        }
        
        echo "</table>";
        fclose($file);
    } else {
        echo "File CSV tidak ditemukan: $filename";
    }
}

// Contoh penggunaan
readCSVFile("data_siswa.csv");
?>