
<?php
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

$cc = 9;
$mid = 7;
$final = 8;

$total = finalScore($cc, $mid, $final);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.5 - Hàm</title>
</head>
<body>
    <h1>Hàm tính điểm tổng kết</h1>
    <p>Chuyên cần: <?= $cc ?></p>
    <p>Giữa kỳ: <?= $mid ?></p>
    <p>Cuối kỳ: <?= $final ?></p>
    <p>Điểm tổng kết: <?= number_format($total, 2) ?></p>
</body>
</html>
