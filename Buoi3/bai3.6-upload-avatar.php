
<?php
$errors = [];
$success = '';
$avatar = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar'])) {
        $errors[] = 'Vui lòng chọn ảnh đại diện.';
    } else {
        $file = $_FILES['avatar'];

        // 1. Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload ảnh thất bại. Vui lòng thử lại.';
        } else {
            // 2. Giới hạn dung lượng: 2 MB
            $maxSize = 2 * 1024 * 1024;

            if ($file['size'] > $maxSize) {
                $errors[] = 'Ảnh không được vượt quá 2 MB.';
            }

            // 3. Kiểm tra loại ảnh thực tế
            $imageInfo = getimagesize($file['tmp_name']);

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif'
            ];

            $mime = $imageInfo['mime'] ?? '';

            if ($imageInfo === false || !isset($allowedTypes[$mime])) {
                $errors[] = 'Chỉ chấp nhận ảnh JPG, PNG hoặc GIF.';
            }

            // 4. Chỉ lưu khi không có lỗi
            if (empty($errors)) {
                $uploadDir = __DIR__ . '/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Đổi tên file để tránh trùng tên
                $newName = bin2hex(random_bytes(8))
                    . '.' . $allowedTypes[$mime];

                $destination = $uploadDir . $newName;

                if (move_uploaded_file(
                    $file['tmp_name'],
                    $destination
                )) {
                    $avatar = 'uploads/' . $newName;
                    $success = 'Upload ảnh đại diện thành công!';
                } else {
                    $errors[] = 'Không thể lưu ảnh. Hãy thử lại.';
                }
            }
        }
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
    <title>Bài 3.6 - Upload ảnh đại diện</title>

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
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 15px #00000012;
        }

        h1 {
            text-align: center;
            color: #5b4bdb;
            font-size: 25px;
        }

        label {
            display: block;
            margin: 20px 0 8px;
            font-weight: bold;
        }

        input[type="file"] {
            width: 100%;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background: #5b4bdb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .success {
            background: #e5f8e9;
            color: #176b2c;
            padding: 12px;
            border-radius: 8px;
        }

        .error {
            background: #fff0f0;
            color: #b42318;
            padding: 12px;
            border-radius: 8px;
        }

        img {
            display: block;
            max-width: 180px;
            max-height: 180px;
            margin: 15px auto;
            border-radius: 12px;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Upload ảnh đại diện</h1>

    <p>Chọn ảnh JPG, PNG hoặc GIF, dung lượng tối đa 2 MB.</p>

    <?php if ($success !== ''): ?>
        <p class="success"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="avatar">Ảnh đại diện:</label>
        <input
            type="file"
            name="avatar"
            id="avatar"
            accept=".jpg,.jpeg,.png,.gif"
            required
        >

        <button type="submit">Tải ảnh lên</button>
    </form>

    <?php if ($avatar !== ''): ?>
        <h3>Ảnh đã tải lên:</h3>
        <img src="<?= e($avatar) ?>" alt="Ảnh đại diện">
    <?php endif; ?>
</div>
</body>
</html>
