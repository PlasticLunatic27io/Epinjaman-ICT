<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function dbcon() {
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }
    try {
        $conn = new mysqli("localhost", "root", "", "ict", 3306);
        $conn->set_charset("utf8mb4");

        return $conn;
    } catch (mysqli_sql_exception $e) {
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(503);

        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Service unavailable</title>
        </head>
        <body>
            <p>The service is temporarily unavailable. Please try again later.</p>
            <script>
                alert("The service is temporarily unavailable. Please try again later.");
            </script>
        </body>
        </html>';

        exit;
    }
}