
<?php
$students = [
    ['name' => 'Nguyễn Văn An', 'score' => 8.5],
    ['name' => 'Trần Thị Bình', 'score' => 7.0],
    ['name' => 'Lê Minh Chi', 'score' => 4.5]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.4 - Mảng sinh viên</title>
    <style>
        body { font-family: Arial; padding: 24px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background: #e9e5ff; }
    </style>
</head>
<body>
    <h1>Danh sách sinh viên</h1>

    <table>
        <tr>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Điểm</th>
        </tr>

        <?php foreach ($students as $index => $student): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= $student['score'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
