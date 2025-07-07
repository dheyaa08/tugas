<!DOCTYPE html>
<html>
<head>
    <title>Input Data Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .error { color: red; font-size: 14px; }
        .success { color: green; background: #f0f8ff; padding: 10px; border-radius: 4px; }
        .student-list { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Form Input Data Siswa</h2>
    
    <?php
    // Inisialisasi session untuk menyimpan data siswa
    session_start();
    if (!isset($_SESSION['students'])) {
        $_SESSION['students'] = [];
    }
    
    // Fungsi validasi
    function validateInput($data) {
        return htmlspecialchars(trim($data));
    }
    
    // Proses form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        
        // Validasi input
        $name = validateInput($_POST['name']);
        $nim = validateInput($_POST['nim']);
        $email = validateInput($_POST['email']);
        $phone = validateInput($_POST['phone']);
        $major = validateInput($_POST['major']);
        $semester = validateInput($_POST['semester']);
        $address = validateInput($_POST['address']);
        
        // Validasi nama
        if (empty($name)) {
            $errors[] = "Nama harus diisi";
        } elseif (strlen($name) < 3) {
            $errors[] = "Nama minimal 3 karakter";
        }
        
        // Validasi NIM
        if (empty($nim)) {
            $errors[] = "NIM harus diisi";
        } elseif (!preg_match("/^[0-9]{8,12}$/", $nim)) {
            $errors[] = "NIM harus berupa angka 8-12 digit";
        }
        
        // Validasi email
        if (empty($email)) {
            $errors[] = "Email harus diisi";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
        
        // Validasi nomor telepon
        if (empty($phone)) {
            $errors[] = "Nomor telepon harus diisi";
        } elseif (!preg_match("/^(\+62|0)[0-9]{8,13}$/", $phone)) {
            $errors[] = "Format nomor telepon tidak valid";
        }
        
        // Validasi jurusan
        if (empty($major)) {
            $errors[] = "Jurusan harus dipilih";
        }
        
        // Validasi semester
        if (empty($semester) || $semester < 1 || $semester > 8) {
            $errors[] = "Semester harus antara 1-8";
        }
        
        // Cek apakah NIM sudah ada
        foreach ($_SESSION['students'] as $student) {
            if ($student['nim'] == $nim) {
                $errors[] = "NIM sudah terdaftar";
                break;
            }
        }
        
        // Jika tidak ada error, simpan data
        if (empty($errors)) {
            $newStudent = [
                'name' => $name,
                'nim' => $nim,
                'email' => $email,
                'phone' => $phone,
                'major' => $major,
                'semester' => $semester,
                'address' => $address,
                'registered_at' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['students'][] = $newStudent;
            echo "<div class='success'>Data siswa berhasil disimpan!</div>";
        } else {
            foreach ($errors as $error) {
                echo "<div class='error'>$error</div>";
            }
        }
    }
    ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>NIM:</label>
            <input type="text" name="nim" value="<?php echo isset($_POST['nim']) ? $_POST['nim'] : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nomor Telepon:</label>
            <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Jurusan:</label>
            <select name="major" required>
                <option value="">Pilih Jurusan</option>
                <option value="Teknik Informatika" <?php echo (isset($_POST['major']) && $_POST['major'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                <option value="Sistem Informasi" <?php echo (isset($_POST['major']) && $_POST['major'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                <option value="Teknik Komputer" <?php echo (isset($_POST['major']) && $_POST['major'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                <option value="Manajemen Informatika" <?php echo (isset($_POST['major']) && $_POST['major'] == 'Manajemen Informatika') ? 'selected' : ''; ?>>Manajemen Informatika</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Semester:</label>
            <select name="semester" required>
                <option value="">Pilih Semester</option>
                <?php
        if (!empty($_SESSION['students'])) {
            echo "<table>";
            echo "<tr><th>No</th><th>Nama</th><th>NIM</th><th>Email</th><th>Telepon</th><th>Jurusan</th><th>Semester</th><th>Tanggal Daftar</th></tr>";
            
            foreach ($_SESSION['students'] as $index => $student) {
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td>" . $student['name'] . "</td>";
                echo "<td>" . $student['nim'] . "</td>";
                echo "<td>" . $student['email'] . "</td>";
                echo "<td>" . $student['phone'] . "</td>";
                echo "<td>" . $student['major'] . "</td>";
                echo "<td>" . $student['semester'] . "</td>";
                echo "<td>" . $student['registered_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<p><strong>Total siswa terdaftar: " . count($_SESSION['students']) . "</strong></p>";
        } else {
            echo "<p>Belum ada siswa yang terdaftar.</p>";
        }
        ?>
    </div>
</body>
</html>

#### 2. Mengelola Data dengan Array
Buat file `array_management.php`:

```php
<?php
// Data siswa dalam bentuk multidimensional array
$students = [
    [
        "id" => 1,
        "name" => "Alice Johnson",
        "nim" => "12345678",
        "major" => "Teknik Informatika",
        "semester" => 6,
        "scores" => [85, 90, 78, 92, 88]
    ],
    [
        "id" => 2,
        "name" => "Bob Smith",
        "nim" => "12345679",
        "major" => "Sistem Informasi",
        "semester" => 4,
        "scores" => [78, 85, 88, 80, 82]
    ],
    [
        "id" => 3,
        "name" => "Charlie Brown",
        "nim" => "12345680",
        "major" => "Teknik Komputer",
        "semester" => 2,
        "scores" => [92, 88, 95, 90, 87]
    ],
    [
        "id" => 4,
        "name" => "Diana Prince",
        "nim" => "12345681",
        "major" => "Teknik Informatika",
        "semester" => 8,
        "scores" => [95, 93, 89, 91, 94]
    ]
];

// Fungsi untuk menghitung rata-rata nilai
function calculateAverage($scores) {
    return round(array_sum($scores) / count($scores), 2);
}

// Fungsi untuk menentukan grade berdasarkan rata-rata
function getGrade($average) {
    if ($average >= 90) return 'A';
    elseif ($average >= 80) return 'B';
    elseif ($average >= 70) return 'C';
    elseif ($average >= 60) return 'D';
    else return 'E';
}

// Fungsi untuk mencari siswa berdasarkan NIM
function findStudentByNIM($students, $nim) {
    foreach ($students as $student) {
        if ($student['nim'] == $nim) {
            return $student;
        }
    }
    return null;
}

// Fungsi untuk mengelompokkan siswa berdasarkan jurusan
function groupByMajor($students) {
    $grouped = [];
    foreach ($students as $student) {
        $major = $student['major'];
        if (!isset($grouped[$major])) {
            $grouped[$major] = [];
        }
        $grouped[$major][] = $student;
    }
    return $grouped;
}

// Fungsi untuk mengurutkan siswa berdasarkan rata-rata nilai
function sortByAverage($students) {
    usort($students, function($a, $b) {
        $avgA = calculateAverage($a['scores']);
        $avgB = calculateAverage($b['scores']);
        return $avgB <=> $avgA; // Descending order
    });
    return $students;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Data Siswa dengan Array</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .grade-A { background-color: #d4edda; }
        .grade-B { background-color: #d1ecf1; }
        .grade-C { background-color: #fff3cd; }
        .grade-D { background-color: #f8d7da; }
        .grade-E { background-color: #f5c6cb; }
        .search-form { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        input[type="text"] { padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Sistem Manajemen Data Siswa</h1>
    
    <!-- Form Pencarian -->
    <div class="search-form">
        <form method="GET">
            <input type="text" name="search_nim" placeholder="Cari berdasarkan NIM" value="<?php echo isset($_GET['search_nim']) ? $_GET['search_nim'] : ''; ?>">
            <button type="submit">Cari</button>
            <a href="?"><button type="button">Reset</button></a>
        </form>
    </div>
    
    <?php
    // Proses pencarian
    if (isset($_GET['search_nim']) && !empty($_GET['search_nim'])) {
        $searchNIM = $_GET['search_nim'];
        $foundStudent = findStudentByNIM($students, $searchNIM);
        
        if ($foundStudent) {
            echo "<h2>Hasil Pencarian untuk NIM: $searchNIM</h2>";
            echo "<table>";
            echo "<tr><th>Nama</th><th>NIM</th><th>Jurusan</th><th>Semester</th><th>Nilai</th><th>Rata-rata</th><th>Grade</th></tr>";
            
            $average = calculateAverage($foundStudent['scores']);
            $grade = getGrade($average);
            
            echo "<tr class='grade-$grade'>";
            echo "<td>{$foundStudent['name']}</td>";
            echo "<td>{$foundStudent['nim']}</td>";
            echo "<td>{$foundStudent['major']}</td>";
            echo "<td>{$foundStudent['semester']}</td>";
            echo "<td>" . implode(", ", $foundStudent['scores']) . "</td>";
            echo "<td>$average</td>";
            echo "<td>$grade</td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "<p style='color: red;'>Siswa dengan NIM $searchNIM tidak ditemukan.</p>";
        }
    } else {
        // Tampilkan semua data siswa
        echo "<h2>Daftar Semua Siswa</h2>";
        echo "<table>";
        echo "<tr><th>No</th><th>Nama</th><th>NIM</th><th>Jurusan</th><th>Semester</th><th>Nilai</th><th>Rata-rata</th><th>Grade</th></tr>";
        
        foreach ($students as $index => $student) {
            $average = calculateAverage($student['scores']);
            $grade = getGrade($average);
            
            echo "<tr class='grade-$grade'>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>{$student['name']}</td>";
            echo "<td>{$student['nim']}</td>";
            echo "<td>{$student['major']}</td>";
            echo "<td>{$student['semester']}</td>";
            echo "<td>" . implode(", ", $student['scores']) . "</td>";
            echo "<td>$average</td>";
            echo "<td>$grade</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Statistik
        echo "<h2>Statistik</h2>";
        $totalStudents = count($students);
        $averages = array_map(function($student) {
            return calculateAverage($student['scores']);
        }, $students);
        
        $overallAverage = round(array_sum($averages) / count($averages), 2);
        $highestAverage = max($averages);
        $lowestAverage = min($averages);
        
        echo "<p><strong>Total Siswa:</strong> $totalStudents</p>";
        echo "<p><strong>Rata-rata Keseluruhan:</strong> $overallAverage</p>";
        echo "<p><strong>Nilai Tertinggi:</strong> $highestAverage</p>";
        echo "<p><strong>Nilai Terendah:</strong> $lowestAverage</p>";
        
        // Pengelompokan berdasarkan jurusan
        echo "<h2>Pengelompokan Berdasarkan Jurusan</h2>";
        $groupedStudents = groupByMajor($students);
        
        foreach ($groupedStudents as $major => $majorStudents) {
            echo "<h3>$major (" . count($majorStudents) . " siswa)</h3>";
            echo "<table>";
            echo "<tr><th>Nama</th><th>NIM</th><th>Semester</th><th>Rata-rata</th><th>Grade</th></tr>";
            
            foreach ($majorStudents as $student) {
                $average = calculateAverage($student['scores']);
                $grade = getGrade($average);
                
                echo "<tr class='grade-$grade'>";
                echo "<td>{$student['name']}</td>";
                echo "<td>{$student['nim']}</td>";
                echo "<td>{$student['semester']}</td>";
                echo "<td>$average</td>";
                echo "<td>$grade</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Ranking siswa berdasarkan rata-rata nilai
        echo "<h2>Ranking Siswa (Berdasarkan Rata-rata Nilai)</h2>";
        $sortedStudents = sortByAverage($students);
        
        echo "<table>";
        echo "<tr><th>Ranking</th><th>Nama</th><th>NIM</th><th>Jurusan</th><th>Rata-rata</th><th>Grade</th></tr>";
        
        foreach ($sortedStudents as $rank => $student) {
            $average = calculateAverage($student['scores']);
            $grade = getGrade($average);
            
            echo "<tr class='grade-$grade'>";
            echo "<td>" . ($rank + 1) . "</td>";
            echo "<td>{$student['name']}</td>";
            echo "<td>{$student['nim']}</td>";
            echo "<td>{$student['major']}</td>";
            echo "<td>$average</td>";
            echo "<td>$grade</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>

<?php
// Membaca file baris per baris dengan fgets()
function readFileLineByLine($filename) {
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        
        if ($file) {
            echo "<h3>Membaca file: $filename</h3>";
            $lineNumber = 1;
            
            while (($line = fgets($file)) !== false) {
                echo "Baris $lineNumber: " . htmlspecialchars($line) . "<br>";
                $lineNumber++;
            }
            
            fclose($file);
        } else {
            echo "Gagal membuka file: $filename";
        }
    } else {
        echo "File tidak ditemukan: $filename";
    }
}

// Membaca seluruh file dengan fread()
function readEntireFile($filename) {
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        
        if ($file) {
            $fileSize = filesize($filename);
            $content = fread($file, $fileSize);
            
            echo "<h3>Isi file: $filename</h3>";
            echo "<pre>" . htmlspecialchars($content) . "</pre>";
            
            fclose($file);
        } else {
            echo "Gagal membuka file: $filename";
        }
    } else {
        echo "File tidak ditemukan: $filename";
    }
}

// Menggunakan file_get_contents() (lebih sederhana)
function readFileSimple($filename) {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        echo "<h3>Isi file (dengan file_get_contents): $filename</h3>";
        echo "<pre>" . htmlspecialchars($content) . "</pre>";
    } else {
        echo "File tidak ditemukan: $filename";
    }
}

// Contoh penggunaan
$testFile = "sample.txt";
readFileLineByLine($testFile);
readEntireFile($testFile);
readFileSimple($testFile);
?>