
<?php
function money(float $value): string
{
    return number_format($value, 0, ',', '.') . ' đ';
}

$name = '';
$qty = '';
$price = '';
$error = '';
$total = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $qty = $_POST['qty'] ?? '';
    $price = $_POST['price'] ?? '';

    if ($name === '') {
        $error = 'Vui lòng nhập tên tài liệu.';
    } elseif (!ctype_digit((string)$qty) || (int)$qty < 1) {
        $error = 'Số lượng phải là số nguyên lớn hơn 0.';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $error = 'Đơn giá phải là số không âm.';
    } else {
        $total = (int)$qty * (float)$price;
    }
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.7 - Hóa đơn học liệu</title>
    <style>
        body { font-family: Arial; background: #f4f0ff; padding: 24px; }
        form { max-width: 450px; background: white; padding: 20px; border-radius: 12px; }
        input { display: block; width: 95%; padding: 10px; margin: 8px 0 16px; }
        button { background: #5b4bdb; color: white; padding: 10px 18px; border: 0; }
    </style>
</head>
<body>
    <h1>Hóa đơn học liệu</h1>

    <form method="post">
        <label>Tên tài liệu</label>
        <input name="name" value="<?= e($name) ?>" required>

        <label>Số lượng</label>
        <input type="number" name="qty" min="1" value="<?= e($qty) ?>" required>

        <label>Đơn giá (VNĐ)</label>
        <input type="number" name="price" min="0" step="1"
               value="<?= e($price) ?>" required>

        <button type="submit">Tính hóa đơn</button>
    </form>

    <?php if ($error !== ''): ?>
        <p><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($total !== null): ?>
        <h2>Kết quả</h2>
        <p>Tài liệu: <?= e($name) ?></p>
        <p>Số lượng: <?= (int)$qty ?></p>
        <p>Thành tiền: <?= money($total) ?></p>
    <?php endif; ?>
</body>
</html>
