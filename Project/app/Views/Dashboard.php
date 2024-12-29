<?php
session_start();

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Singleton database connection class
class Database {
    private static $instance = null;
    private $connection;

    // Database credentials
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "nutritionx";

    // Private constructor to prevent multiple instances
    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Return singleton instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Get the database connection
    public function getConnection() {
        return $this->connection;
    }
}

// Initialize DB connection
$db = Database::getInstance();
$conn = $db->getConnection();

// Fetch the logged-in user's data from database
$userData = [];
$userId = $_SESSION['id'];
$userQuery = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
if ($userResult->num_rows === 1) {
    $userData = $userResult->fetch_assoc();
}
$stmt->close();

// Fetch all available food options for the dropdown in "Add Food" section
$foodOptions = [];
$sql = "SELECT * FROM food_items";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodOptions[] = $row;
    }
}

// Handle adding and deleting food items for specific meals
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Add food to the log if meal type, food, and grams are provided
    if (isset($_POST['meal_type'], $_POST['food_select'], $_POST['grams'])) {
        $mealType = $_POST['meal_type'];
        $foodName = $_POST['food_select'];
        $grams = $_POST['grams'];
        $selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

        // Find the selected food in the food options to calculate nutritional values
        foreach ($foodOptions as $food) {
            if ($food['FoodName'] === $foodName) {
                // Calculate nutritional values based on the selected grams
                $protein = ($food['Protein'] * $grams) / 100;
                $carbs = ($food['Carbohydrates'] * $grams) / 100;
                $fat = ($food['Fats'] * $grams) / 100;
                $calories = ($food['Calories'] * $grams) / 100;

                // Insert food entry into the daily_logs table
                $stmt = $conn->prepare("INSERT INTO daily_logs (Date, MealType, FoodName, Grams, Protein, Carbohydrates, Fats, Calories, user_id)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssdddddi", $selectedDate, $mealType, $foodName, $grams, $protein, $carbs, $fat, $calories, $userId);
                $stmt->execute();
                $stmt->close();
                break;
            }
        }
    } 
    // Handle deletion of a food item from the daily log
    elseif (isset($_POST['delete_meal_type'], $_POST['delete_food_name'])) {
        $deleteMealType = $_POST['delete_meal_type'];
        $deleteFoodName = $_POST['delete_food_name'];
        $selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

        // Delete the selected meal from the log
        $stmt = $conn->prepare("DELETE FROM daily_logs WHERE Date = ? AND MealType = ? AND FoodName = ? AND user_id = ?");
        $stmt->bind_param("sssi", $selectedDate, $deleteMealType, $deleteFoodName, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle changing the selected date via the form or default to current date
if (isset($_POST['selected_date'])) {
    $_SESSION['selected_date'] = $_POST['selected_date'];
}
$selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

// Fetch the user's meal logs for the selected date
$mealLogs = [
    "Breakfast" => [],
    "Lunch" => [],
    "Dinner" => [],
    "Snacks" => []
];

$sql = "SELECT * FROM daily_logs WHERE Date = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $selectedDate, $userId);
$stmt->execute();
$result = $stmt->get_result();

// Organize the retrieved data into meal categories (Breakfast, Lunch, Dinner, Snacks)
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mealLogs[$row['MealType']][] = [
            "name" => $row['FoodName'],
            "grams" => $row['Grams'],
            "protein" => $row['Protein'],
            "carbs" => $row['Carbohydrates'],
            "fat" => $row['Fats'],
            "calories" => $row['Calories']
        ];
    }
}
$stmt->close();

// Calculate the total nutritional values (protein, carbs, fat, calories) for the selected date
$dailyTotals = ["protein" => 0, "carbs" => 0, "fat" => 0, "calories" => 0];
foreach ($mealLogs as $mealFoods) {
    foreach ($mealFoods as $food) {
        $dailyTotals["protein"] += $food["protein"];
        $dailyTotals["carbs"] += $food["carbs"];
        $dailyTotals["fat"] += $food["fat"];
        $dailyTotals["calories"] += $food["calories"];
    }
}

