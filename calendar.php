<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=nutritionx;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch current year and month
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

// Function to calculate day status
function getDayStatus($date, $userId, $pdo) {
    // Fetch total calories for the day
    $stmt = $pdo->prepare("
        SELECT SUM(calories) AS total_calories 
        FROM daily_logs 
        WHERE date = :date AND user_id = :user_id
    ");
    $stmt->execute(['date' => $date, 'user_id' => $userId]);
    $log = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalCalories = $log['total_calories'] ?? 0;

    // Fetch user's calorie requirement
    $stmt = $pdo->prepare("SELECT calories FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $requiredCalories = $user['calories'] ?? 0;

    // Compare total calories with requirement
    if ($totalCalories >= ($requiredCalories - 300) && $totalCalories <= ($requiredCalories + 300)) {
        return 'green';
    } else {
        return 'red';
    }
}

// Assume user_id is provided, e.g., from a session or login
$userId = 8; // Replace with the actual logged-in user ID

// Today's date
$today = new DateTime();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Calendar</title>
    <link rel="stylesheet" href="calendar.css">
</head>
<body>
    <div class="calendar-container">
        <h1>Nutrition Calendar</h1>
        <div class="month-navigation">
            <?php
            // Calculate previous and next months
            $prevMonth = $currentMonth - 1;
            $prevYear = $currentYear;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }

            $nextMonth = $currentMonth + 1;
            $nextYear = $currentYear;
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }
            ?>
            <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="arrow">&lt; Previous</a>
            <span id="currentMonth"><?= date('F Y', strtotime("$currentYear-$currentMonth-01")) ?></span>
            <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="arrow">Next &gt;</a>
        </div>
        <div class="calendar" id="calendarGrid">
            <?php
            // Get first and last day of the month
            $firstDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
            $lastDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
            $lastDayOfMonth->modify('last day of this month');

            // Add empty cells for days before the first day of the month
            for ($i = 0; $i < $firstDayOfMonth->format('N') - 1; $i++) {
                echo '<div class="day empty"></div>';
            }

            // Add cells for each day of the month
            for ($day = 1; $day <= $lastDayOfMonth->format('d'); $day++) {
                $date = "$currentYear-$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                $dayDate = new DateTime($date);

                if ($dayDate > $today) {
                    echo "<div class='day'>$day</div>"; // No highlight for future dates
                } else {
                    $statusClass = getDayStatus($date, $userId, $pdo);
                    echo "<div class='day $statusClass'>$day</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
