
<?php
$score = 7.5;
$result = '';

if (!is_numeric($score) || $score < 0 || $score > 10) {
    $result = 'Điểm không hợp lệ';
} elseif ($score >= 8) {
    $result = 'Giỏi';
} elseif ($score >= 6.5) {
    $result = 'Khá';
} elseif ($score >= 5) {
    $result = 'Trung bình';
} else {
    $result = 'Chưa đạt';
}

$numbers = [1, 2, 3, 4, 5];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.3</title>
</head>
<body>
    <h1>Xếp loại học tập</h1>
    <p>Điểm: <?= $score ?></p>
    <p>Kết quả: <?= $result ?></p>

    <h2>Ví dụ vòng lặp foreach</h2>
    <?php foreach ($numbers as $number): ?>
        <p><?= $number ?></p>
    <?php endforeach; ?>
</body>
</html>
