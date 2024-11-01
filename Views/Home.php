<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Log</title>
    <link rel="stylesheet" href="../Styles/Home.css">
</head>
<body>
    <?php include 'Components/header.php'; ?>
    <div class="hero" style="background-image: url('../Images/basil_blurred.jpg'); background-size: cover; background-position: center; height: 600px; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff;">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>The World's Largest Verified Nutrition Database</h1>
            <p>Access over 1 million food items and track your daily nutrition with precision.</p>
            <button class="cta-btn" onclick="window.location.href='SignUp.php'">SignUp Now</button>
        </div>
    </div>

    <div class="container">
        <div class="statistics">
            <div>
                <span>989,447</span>
                Grocery Items
            </div>
            <div>
                <span>201,536</span>
                Restaurant Items
            </div>
            <div>
                <span>10,448</span>
                Common Foods
            </div>
        </div>

        <section class="features">
            <h2>Why Choose Us?</h2>
            <div class="feature-cards">
                <div class="card">
                    <h3>Accurate Data</h3>
                    <p>Our database is updated frequently to provide the most accurate nutrition information.</p>
                </div>
                <div class="card">
                    <h3>Track Your Progress</h3>
                    <p>Log your meals, set daily goals, and monitor your weight loss journey effortlessly.</p>
                </div>
                <div class="card">
                    <h3>Mobile Access</h3>
                    <p>Download our app for quick and easy logging on the go, available on iOS and Android.</p>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <h2>What Our Users Say</h2>
            <div class="testimonial-cards">
                <div class="testimonial">
                    <p>"This app transformed the way I track my diet. Highly recommend it to anyone serious about nutrition!"</p>
                    <span>- Alex D.</span>
                </div>
                <div class="testimonial">
                    <p>"The accuracy and ease of use are unmatched. It’s become an essential part of my daily routine."</p>
                    <span>- Maria P.</span>
                </div>
            </div>
        </section>
    </div>
    <?php include 'Components/footer.php'; ?>
</body>
</html>
