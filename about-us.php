<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About GSA | Elite Sport Academy</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #27ae60;
            --white: #ffffff;
            --light: #f4f7f6;
            --grey: #bdc3c7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--primary);
            line-height: 1.6;
        }

        /* Navigation */
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

        nav .logo { color: var(--white); font-size: 24px; font-weight: bold; letter-spacing: 1px; }
        nav .nav-links a { color: var(--white); text-decoration: none; margin-left: 20px; font-weight: 500; transition: 0.3s; }
        nav .nav-links a:hover { color: var(--accent); }

        /* Hero Section */
        .about-hero {
            height: 50vh;
            background: linear-gradient(rgba(44, 62, 80, 0.7), rgba(44, 62, 80, 0.7)), 
                        url('https://images.unsplash.com/photo-1517649763962-0c623066013b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--white);
        }

        .about-hero h1 { font-size: 50px; margin: 0; text-transform: uppercase; }
        .about-hero p { font-size: 20px; max-width: 600px; }

        .container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }

        /* Description Section */
        .description-box {
            background: var(--white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .accent-title { color: var(--accent); font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 10px; }

        /* Feature Grid */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }

        .feature-item {
            text-align: center;
            padding: 30px;
            background: var(--white);
            border-radius: 15px;
            transition: 0.3s;
        }

        .feature-item:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .feature-item i { font-size: 40px; color: var(--accent); margin-bottom: 20px; }
        .feature-item h3 { margin-bottom: 10px; }

        /* Programs Section */
        .programs {
            background: var(--primary);
            color: var(--white);
            padding: 80px 20px;
            text-align: center;
            border-radius: 20px;
            margin-bottom: 80px;
        }

        .program-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .program-tag {
            background: rgba(255,255,255,0.1);
            padding: 10px 25px;
            border-radius: 50px;
            border: 1px solid var(--accent);
            font-weight: bold;
        }

        /* Gallery Section - PRESERVING YOUR HOVER EFFECTS */
        .gallery-title { text-align: center; margin-bottom: 40px; }
        .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        .box { background: white; padding: 10px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; overflow: hidden; }
        .box img { width: 100%; height: 180px; object-fit: cover; border-radius: 8px; transition: all 0.5s ease-in-out; cursor: pointer; }
        .box:hover img { height: 250px; object-fit: contain; transform: scale(1.1); background-color: #f9f9f9; }
        .box p { margin-top: 8px; font-weight: bold; }

        /* --- MODAL STYLES --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .modal-toolbar { position: absolute; top: 20px; display: flex; gap: 10px; z-index: 10001; }
        .modal-btn { background: var(--accent); color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .close-modal { position: absolute; top: 15px; right: 25px; color: white; font-size: 40px; cursor: pointer; }
        .modal-img-container { width: 80%; height: 70%; display: flex; justify-content: center; align-items: center; overflow: hidden; }
        #fullImage { max-width: 100%; max-height: 100%; transition: transform 0.3s ease-out; }
        .arrow { position: absolute; top: 50%; color: white; font-size: 60px; cursor: pointer; padding: 20px; user-select: none; }
        .prev-arrow { left: 10px; }
        .next-arrow { right: 10px; }

        footer { background: var(--primary); color: var(--white); text-align: center; padding: 30px; margin-top: 50px; }

        @media (max-width: 768px) {
            nav { padding: 15px 20px; }
            .about-hero h1 { font-size: 32px; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">GSA ACADEMY</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="register.php">Registration</a>
            <a href="about-us.php" style="color: var(--accent);">About GSA</a>
        </div>
    </nav>

    <header class="about-hero">
        <h1>GSA Sport Academy</h1>
        <p>Developing the Next Generation of Ethiopian Champions through Science and Discipline.</p>
    </header>

    <main class="container">
        
        <!-- Academy Description -->
        <section class="description-box">
            <span class="accent-title">Who We Are</span>
            <h2>More Than Just An Academy</h2>
            <p>
                Founded in 2024 in Debre Tabor, <strong>GSA Sport Academy</strong> is Ethiopia's premier institution for elite athletic development. We don't just train players; we build professionals. Our approach combines rigorous physical conditioning with state-of-the-art performance data analytics.
            </p>
            <p>
                Whether it's on the football pitch, the running track, or the basketball court, GSA provides a structured environment where young talent is nurtured under the guidance of certified experts and former professional athletes.
            </p>
        </section>

        <!-- Academy Features -->
        <div class="gallery-title">
            <span class="accent-title">Why Choose GSA?</span>
            <h2>Our Core Features</h2>
        </div>
        <section class="feature-grid">

            <div class="feature-item">
                <i class="fas fa-user-tie"></i>
                <h3>Elite Coaching</h3>
                <p>Our coaches hold international certifications and focus on technical mastery and tactical intelligence.</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-graduation-cap"></i>
                <h3>Holistic Growth</h3>
                <p>We balance sports with education, ensuring our graduates are leaders both on and off the field.</p>
            </div>
        </section>

        <!-- Programs -->
        <section class="programs">
            <h2>Our Specialized Programs</h2>
            <p>We offer dedicated training wings for various disciplines:</p>
            <div class="program-list">
                 <span class="program-tag">teakwondo</span>
                 <span class="program-tag">gym</span>
                 <span class="program-tag">Youth Football League</span>
                 <span class="program-tag">Elite Athletics (Running)</span>          
                 <span class="program-tag">Strength & Conditioning</span>
                 <span class="program-tag">Professional Scouting</span>
             </div>
        </section>

        <!-- Interactive Gallery -->
        <div class="gallery-title">
            <span class="accent-title">Life at GSA</span>
            <h2>Academy Gallery</h2>
        </div>
        <div class="gallery" id="imageGallery">
            <div class="box"><img src="image/aa.jpg" alt="Training Session"><p>Field Training</p></div>
            <div class="box"><img src="image/bb.jpg" alt="Gym Workout"><p>Indoor Gym</p></div>
            <div class="box"><img src="image/cc.jpg" alt="Team Meeting"><p>Strategy Session</p></div>
            <div class="box"><img src="image/dd.jpg" alt="Award Ceremony"><p>Victory</p></div>
            <div class="box"><img src="image/ee.jpg" alt="Medical Check"><p>Recovery</p></div>
            <div class="box"><img src="image/ff.jpg" alt="Youth Program"><p>Junior Stars</p></div>
        </div>
    </main>

    <!-- THE MODAL (Interactive Viewer) -->
    <div id="imageModal" class="modal">
        <span class="close-modal" id="closeBtn">&times;</span>
        <div class="modal-toolbar">
            <button class="modal-btn" onclick="applyZoom(0.2)">Zoom In (+)</button>
            <button class="modal-btn" onclick="applyZoom(-0.2)">Zoom Out (-)</button>
            <button class="modal-btn" onclick="applyRotate(-90)">Rotate Left ⟲</button>
            <button class="modal-btn" onclick="applyRotate(90)">Rotate Right ⟳</button>
            <button class="modal-btn" onclick="resetTransforms()">Reset</button>
        </div>
        <span class="arrow prev-arrow" id="prevBtn">&#10094;</span>
        <div class="modal-img-container"><img src="" id="fullImage"></div>
        <span class="arrow next-arrow" id="nextBtn">&#10095;</span>
    </div>

    <footer>
        <p>&copy; 2024 GSA Sport Academy Management System. All Rights Reserved.</p>
    </footer>

    <script>
        const galleryImages = document.querySelectorAll('#imageGallery img');
        const modal = document.getElementById('imageModal');
        const fullImage = document.getElementById('fullImage');
        const closeBtn = document.getElementById('closeBtn');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        let currentIndex = 0;
        let zoomScale = 1;
        let currentRotation = 0;

        galleryImages.forEach((img, index) => {
            img.addEventListener('click', () => {
                currentIndex = index;
                openModal(img.src);
            });
        });

        function openModal(src) {
            modal.style.display = 'flex';
            fullImage.src = src;
            resetTransforms();
        }

        function closeModal() { modal.style.display = 'none'; }

        function applyZoom(amount) { zoomScale += amount; updateImageTransform(); }
        function applyRotate(degree) { currentRotation += degree; updateImageTransform(); }
        function resetTransforms() { zoomScale = 1; currentRotation = 0; updateImageTransform(); }
        function updateImageTransform() { fullImage.style.transform = `scale(${zoomScale}) rotate(${currentRotation}deg)`; }

        function showNext() {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            fullImage.src = galleryImages[currentIndex].src;
            resetTransforms();
        }

        function showPrev() {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            fullImage.src = galleryImages[currentIndex].src;
            resetTransforms();
        }

        closeBtn.onclick = closeModal;
        nextBtn.onclick = showNext;
        prevBtn.onclick = showPrev;
        modal.onclick = (e) => { if (e.target === modal || e.target.className === 'modal-img-container') closeModal(); };

        document.addEventListener('keydown', (e) => {
            if (modal.style.display === 'flex') {
                if (e.key === "ArrowRight") showNext();
                if (e.key === "ArrowLeft") showPrev();
                if (e.key === "Escape") closeModal();
            }
        });
    </script>
</body>
</html>