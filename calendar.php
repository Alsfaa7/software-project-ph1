<?php
session_start();

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch the logged-in user's data
$userId = $_SESSION['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch current year and month
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

// Function to calculate day status and calories
function getDayStatusAndCalories($date, $userId, $pdo) {
    // Fetch total calories for the day
    $stmt = $pdo->prepare("
        SELECT SUM(calories) AS total_calories 
        FROM daily_logs 
        WHERE date = :date AND user_id = :user_id
    ");
    $stmt->execute(['date' => $date, 'user_id' => $userId]);
    $log = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalCalories = $log['total_calories'] ?? 0;

    // If no calories were logged, return no status
    if ($totalCalories == 0) {
        return ['status' => '', 'calories' => 0]; // No highlight for days with no logged food
    }

    // Fetch user's calorie requirement
    $stmt = $pdo->prepare("SELECT calories FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $requiredCalories = $user['calories'] ?? 0;

    // Compare total calories with requirement
    if ($totalCalories >= ($requiredCalories - 300) && $totalCalories <= ($requiredCalories + 300)) {
        return ['status' => 'green', 'calories' => $totalCalories];
    } else {
        return ['status' => 'red', 'calories' => $totalCalories];
    }
}

// Today's date
$today = new DateTime();
$cutoffDate = new DateTime('2024-12-28');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Calendar</title>
    <link rel="stylesheet" href="../../Public/Styles/calendar.css">
</head>
<body>
    <?php include 'Components/usernav.php'; ?> <!-- Navigation bar included -->

    <!-- User information section -->
    <header>
        <h1>Welcome, <?= htmlspecialchars($userData['FirstName'] . ' ' . $userData['LastName']) ?>!</h1>
        <p>Email: <?= htmlspecialchars($userData['Email'] ?? 'N/A') ?></p>
        <p>Calorie Goal: <?= htmlspecialchars($userData['calories'] ?? 'N/A') ?> kcal/day</p>
    </header>

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

                // Only show highlight if food is logged for that day
                $statusAndCalories = getDayStatusAndCalories($date, $userId, $pdo);
                $statusClass = $statusAndCalories['status'];
                $calories = $statusAndCalories['calories'];

                // Skip dates before cutoff unless food was logged
                if ($dayDate < $cutoffDate && $statusClass == '') {
                    echo "<div class='day'>$day</div>"; // No highlight for dates before 2024-12-28 without food
                } else if ($dayDate > $today) {
                    echo "<div class='day'>$day</div>"; // No highlight for future dates
                } else if ($statusClass) {
                    // Only highlight if food is logged and apply red/green class
                    echo "<div class='day $statusClass' data-calories='$calories'>$day</div>";
                } else {
                    echo "<div class='day'>$day</div>";
                }
            }
            ?>
        </div>
    </div>

    <script>
        // JavaScript to handle day click and show calories popup
        document.querySelectorAll('.day.green, .day.red').forEach(day => {
            day.addEventListener('click', function () {
                const calories = this.getAttribute('data-calories');
                alert(`You ate ${calories} calories that day.`);
            });
        });
    </script>

    <?php include 'Components/footer.php'; ?> <!-- Footer included -->
</body>
</html>
