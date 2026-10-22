
<?php
$name = trim($_GET['name'] ?? '');
$score = $_POST['score'] ?? '';
$error = '';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($name === '') {
        $error = 'Hãy nhập tên trên URL bằng tham số name.';
    } elseif ($score === '' || !is_numeric($score)
        || (float)$score < 0 || (float)$score > 10) {
        $error = 'Điểm phải là số từ 0 đến 10.';
    } else {
        $result = (float)$score;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.6 - GET và POST</title>
</head>
<body>
    <h1>Nhập điểm sinh viên</h1>

    <p>Tên lấy từ GET: <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="post">
        <label>Điểm (0–10):</label>
        <input type="number" name="score" min="0" max="10"
               step="0.1" required>
        <button type="submit">Kiểm tra</button>
    </form>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php elseif ($result !== null): ?>
        <p>Điểm hợp lệ: <?= $result ?></p>
    <?php endif; ?>
</body>
</html>
