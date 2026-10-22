
<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location: bai3.7-dangkysinhvien.php');
    exit;
}

$student = $_SESSION['student'];

function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f0ff;
            padding: 30px 15px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 14px;
        }

        h1 { color: #268447; }
        p { padding: 10px; background: #f7f7fb; border-radius: 7px; }

        a { color: #5b4bdb; }
    </style>
</head>
<body>
<div class="container">
    <h1>Đăng ký thành công!</h1>

    <p>Họ tên: <?= e($student['name']) ?></p>
    <p>Email: <?= e($student['email']) ?></p>
    <p>Ngày sinh: <?= e($student['birthday']) ?></p>

    <a href="bai3.7-dangkysinhvien.php">Quay lại form đăng ký</a>
</div>
</body>
</html>
