<?php 
session_start();

// Singleton Database Connection
class Database {
    private static $instance = null;
    private $connection;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "nutritionx";

    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Fetch food items from the database
$db = Database::getInstance();
$conn = $db->getConnection();

$foodOptions = [];
$sql = "SELECT * FROM food_items";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodOptions[] = $row;
    }
}

// Add or Delete meal log
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['meal_type'], $_POST['food_select'], $_POST['grams'])) {
        // Add meal log to the database
        $mealType = $_POST['meal_type'];
        $foodName = $_POST['food_select'];
        $grams = $_POST['grams'];
        $selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

        // Find the selected food item
        foreach ($foodOptions as $food) {
            if ($food['FoodName'] === $foodName) {
                $protein = ($food['Protein'] * $grams) / 100;
                $carbs = ($food['Carbohydrates'] * $grams) / 100;
                $fat = ($food['Fats'] * $grams) / 100;
                $calories = ($food['Calories'] * $grams) / 100;

                // Insert into daily_logs table
                $stmt = $conn->prepare("INSERT INTO daily_logs (Date, MealType, FoodName, Grams, Protein, Carbohydrates, Fats, Calories)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssddddd", $selectedDate, $mealType, $foodName, $grams, $protein, $carbs, $fat, $calories);
                $stmt->execute();
                $stmt->close();
                break;
            }
        }
    } elseif (isset($_POST['delete_meal_type'], $_POST['delete_food_name'])) {
        // Delete meal log from the database
        $deleteMealType = $_POST['delete_meal_type'];
        $deleteFoodName = $_POST['delete_food_name'];
        $selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

        // Delete from daily_logs table
        $stmt = $conn->prepare("DELETE FROM daily_logs WHERE Date = ? AND MealType = ? AND FoodName = ?");
        $stmt->bind_param("sss", $selectedDate, $deleteMealType, $deleteFoodName);
        $stmt->execute();
        $stmt->close();
    }
}

// Change the date based on user input
if (isset($_POST['selected_date'])) {
    $_SESSION['selected_date'] = $_POST['selected_date'];
}

$selectedDate = $_SESSION['selected_date'] ?? date("Y-m-d");

// Retrieve meal logs for the selected day
$mealLogs = [
    "Breakfast" => [],
    "Lunch" => [],
    "Dinner" => [],
    "Snacks" => []
];

$sql = "SELECT * FROM daily_logs WHERE Date = '$selectedDate'";
$result = $conn->query($sql);

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

// Calculate daily totals
$dailyTotals = ["protein" => 0, "carbs" => 0, "fat" => 0, "calories" => 0];
foreach ($mealLogs as $mealFoods) {
    foreach ($mealFoods as $food) {
        $dailyTotals["protein"] += $food["protein"];
        $dailyTotals["carbs"] += $food["carbs"];
        $dailyTotals["fat"] += $food["fat"];
        $dailyTotals["calories"] += $food["calories"];
    }
}

// Calculate Previous and Next Date
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
    <div class="main-content">
        <header>
            <h1>Nutrition Dashboard</h1>
        </header>

        <section>
            <!-- Date Picker and Navigation -->
            <form id="dateForm" method="POST">
                <button type="submit" name="selected_date" value="<?= $prevDate ?>">Previous</button>
                <label for="selected_date">Select Date:</label>
                <input type="date" id="selected_date" name="selected_date" value="<?= $selectedDate ?>">
                <button type="submit" name="selected_date" value="<?= $nextDate ?>">Next</button>
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
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_meal_type" value="<?= htmlspecialchars($meal) ?>">
                                            <input type="hidden" name="delete_food_name" value="<?= htmlspecialchars($food['name']) ?>">
                                            <button class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button class="add-food-btn" onclick="openAddFoodForm('<?= htmlspecialchars($meal) ?>')">+</button>
            <?php endforeach; ?>

            <h3>Daily Totals</h3>
            <p>
                Calories: <?= $dailyTotals['calories'] ?> kcal, 
                Protein: <?= $dailyTotals['protein'] ?> g, 
                Carbs: <?= $dailyTotals['carbs'] ?> g, 
                Fat: <?= $dailyTotals['fat'] ?> g
            </p>
        </section>

        <div id="addFoodForm" style="display:none;">
            <h3>Add Food Item</h3>
            <form method="POST">
                <input type="hidden" name="meal_type" id="mealType">
                <label for="food_select">Choose Food:</label>
                <select name="food_select" id="foodSelect">
                    <?php foreach ($foodOptions as $food): ?>
                        <option value="<?= htmlspecialchars($food['FoodName']) ?>"><?= htmlspecialchars($food['FoodName']) ?></option>
                    <?php endforeach; ?>
                </select><br>
                <label for="grams">Grams:</label>
                <input type="number" name="grams" step="1" required><br>
                <button type="submit">Add</button>
                <button type="button" onclick="closeAddFoodForm()">Cancel</button>
            </form>
        </div>
    </div>

    <?php include 'Components/footer.php'; ?>

    <script>
        function openAddFoodForm(meal) {
            document.getElementById('addFoodForm').style.display = 'block';
            document.getElementById('mealType').value = meal;
        }

        function closeAddFoodForm() {
            document.getElementById('addFoodForm').style.display = 'none';
        }
    </script>
</body>
</html>
