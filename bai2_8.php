
<?php
// 1. Tao mang du lieu 3 sinh vien
$students = [
    [
        "name" => "Nguyen Van An",
        "midterm" => 7,
        "final" => 8
    ],
    [
        "name" => "Tran Thi Binh",
        "midterm" => 4,
        "final" => 5
    ],
    [
        "name" => "Le Van Cuong",
        "midterm" => 6,
        "final" => 6
    ]
];

// 2. Ham tinh diem trung binh
function calculateAverage($midterm, $final)
{
    return ($midterm + $final) / 2;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm sinh viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #198754;
            color: white;
        }

        h2 {
            color: #198754;
        }
    </style>
</head>
<body>

<h2>BẢNG ĐIỂM 3 SINH VIÊN</h2>

<table>
    <tr>
        <th>STT</th>
        <th>Họ tên</th>
        <th>Điểm giữa kỳ</th>
        <th>Điểm cuối kỳ</th>
        <th>Điểm trung bình</th>
        <th>Kết quả</th>
    </tr>

    <?php foreach ($students as $index => $student): ?>
        <?php
            $average = calculateAverage(
                $student["midterm"],
                $student["final"]
            );

            $result = $average >= 5 ? "Đạt" : "Chưa đạt";
        ?>

        <tr>
            <td><?= $index + 1 ?></td>

            <td>
                <?= htmlspecialchars(
                    $student["name"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </td>

            <td><?= $student["midterm"] ?></td>
            <td><?= $student["final"] ?></td>
            <td><?= number_format($average, 2) ?></td>
            <td><?= $result ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
