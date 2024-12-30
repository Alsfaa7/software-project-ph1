<?php 
include '../includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
    $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : null;
    $calories = isset($_POST['calories']) ? filter_var($_POST['calories'], FILTER_VALIDATE_INT) : null;
    $protein = isset($_POST['protein']) ? filter_var($_POST['protein'], FILTER_VALIDATE_FLOAT) : null;
    $carbs = isset($_POST['carbs']) ? filter_var($_POST['carbs'], FILTER_VALIDATE_FLOAT) : null;
    $fats = isset($_POST['fats']) ? filter_var($_POST['fats'], FILTER_VALIDATE_FLOAT) : null;
    $message = "";

    // Add food item
    if (isset($_POST['add_food']) && $name && $category && $calories !== false && $protein !== false && $carbs !== false && $fats !== false) {
        $stmt = $conn->prepare("INSERT INTO food_items (FoodName, Category, Calories, Protein, Carbohydrates, Fats) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdddd", $name, $category, $calories, $protein, $carbs, $fats);
        $stmt->execute();
        $message = $stmt->affected_rows > 0 ? "New food item added successfully." : "Error: Could not add the food item.";
        $stmt->close();
    }

    // Update food item
    if (isset($_POST['update_food']) && isset($_POST['food_id']) && $name && $category && $calories !== false && $protein !== false && $carbs !== false && $fats !== false) {
        $food_id = filter_var($_POST['food_id'], FILTER_VALIDATE_INT);
        $stmt = $conn->prepare("UPDATE food_items SET FoodName=?, Category=?, Calories=?, Protein=?, Carbohydrates=?, Fats=? WHERE FoodID=?");
        $stmt->bind_param("ssddddi", $name, $category, $calories, $protein, $carbs, $fats, $food_id);
        $stmt->execute();
        $message = $stmt->affected_rows > 0 ? "Food item updated successfully." : "Error: Could not update the food item.";
        $stmt->close();
    }

    // Delete food item
    if (isset($_POST['delete_food']) && isset($_POST['food_id'])) {
        $food_id = filter_var($_POST['food_id'], FILTER_VALIDATE_INT);
        $stmt = $conn->prepare("DELETE FROM food_items WHERE FoodID=?");
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $message = $stmt->affected_rows > 0 ? "Food item deleted successfully." : "Error: Could not delete the food item.";
        $stmt->close();
    }
}

// Fetch all food items
$food_sql = "SELECT * FROM food_items";
$food_result = $conn->query($food_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            cursor: pointer;
        }

        .btn {
            background-color: #007BFF;
            color: white;
            border: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .actions button {
            margin-right: 5px;
        }

        .message {
            color: green;
            text-align: center;
        }
    </style>    
</head>
<body>
<?php include 'Components/adminnavbar.php'; ?>

<div class="container">
    <h1>Manage Food Items</h1>
    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <!-- Add/Update Form -->
    <form method="POST" action="foodadminedit.php">
        <input type="hidden" name="food_id" id="food_id">
        <label for="name">Food Name</label>
        <input type="text" name="name" id="name" required>

        <label for="category">Category</label>
        <select name="category" id="category" required>
            <option value="">Select Category</option>
            <option value="Fruit">Fruit</option>
            <option value="Vegetable">Vegetable</option>
            <option value="Meat">Meat</option>
            <option value="Dairy">Dairy</option>
            <option value="Grain">Grain</option>
        </select>

        <label for="calories">Calories</label>
        <input type="number" name="calories" id="calories" required>

        <label for="protein">Protein (g)</label>
        <input type="number" min="0" step="0.1" name="protein" id="protein" required>

        <label for="carbs">Carbohydrates (g)</label>
        <input type="number" min="0" step="0.1" name="carbs" id="carbs" required>

        <label for="fats">Fats (g)</label>
        <input type="number" min="0" step="0.1" name="fats" id="fats" required>

        <button type="submit" name="add_food" class="btn btn-add">Add Food Item</button>
        <button type="submit" name="update_food" class="btn btn-update">Update Food Item</button>
    </form>

    <!-- Table Display -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Food Name</th>
                <th>Category</th>
                <th>Calories</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Fats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $food_result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['FoodID'] ?></td>
                <td><?= $row['FoodName'] ?></td>
                <td><?= $row['Category'] ?></td>
                <td><?= $row['Calories'] ?></td>
                <td><?= $row['Protein'] ?></td>
                <td><?= $row['Carbohydrates'] ?></td>
                <td><?= $row['Fats'] ?></td>
                <td class="actions">
                    <button onclick="editFood(<?= $row['FoodID'] ?>, '<?= $row['FoodName'] ?>', '<?= $row['Category'] ?>', <?= $row['Calories'] ?>, <?= $row['Protein'] ?>, <?= $row['Carbohydrates'] ?>, <?= $row['Fats'] ?>)">Edit</button>
                    <form method="POST" action="foodadminedit.php" style="display:inline;">
                        <input type="hidden" name="food_id" value="<?= $row['FoodID'] ?>">
                        <button type="submit" name="delete_food" class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function editFood(id, name, category, calories, protein, carbs, fats) {
    document.getElementById('food_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('category').value = category;
    document.getElementById('calories').value = calories;
    document.getElementById('protein').value = protein;
    document.getElementById('carbs').value = carbs;
    document.getElementById('fats').value = fats;
}
</script>

</body>
</html>

<?php $conn->close(); ?>
