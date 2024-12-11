<?php
// Base directory for files
$baseDir = 'C:\Program Files\Ampps\www\FinalProject';

// Get the file parameter from the URL
$fileName = isset($_GET['file']) ? basename($_GET['file']) : null;

// Full file path
$filePath = $baseDir . $fileName;

// Validate the file exists and is within the base directory
if ($fileName && file_exists($filePath) && strpos(realpath($filePath), realpath($baseDir)) === 0) {
    // Set headers
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Pragma: public');

    // Clear output buffer and read file
    ob_clean();
    flush();
    readfile($filePath);
    exit;
} else {
    echo "Invalid file or file not found.";
}
?>
