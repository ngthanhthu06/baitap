<?php
$errors = [];
$success = '';
$data = ['name' => '', 'email' => '', 'subject' => 'Hỗ trợ kỹ thuật', 'message' => ''];

// Xử lý khi người dùng submit form bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Chuẩn hóa dữ liệu đầu vào (Sanitization - xóa khoảng trắng thừa)
    $data['name'] = trim($_POST['name'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['subject'] = trim($_POST['subject'] ?? '');
    $data['message'] = trim($_POST['message'] ?? '');

    // 2. Validation phía server
    // Kiểm tra họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    // Kiểm tra định dạng email
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    }

    // Kiểm tra nội dung (Bắt buộc và từ 10 đến 500 ký tự)
    $msgLength = mb_strlen($data['message']);
    if ($data['message'] === '') {
        $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif ($msgLength < 10 || $msgLength > 500) {
        $errors['message'] = 'Nội dung phải từ 10 đến 500 ký tự.';
    }

    // 3. Kiểm tra Upload ảnh đại diện với 3 điều kiện bắt buộc[cite: 39]
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Lỗi trong quá trình tải file lên.';
        } else {
            // Kiểm tra bắt buộc 1: Kích thước file tối đa (Ví dụ: 2MB)[cite: 39]
            if ($file['size'] > 2 * 1024 * 1024) {
                $errors['avatar'] = 'Kích thước file ảnh không được vượt quá 2MB.';
            }

            // Kiểm tra bắt buộc 2: Định dạng MIME type hợp lệ (Chỉ nhận ảnh JPG, PNG, GIF)[cite: 39]
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors['avatar'] = 'Chỉ chấp nhận file ảnh định dạng JPG, PNG hoặc GIF.';
            }
        }
    }

    // Nếu không có lỗi nào xảy ra
    if (empty($errors)) {
        // Xử lý lưu file upload an toàn (đổi tên mới tránh ghi đè và thực thi mã độc)[cite: 39]
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('img_', true) . '.' . strtolower($ext);
            $uploadDir = 'uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName);
        }

        $success = 'Gửi liên hệ thành công!';
        // Reset lại dữ liệu form sau khi gửi thành công
        $data = ['name' => '', 'email' => '', 'subject' => 'Hỗ trợ kỹ thuật', 'message' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Liên Hệ</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 40px; display: flex; justify-content: center; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 550px; }
        h2 { text-align: center; margin-bottom: 5px; color: #333; }
        p.subtitle { text-align: center; color: #666; margin-bottom: 25px; font-size: 0.95em; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="email"], select, textarea, input[type="file"] {
            width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;
        }
        textarea { height: 110px; resize: vertical; }
        .error-text { color: #d32f2f; font-size: 0.85em; margin-top: 5px; }
        .success-text { color: #2e7d32; font-weight: bold; margin-bottom: 20px; text-align: center; background: #e8f5e9; padding: 10px; border-radius: 4px; }
        button { background: #1976d2; color: white; border: none; padding: 12px; width: 100%; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #115293; }
    </style>
</head>
<body>

<div class="card">
    <h2>Liên hệ</h2>
    <p class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <!-- Hiển thị thông báo thành công -->
    <?php if ($success): ?>
        <div class="success-text"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <!-- Họ tên -->
        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập họ tên...">
            <?php if (isset($errors['name'])): ?>
                <div class="error-text"><?= $errors['name'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?>" placeholder="name@example.com">
            <?php if (isset($errors['email'])): ?>
                <div class="error-text"><?= $errors['email'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Chủ đề -->
        <div class="form-group">
            <label>Chủ đề</label>
            <select name="subject">
                <option value="Hỗ trợ kỹ thuật" <?= $data['subject'] === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>Hỗ trợ kỹ thuật</option>
                <option value="Góp ý" <?= $data['subject'] === 'Góp ý' ? 'selected' : '' ?>>Góp ý</option>
                <option value="Hợp tác" <?= $data['subject'] === 'Hợp tác' ? 'selected' : '' ?>>Hợp tác kinh doanh</option>
            </select>
        </div>

        <!-- Nội dung -->
        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="message" placeholder="Nhập nội dung liên hệ..."><?= htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') ?></textarea>
            <small style="color: #666;">Nội dung phải từ 10 đến 500 ký tự.</small>
            <?php if (isset($errors['message'])): ?>
                <div class="error-text"><?= $errors['message'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Upload ảnh đại diện -->
        <div class="form-group">
            <label>Ảnh đại diện (Tùy chọn)</label>
            <input type="file" name="avatar">
            <?php if (isset($errors['avatar'])): ?>
                <div class="error-text"><?= $errors['avatar'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>