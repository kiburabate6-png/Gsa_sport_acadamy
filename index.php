<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSA Sport Academy | Official Home</title>
    <style>
        /* CSS Variables for Consistent Branding */
        :root {
            --primary: #2c3e50;
            --accent: #27ae60;
            --white: #ffffff;
            --light: #f4f7f6;
            --login-blue: #3498db;
        }

        /* Smooth scrolling for the whole page */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--primary);
        }

        /* Navigation Bar */
        nav {
            background: var(--primary);
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav .logo {
            color: var(--white);
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav .nav-links a {
            color: var(--white);
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: 0.3s;
        }

        nav .nav-links a:hover {
            color: var(--accent);
        }

        /* Hero Section */
        .hero {
            height: 80vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url("image/img.png");
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--white);
        }

        .hero h1 {
            font-size: 56px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .cta-buttons .btn {
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            margin: 10px;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-member {
            background: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-member:hover {
            background: var(--white);
            color: var(--primary);
            transform: translateY(-3px);
        }

        .btn-login {
            background: var(--login-blue);
            color: var(--white);
        }

        .btn-login:hover {
            background: #2980b9;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 80px 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .feature-box {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .feature-box h3 {
            color: var(--accent);
            margin-bottom: 15px;
        }

        /* Contact Section */
        .contact-container {
            padding: 80px 50px;
            background-color: var(--white);
        }

        .contact-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .contact-title h2 {
            font-size: 36px;
            color: var(--primary);
        }

        .contact-title .underline {
            width: 80px;
            height: 4px;
            background: var(--accent);
            margin: 10px auto;
        }

        .contact-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .contact-info h3 {
            color: var(--accent);
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .info-item span {
            font-size: 18px;
            margin-left: 15px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
        }

        .contact-form button {
            background: var(--accent);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .contact-form button:hover {
            background: #219150;
        }

        /* Footer */
        footer {
            background: var(--primary);
            color: var(--white);
            text-align: center;
            padding: 30px;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .contact-wrapper { grid-template-columns: 1fr; }
            .hero h1 { font-size: 36px; }
            nav { padding: 15px 20px; }
        }
        .feature{
            text-align:center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <div class="logo">GSA ACADEMY</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="register.php" style="color: var(--accent);">Register Now</a>
            <a href="login.php">Login</a> 
            <a href="about-us.php">about us</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>GSA SPORT ACADEMY</h1>
        <p>Advanced Management System for Elite Athletes. Track performance, manage memberships, and reach your peak potential.</p>
        <div class="cta-buttons">
            <a href="register.php" class="btn btn-member">Join as Sportsman</a>
            <a href="login.php" class="btn btn-login">Login to Account</a>
        </div>
    </section>
      <div  class=feature>
          <h2>features</h2></div>
    <section id="features" class="features">
        <div class="feature-box">
            <h3>Membership Control</h3>
            <p>Precise tracking of start and expiry dates with automatic time-based validation.</p>
        </div>
        <div class="feature-box">
            <h3>Live Announcements</h3>
            <p>Admins are instantly notified when memberships expire to ensure seamless renewals.</p>
        </div>
        <div class="feature-box">
            <h3>Secure Records</h3>
            <p>Every athlete's registration is protected with encrypted passwords and duplicate checks.</p>
        </div>
        <div class="feature-box">
            <h3>Real-time Tracking</h3>
            <p>Monitor status 24/7 with our live dashboard and automatic expiry alerts.</p>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="contact-container">
        <div class="contact-title">
            <h2>Contact Us</h2>
            <div class="underline"></div>
            <p>Have questions? We are here to help you.</p>
        </div>

        <div class="contact-wrapper">
            <!-- Left Side: Contact Info -->
            <div class="contact-info">
                <h3>Get in Touch</h3>
                <div class="info-item">
                    <strong>📍 Location:</strong>
                    <span>debre tabor, Ethiopia</span>
                </div>
                <div class="info-item">
                    <strong>📞 Phone:</strong>
                    <span>+251 943 28 49 67</span>
                </div>
                <div class="info-item">
                    <strong>✉️ Email:</strong>
                    <span>info@gsasports.com</span>
                </div>
                <div class="info-item">
                    <strong>🕒 Hours:</strong>
                    <span>Mon - Sat: 9:00 AM - 11:30 AM</span>
                </div>
            </div>

            <!-- Right Side: Contact Form -->
            <div class="contact-form">
                <form action="send_contact.php" method="POST">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> GSA Sport Academy Management System. All Rights Reserved.</p>
    </footer>

</body>
</html>