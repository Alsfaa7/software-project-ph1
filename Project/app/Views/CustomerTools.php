<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Plan</title>
    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../Styles/n.css">
    <!-- Link to JavaScript file -->
    <script src="n.js"></script>
</head>
<body>
    <?php include 'Components/header.php'; ?>
    <div class="container">
        <h1>Personalized Nutrition Plan</h1>
        <form onsubmit="return validateForm() && calculateNutrition();">
            <!-- Gender Selection -->
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="female">Female</option>
                <option value="male">Male</option>
            </select>

            <!-- Age Input -->
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="0" required>

            <!-- Current Weight Input -->
            <label for="weight">Current Weight:</label>
            <input type="number" id="weight" name="weight" min="0" placeholder="Enter current weight" required>
            <select id="weight-unit" name="weight-unit">
                <option value="kg">kg</option>
                <option value="lb">lb</option>
            </select>

            <!-- Goal Weight Input -->
            <label for="goal-weight">Goal Weight:</label>
            <input type="number" id="goal-weight" name="goal-weight" min="0" placeholder="Enter goal weight" required>
            <select id="goal-weight-unit" name="goal-weight-unit">
                <option value="kg">kg</option>
                <option value="lb">lb</option>
            </select>

            <!-- Height Input -->
            <label for="height">Height:</label>
            <input type="number" id="height" name="height" min="0" placeholder="Enter height" required>
            <select id="height-unit" name="height-unit">
                <option value="cm">cm</option>
                <option value="ft">ft</option>
                <option value="in">in</option>
            </select>

            <!-- Activity Level -->
            <label for="activity">Activity Level:</label>
            <select id="activity" name="activity">
                <option value="1.2">Sedentary (little or no exercise)</option>
                <option value="1.375">Lightly active (light exercise)</option>
                <option value="1.55">Moderately active (moderate exercise)</option>
                <option value="1.725">Very active (hard exercise)</option>
                <option value="1.9">Extra active (very hard exercise or physical job)</option>
            </select>

            <!-- Goal Selection -->
            <label for="goal">Goal:</label>
            <select id="goal" name="goal">
                <option value="lose">Lose Weight</option>
                <option value="maintain">Maintain Weight</option>
                <option value="gain">Gain Weight</option>
            </select>

            <button type="submit">Calculate</button>
        </form>

        <!-- Display results here -->
        <div id="results" style="display:none;">
            <h2>Nutrition Plan Results</h2>
            <p id="total-calories"></p>
            <p id="protein-intake"></p>
            <p id="carb-intake"></p>
            <p id="fat-intake"></p>
        </div>
    </div>

    <script>
        function validateForm() {
            const age = parseInt(document.getElementById("age").value);
            const weight = parseFloat(document.getElementById("weight").value);
            const goalWeight = parseFloat(document.getElementById("goal-weight").value);
            const height = parseFloat(document.getElementById("height").value);

            if (age < 0 || weight < 0 || goalWeight < 0 || height < 0) {
                alert("Please enter positive values only.");
                return false;
            }
            return true;
        }

        function calculateNutrition() {
            const gender = document.getElementById("gender").value;
            const age = parseInt(document.getElementById("age").value);
            let weight = parseFloat(document.getElementById("weight").value);
            const weightUnit = document.getElementById("weight-unit").value;
            let height = parseFloat(document.getElementById("height").value);
            const heightUnit = document.getElementById("height-unit").value;
            const activityLevel = parseFloat(document.getElementById("activity").value);
            const goal = document.getElementById("goal").value;

            // Convert weight to kg if in lb
            if (weightUnit === "lb") {
                weight = weight * 0.453592;
            }

            // Convert height to cm if in ft or inches
            if (heightUnit === "ft") {
                height = height * 30.48;
            } else if (heightUnit === "in") {
                height = height * 2.54;
            }

            // Calculate BMR based on gender
            let bmr;
            if (gender === "female") {
                bmr = 655 + (9.6 * weight) + (1.8 * height) - (4.7 * age);
            } else {
                bmr = 66 + (13.7 * weight) + (5 * height) - (6.8 * age);
            }

            // Adjust BMR based on activity level
            let totalCalories = bmr * activityLevel;

            // Adjust for goal (500 calorie deficit for weight loss, surplus for gain)
            if (goal === "lose") {
                totalCalories -= 500;
            } else if (goal === "gain") {
                totalCalories += 500;
            }

            // Calculate macronutrient breakdown (40% protein, 40% carbs, 20% fats)
            const proteinCalories = totalCalories * 0.40;
            const carbCalories = totalCalories * 0.40;
            const fatCalories = totalCalories * 0.20;

            const proteinIntake = proteinCalories / 4; // 1g of protein = 4 calories
            const carbIntake = carbCalories / 4; // 1g of carb = 4 calories
            const fatIntake = fatCalories / 9; // 1g of fat = 9 calories

            // Display results
            document.getElementById("results").style.display = "block";
            document.getElementById("total-calories").textContent = `Total Daily Calories: ${totalCalories.toFixed(2)} kcal`;
            document.getElementById("protein-intake").textContent = `Protein: ${proteinIntake.toFixed(2)} grams`;
            document.getElementById("carb-intake").textContent = `Carbohydrates: ${carbIntake.toFixed(2)} grams`;
            document.getElementById("fat-intake").textContent = `Fats: ${fatIntake.toFixed(2)} grams`;

            // Prevent form submission
            return false;
        }
    </script>
    <?php include 'Components/footer.php'; ?>
</body>
</html>