// Calculate the previous and next dates for easier navigation
$prevDate = date('Y-m-d', strtotime($selectedDate . ' -1 day'));
$nextDate = date('Y-m-d', strtotime($selectedDate . ' +1 day'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Dashboard</title>
    <link rel="stylesheet" href="../../Public/Styles/dashboard.css">
</head>
<body>
    <!-- Include user navigation -->
    <?php include 'Components/usernav.php'; ?>

    <div class="main-content">
        <!-- User information section -->
        <header>
            <h1>Welcome, <?= htmlspecialchars($userData['FirstName'] . ' ' . $userData['LastName']) ?>!</h1>
            <p>Email: <?= htmlspecialchars($userData['Email'] ?? 'N/A') ?></p>
            <p>Weight: <?= htmlspecialchars($userData['weight'] ?? 'N/A') ?></p>
        </header>

        <!-- Section to navigate through dates and display daily logs -->
        <section>
            <form id="dateForm" method="POST">
                <input type="hidden" id="selected_date_hidden" name="selected_date" value="<?= $selectedDate ?>">
                <button type="button" onclick="changeDate('<?= $prevDate ?>')">Previous</button>
                <label for="selected_date">Select Date:</label>
                <input type="date" id="selected_date" name="selected_date_display" value="<?= $selectedDate ?>" onchange="submitDateForm()">
                <button type="button" onclick="changeDate('<?= $nextDate ?>')">Next</button>
            </form>

            <button class="calendar-btn" onclick="window.location.href='calendar.php'">Open Calendar</button>

            <h2>Daily Food Log for <?= $selectedDate ?></h2>
            <?php foreach ($mealLogs as $meal => $foods): ?>
                <h3><?= htmlspecialchars($meal) ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Grams</th>
                            <th>Protein (g)</th>
                            <th>Carbs (g)</th>
                            <th>Fat (g)</th>
                            <th>Calories</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($foods)): ?>
                            <tr>
                                <td colspan="7">No items added yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($foods as $food): ?>
                                <tr>
                                    <td><?= htmlspecialchars($food['name']) ?></td>
                                    <td><?= htmlspecialchars($food['grams']) ?></td>
                                    <td><?= htmlspecialchars($food['protein']) ?></td>
                                    <td><?= htmlspecialchars($food['carbs']) ?></td>
                                    <td><?= htmlspecialchars($food['fat']) ?></td>
                                    <td><?= htmlspecialchars($food['calories']) ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="delete_meal_type" value="<?= htmlspecialchars($meal) ?>">
                                            <input type="hidden" name="delete_food_name" value="<?= htmlspecialchars($food['name']) ?>">
                                            <button type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </section>

        <!-- Section for adding new food items -->
        <section>
            <h2>Add Food</h2>
            <form method="POST">
                <label for="meal_type">Select Meal:</label>
                <select name="meal_type" id="meal_type" required>
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Snacks">Snacks</option>
                </select>

                <label for="food_select">Select Food:</label>
                <select name="food_select" id="food_select" required>
                    <?php foreach ($foodOptions as $food): ?>
                        <option value="<?= htmlspecialchars($food['FoodName']) ?>"><?= htmlspecialchars($food['FoodName']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="grams">Grams:</label>
                <input type="number" name="grams" id="grams" min="1" step="1" required>

                <button type="submit">Add</button>
            </form>
        </section>

        <!-- Display daily nutritional totals -->
        <section>
            <h2>Daily Nutritional Totals</h2>
            <ul>
                <li>Protein: <?= $dailyTotals['protein'] ?> g</li>
                <li>Carbohydrates: <?= $dailyTotals['carbs'] ?> g</li>
                <li>Fats: <?= $dailyTotals['fat'] ?> g</li>
                <li>Calories: <?= $dailyTotals['calories'] ?></li>
            </ul>
        </section>
    </div>

    <!-- Include the footer -->
    <?php include 'Components/footer.php'; ?>

    <script>
        function submitDateForm() {
            document.getElementById('selected_date_hidden').value = document.getElementById('selected_date').value;
            document.getElementById('dateForm').submit();
        }

        function changeDate(newDate) {
            document.getElementById('selected_date_hidden').value = newDate;
            document.getElementById('dateForm').submit();
        }
    </script>
</body>
</html>
