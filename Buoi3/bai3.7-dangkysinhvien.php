
<?php
$errors = [];

$data = [
    'name' => '',
    'email' => '',
    'birthday' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name'] = trim($_POST['name'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['birthday'] = trim($_POST['birthday'] ?? '');

    // Kiểm tra họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    // Kiểm tra email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    // Kiểm tra ngày sinh
    $date = DateTime::createFromFormat('!Y-m-d', $data['birthday']);
    $dateErrors = DateTime::getLastErrors();

    $validDate = $date !== false
        && $date->format('Y-m-d') === $data['birthday']
        && ($dateErrors === false
            || ($dateErrors['warning_count'] === 0
                && $dateErrors['error_count'] === 0))
        && $data['birthday'] <= date('Y-m-d');

    if (!$validDate) {
        $errors['birthday'] = 'Ngày sinh không hợp lệ.';
    }

    // Nếu hợp lệ, chuyển sang trang thành công
    if (empty($errors)) {
        session_start();

        $_SESSION['student'] = [
            'name' => $data['name'],
            'email' => $data['email'],
            'birthday' => $data['birthday']
        ];

        header('Location: success.php');
        exit;
    }
}

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
    <title>Bài 3.7 - Đăng ký sinh viên</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f0ff;
            margin: 0;
            padding: 30px 15px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 15px #00000012;
        }

        h1 {
            text-align: center;
            color: #5b4bdb;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
        }

        .error {
            color: #b42318;
            font-size: 14px;
            margin-top: 6px;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #5b4bdb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Đăng ký sinh viên</h1>

    <form method="POST">
        <label for="name">Họ và tên</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= e($data['name']) ?>"
            required
        >
        <?php if (isset($errors['name'])): ?>
            <p class="error"><?= e($errors['name']) ?></p>
        <?php endif; ?>

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= e($data['email']) ?>"
            required
        >
        <?php if (isset($errors['email'])): ?>
            <p class="error"><?= e($errors['email']) ?></p>
        <?php endif; ?>

        <label for="birthday">Ngày sinh</label>
        <input
            type="date"
            id="birthday"
            name="birthday"
            value="<?= e($data['birthday']) ?>"
            required
        >
        <?php if (isset($errors['birthday'])): ?>
            <p class="error"><?= e($errors['birthday']) ?></p>
        <?php endif; ?>

        <button type="submit">Đăng ký</button>
    </form>
</div>
</body>
</html>
