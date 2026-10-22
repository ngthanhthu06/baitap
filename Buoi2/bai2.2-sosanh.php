
<?php
$a = (0 == "0");
$b = (0 === "0");
$c = (false == 0);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.2</title>
</head>
<body>
    <h1>So sánh trong PHP</h1>

    <p>0 == "0": <?= $a ? 'true' : 'false' ?></p>
    <p>0 === "0": <?= $b ? 'true' : 'false' ?></p>
    <p>false == 0: <?= $c ? 'true' : 'false' ?></p>
</body>
</html>
