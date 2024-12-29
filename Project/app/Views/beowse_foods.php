<?php
$conn = new mysqli("localhost", "root", "", "nutrition_tracker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$foods = $conn->query("SELECT * FROM foods");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Foods</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <h1>Browse Foods</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Fat</th>
                <th>Sodium</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $foods->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['protein'] ?>g</td>
                    <td><?= $row['carbs'] ?>g</td>
                    <td><?= $row['fat'] ?>g</td>
                    <td><?= $row['sodium'] ?>mg</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>