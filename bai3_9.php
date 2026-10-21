<?php
$errors = [];
$success = '';

$data = [
    'name' => '',
    'email' => '',
    'subject' => 'Hỗ trợ kỹ thuật',
    'message' => ''
];

// Các chủ đề hợp lệ
$allowedSubjects = [
    'Hỗ trợ kỹ thuật',
    'Góp ý',
    'Hợp tác'
];

// Hàm mã hóa dữ liệu trước khi hiển thị, chống XSS
function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Xử lý form khi gửi bằng POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Chuẩn hóa và kiểm tra kiểu dữ liệu đầu vào
    foreach (['name', 'email', 'subject', 'message'] as $field) {
        if (isset($_POST[$field]) && is_string($_POST[$field])) {
            $data[$field] = trim($_POST[$field]);
        } elseif (isset($_POST[$field])) {
            $errors[$field] = 'Dữ liệu nhập không hợp lệ.';
        }
    }

    // 2. Kiểm tra họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    } elseif (mb_strlen($data['name'], 'UTF-8') > 100) {
        $errors['name'] = 'Họ tên không được vượt quá 100 ký tự.';
    }

    // 3. Kiểm tra email
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    } elseif (strlen($data['email']) > 254) {
        $errors['email'] = 'Email không được vượt quá 254 ký tự.';
    }

    // 4. Kiểm tra chủ đề
    if (!in_array($data['subject'], $allowedSubjects, true)) {
        $errors['subject'] = 'Vui lòng chọn chủ đề hợp lệ.';
    }

    // 5. Kiểm tra nội dung
    $messageLength = mb_strlen($data['message'], 'UTF-8');

    if ($data['message'] === '') {
        $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif ($messageLength < 10 || $messageLength > 500) {
        $errors['message'] = 'Nội dung phải từ 10 đến 500 ký tự.';
    }

    // 6. Kiểm tra ảnh đại diện (không bắt buộc)
    $file = $_FILES['avatar'] ?? null;
    $avatarMime = null;

    if ($file !== null && is_array($file)) {
        $fileError = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($fileError !== UPLOAD_ERR_NO_FILE) {

            if ($fileError !== UPLOAD_ERR_OK) {
                $errors['avatar'] = 'Có lỗi khi tải ảnh lên.';
            } elseif (
                !isset($file['tmp_name'], $file['size']) ||
                !is_uploaded_file($file['tmp_name'])
            ) {
                $errors['avatar'] = 'File tải lên không hợp lệ.';
            } elseif (
                $file['size'] <= 0 ||
                $file['size'] > 2 * 1024 * 1024
            ) {
                $errors['avatar'] = 'Ảnh phải lớn hơn 0 và không quá 2MB.';
            } else {
                // Kiểm tra MIME type thực tế của file
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                if ($finfo === false) {
                    $errors['avatar'] = 'Không thể kiểm tra định dạng ảnh.';
                } else {
                    $avatarMime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    $allowedMimeTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/gif'
                    ];

                    if (!in_array($avatarMime, $allowedMimeTypes, true)) {
                        $errors['avatar'] =
                            'Chỉ chấp nhận ảnh JPG, PNG hoặc GIF.';
                    } elseif (@getimagesize($file['tmp_name']) === false) {
                        $errors['avatar'] = 'File ảnh không hợp lệ.';
                    }
                }
            }
        }
    } elseif ($file !== null) {
        $errors['avatar'] = 'Dữ liệu file không hợp lệ.';
    }

    // 7. Chỉ xử lý khi không có lỗi
    if (empty($errors)) {
        $uploadSuccess = true;

        // Lưu ảnh nếu người dùng đã chọn file
        if (
            $file !== null &&
            is_array($file) &&
            ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK
        ) {
            // Phần mở rộng dựa trên MIME type đã xác thực
            $extensions = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif'
            ];

            $extension = $extensions[$avatarMime] ?? null;
            $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';

            if ($extension === null) {
                $errors['avatar'] = 'Định dạng ảnh không hợp lệ.';
                $uploadSuccess = false;
            } elseif (
                !is_dir($uploadDir) &&
                !mkdir($uploadDir, 0755, true) &&
                !is_dir($uploadDir)
            ) {
                $errors['avatar'] = 'Không thể tạo thư mục lưu ảnh.';
                $uploadSuccess = false;
            } else {
                // Đặt tên file mới, không sử dụng tên do người dùng gửi
                $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
                $destination = $uploadDir . DIRECTORY_SEPARATOR . $newFileName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    $errors['avatar'] = 'Không thể lưu ảnh. Vui lòng thử lại.';
                    $uploadSuccess = false;
                }
            }
        }

        if ($uploadSuccess) {
            $success = 'Gửi liên hệ thành công!';

            // Xóa dữ liệu sau khi gửi thành công
            $data = [
                'name' => '',
                'email' => '',
                'subject' => 'Hỗ trợ kỹ thuật',
                'message' => ''
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài 3.9 - Form liên hệ an toàn</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f1ff;
            margin: 0;
            padding: 35px 16px;
            color: #333;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #6545c5;
            margin-top: 0;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 11px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #d5c8ff;
            border-color: #7352cf;
        }

        textarea {
            height: 110px;
            resize: vertical;
        }

        .error-text {
            color: #c62828;
            font-size: 13px;
            margin-top: 6px;
        }

        .error-box {
            color: #b42323;
            background: #fff0f0;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .success-text {
            color: #237a3b;
            background: #e8f5e9;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            padding: 12px;
            border-radius: 6px;
        }

        .hint {
            display: block;
            color: #666;
            margin-top: 6px;
            font-size: 12px;
        }

        button {
            background: #7352cf;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #5d3eb5;
        }

        @media (max-width: 480px) {
            .card {
                padding: 22px 18px;
            }
        }
    </style>
</head>

<body>
<div class="card">
    <h2>Liên hệ</h2>
    <p class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <?php if ($success !== ''): ?>
        <div class="success-text" role="status"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-box" role="alert">
            Dữ liệu chưa hợp lệ. Vui lòng kiểm tra lại các trường bên dưới.
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">

        <div class="form-group">
            <label for="name">Họ tên *</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                value="<?= e($data['name']) ?>"
                placeholder="Nhập họ tên..."
                required
            >
            <?php if (isset($errors['name'])): ?>
                <div class="error-text"><?= e($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="254"
                value="<?= e($data['email']) ?>"
                placeholder="name@example.com"
                required
            >
            <?php if (isset($errors['email'])): ?>
                <div class="error-text"><?= e($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="subject">Chủ đề *</label>
            <select id="subject" name="subject" required>
                <option value="Hỗ trợ kỹ thuật"
                    <?= $data['subject'] === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>
                    Hỗ trợ kỹ thuật
                </option>

                <option value="Góp ý"
                    <?= $data['subject'] === 'Góp ý' ? 'selected' : '' ?>>
                    Góp ý
                </option>

                <option value="Hợp tác"
                    <?= $data['subject'] === 'Hợp tác' ? 'selected' : '' ?>>
                    Hợp tác kinh doanh
                </option>
            </select>
            <?php if (isset($errors['subject'])): ?>
                <div class="error-text"><?= e($errors['subject']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="message">Nội dung *</label>
            <textarea
                id="message"
                name="message"
                minlength="10"
                maxlength="500"
                placeholder="Nhập nội dung liên hệ..."
                required
            ><?= e($data['message']) ?></textarea>

            <small class="hint">Nội dung phải từ 10 đến 500 ký tự.</small>

            <?php if (isset($errors['message'])): ?>
                <div class="error-text"><?= e($errors['message']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="avatar">Ảnh đại diện (tùy chọn)</label>
            <input
                type="file"
                id="avatar"
                name="avatar"
                accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif"
            >

            <small class="hint">Chỉ nhận ảnh JPG, PNG hoặc GIF, tối đa 2MB.</small>

            <?php if (isset($errors['avatar'])): ?>
                <div class="error-text"><?= e($errors['avatar']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>
</body>
</html>
