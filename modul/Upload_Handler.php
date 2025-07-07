<?php
// file: upload_handler.php

function handleFileUpload() {
    // Konfigurasi upload
    $uploadDir = "uploads/";
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $allowedTypes = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "txt"];
    
    // Pastikan folder upload ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (isset($_POST["submit"]) && isset($_FILES["uploadFile"])) {
        $file = $_FILES["uploadFile"];
        $description = $_POST["description"] ?? "";
        
        // Informasi file
        $fileName = $file["name"];
        $fileTmpName = $file["tmp_name"];
        $fileSize = $file["size"];
        $fileError = $file["error"];
        $fileType = $file["type"];
        
        // Ambil ekstensi file
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validasi
        $errors = [];
        
        // Cek error upload
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Terjadi error saat upload file.";
        }
        
        // Cek ukuran file
        if ($fileSize > $maxFileSize) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 5MB.";
        }
        
        // Cek tipe file
        if (!in_array($fileExt, $allowedTypes)) {
            $errors[] = "Tipe file tidak diizinkan. Hanya: " . implode(", ", $allowedTypes);
        }
        
        // Jika tidak ada error, upload file
        if (empty($errors)) {
            // Generate nama file unik
            $newFileName = uniqid() . "_" . $fileName;
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // Simpan informasi file ke database atau file log
                $fileInfo = [
                    "original_name" => $fileName,
                    "new_name" => $newFileName,
                    "size" => $fileSize,
                    "type" => $fileType,
                    "extension" => $fileExt,
                    "description" => $description,
                    "upload_time" => date("Y-m-d H:i:s"),
                    "path" => $uploadPath
                ];
                
                // Simpan ke file log
                $logEntry = json_encode($fileInfo) . "\n";
                file_put_contents("upload_log.txt", $logEntry, FILE_APPEND | LOCK_EX);
                
                echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>";
                echo "<h3>Upload Berhasil!</h3>";
                echo "<p><strong>File:</strong> " . htmlspecialchars($fileName) . "</p>";
                echo "<p><strong>Ukuran:</strong> " . formatBytes($fileSize) . "</p>";
                echo "<p><strong>Tipe:</strong> " . htmlspecialchars($fileType) . "</p>";
                if (!empty($description)) {
                    echo "<p><strong>Deskripsi:</strong> " . htmlspecialchars($description) . "</p>";
                }
                echo "<p><strong>Waktu Upload:</strong> " . date("Y-m-d H:i:s") . "</p>";
                
                // Tampilkan preview jika gambar
                if (in_array($fileExt, ["jpg", "jpeg", "png", "gif"])) {
                    echo "<p><strong>Preview:</strong></p>";
                    echo "<img src='$uploadPath' style='max-width: 300px; border: 1px solid #ddd; border-radius: 4px;'>";
                }
                echo "</div>";
                
            } else {
                $errors[] = "Gagal memindahkan file ke folder upload.";
            }
        }
        
        // Tampilkan error jika ada
        if (!empty($errors)) {
            echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>";
            echo "<h3>Error Upload:</h3>";
            foreach ($errors as $error) {
                echo "<p>- $error</p>";
            }
            echo "</div>";
        }
    }
}

function formatBytes($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}

function displayUploadedFiles() {
    $logFile = "upload_log.txt";
    
    if (file_exists($logFile)) {
        echo "<h3>File yang Pernah Diupload:</h3>";
        echo "<table border='1' cellpadding='10' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #f2f2f2;'><th>Nama File</th><th>Ukuran</th><th>Tipe</th><th>Waktu Upload</th><th>Deskripsi</th></tr>";
        
        $logContent = file_get_contents($logFile);
        $lines = explode("\n", trim($logContent));
        
        foreach (array_reverse($lines) as $line) {
            if (!empty($line)) {
                $fileInfo = json_decode($line, true);
                if ($fileInfo) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fileInfo['original_name']) . "</td>";
                    echo "<td>" . formatBytes($fileInfo['size']) . "</td>";
                    echo "<td>" . htmlspecialchars($fileInfo['extension']) . "</td>";
                    echo "<td>" . $fileInfo['upload_time'] . "</td>";
                    echo "<td>" . htmlspecialchars($fileInfo['description']) . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload File - Handler</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Hasil Upload File</h2>
    
    <?php
    handleFileUpload();
    displayUploadedFiles();
    ?>
    
    <p><a href="upload_form.html">← Kembali ke form upload</a></p>
</body>
</html>