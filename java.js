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
    document.getElementById("total-calories").textContent = `Total Daily Calories: ${totalCalories.toFixed(2)} kcal`;
    document.getElementById("protein-intake").textContent = `Protein: ${proteinIntake.toFixed(2)} grams`;
    document.getElementById("carb-intake").textContent = `Carbohydrates: ${carbIntake.toFixed(2)} grams`;
    document.getElementById("fat-intake").textContent = `Fats: ${fatIntake.toFixed(2)} grams`;
}
