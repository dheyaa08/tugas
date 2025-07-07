<!-- file: upload_form.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .upload-form { background: #f8f9fa; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="file"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Upload File</h2>
    <div class="upload-form">
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Pilih file untuk diupload:</label>
                <input type="file" name="uploadFile" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi file (opsional):</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>
            
            <button type="submit" name="submit">Upload File</button>
        </form>
    </div>
</body>
</html>