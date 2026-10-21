
<?php
$name = "";
$attendance = "";
$midterm = "";
$final = "";
$error = "";
$success = false;
$total = 0;
$rank = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $attendance = trim($_POST["attendance"] ?? "");
    $midterm = trim($_POST["midterm"] ?? "");
    $final = trim($_POST["final"] ?? "");

    if ($name === "") {
        $error = "Vui lòng nhập họ tên.";
    } elseif (
        $attendance === "" ||
        $midterm === "" ||
        $final === ""
    ) {
        $error = "Vui lòng nhập đầy đủ các điểm.";
    } elseif (
        !is_numeric($attendance) ||
        !is_numeric($midterm) ||
        !is_numeric($final)
    ) {
        $error = "Điểm phải là số.";
    } elseif (
        $attendance < 0 || $attendance > 10 ||
        $midterm < 0 || $midterm > 10 ||
        $final < 0 || $final > 10
    ) {
        $error = "Điểm phải nằm trong khoảng từ 0 đến 10.";
    } else {
        $total = 0.1 * $attendance
               + 0.3 * $midterm
               + 0.6 * $final;

        if ($total >= 8.5) {
            $rank = "Giỏi";
        } elseif ($total >= 7) {
            $rank = "Khá";
        } elseif ($total >= 5) {
            $rank = "Trung bình";
        } else {
            $rank = "Chưa đạt";
        }

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Máy tính điểm học phần</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background: #198754;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: #c62828;
        }

        .success {
            background: #e8f5e9;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Máy tính điểm học phần</h2>

    <form method="POST" action="">
        <label>Họ tên:</label>
        <input
            type="text"
            name="name"
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label>Điểm chuyên cần (10%):</label>
        <input
            type="number"
            name="attendance"
            min="0"
            max="10"
            step="0.1"
            value="<?= htmlspecialchars($attendance, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label>Điểm giữa kỳ (30%):</label>
        <input
            type="number"
            name="midterm"
            min="0"
            max="10"
            step="0.1"
            value="<?= htmlspecialchars($midterm, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label>Điểm cuối kỳ (60%):</label>
        <input
            type="number"
            name="final"
            min="0"
            max="10"
            step="0.1"
            value="<?= htmlspecialchars($final, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <button type="submit">Tính điểm</button>
    </form>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <h3>Kết quả học tập</h3>
            <p>Họ tên:
                <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p>Điểm tổng kết: <strong><?= number_format($total, 2) ?></strong></p>
            <p>Xếp loại: <strong><?= $rank ?></strong></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
