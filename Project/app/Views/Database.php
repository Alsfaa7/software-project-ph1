<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #5cb85c;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            color: #fff;
            background-color: #5cb85c;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #5cb85c;
            color: #fff;
        }
    </style>
</head>
<body>
<?php include 'Components/header.php'; ?>

    <div class="container">
        <h2>Nutrition Database</h2>
        
        <!-- Form to Add Product and Calorie Info -->
        <form id="productForm">
            <label for="product">Product Name:</label>
            <input type="text" id="product" required placeholder="Enter product name">
            
            <label for="calories">Calories (kcal):</label>
            <input type="number" id="calories" required placeholder="Enter calorie amount">
            
            <button type="submit">Add Product</button>
        </form>
        
        <!-- Table to Display Products and Calories -->
        <table id="nutritionTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Calories (kcal)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Initial rows added here -->
            </tbody>
        </table>
    </div>

    <script>
        // Initial product data
        const initialProducts = [
            { product: "Apple", calories: 52 },
            { product: "Banana", calories: 89 },
            { product: "Chicken Breast", calories: 165 },
            { product: "Broccoli", calories: 55 },
            { product: "Almonds", calories: 579 }
        ];

        // Function to add a product to the table
        function addProductToTable(product, calories) {
            const tableBody = document.getElementById('nutritionTable').getElementsByTagName('tbody')[0];
            const newRow = tableBody.insertRow();

            const productCell = newRow.insertCell(0);
            const caloriesCell = newRow.insertCell(1);

            productCell.textContent = product;
            caloriesCell.textContent = calories;
        }

        // Load initial products when the page loads
        window.onload = function() {
            initialProducts.forEach(item => addProductToTable(item.product, item.calories));
        };

        // Handle form submission to add new products
        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const product = document.getElementById('product').value;
            const calories = document.getElementById('calories').value;

            if (product && calories) {
                addProductToTable(product, calories);

                // Clear form fields after submission
                document.getElementById('product').value = '';
                document.getElementById('calories').value = '';
            }
        });
    </script>
        <?php include 'Components/footer.php'; ?>

</body>
</html>