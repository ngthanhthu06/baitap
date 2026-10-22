
<?php
$name = "Lan";
$age = 20;
$score = 8.5;
$isStudent = true;

$x = "5";
$y = 5;
$result = $x + $y;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.1 - Biến và kiểu dữ liệu</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f0ff;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }

        h1 {
            color: #5b4bdb;
        }

        p {
            padding: 10px;
            background: #f8f7ff;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bài 2.1: Biến và kiểu dữ liệu PHP</h1>

        <p>Họ tên: <?= $name ?></p>
        <p>Tuổi: <?= $age ?></p>
        <p>Điểm: <?= $score ?></p>
        <p>Sinh viên: <?= $isStudent ? 'Có' : 'Không' ?></p>

        <h2>Thử nghiệm kiểu dữ liệu</h2>

        <p>Kiểu của x: <?= gettype($x) ?></p>
        <p>Kiểu của y: <?= gettype($y) ?></p>
        <p>Kết quả x + y: <?= $result ?></p>
    </div>
</body>
</html>
