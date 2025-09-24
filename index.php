<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyFind</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
     

.hero {
    width: 100%;
    height: 100vh;
    background-image: linear-gradient(rgba(5, 5, 7, 0.7), rgba(10, 11, 14, 0.8)), url('https://cdn5.vectorstock.com/i/1000x1000/61/54/cartoon-color-characters-people-and-searching-vector-36036154.jpg');
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    text-align: center;
    color: var(--light);
    padding-top: 60px; /* Space for fixed navbar */
}

.hero .content {
    position: relative;
    z-index: 1;
    background: var(--dark-card);
    padding: 30px;
    border-radius: 10px;
    max-width: 500px;
    text-align: center;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
    border: 1px solid var(--border);
}

.hero .content h1 {
    font-size: 32px;
    margin-bottom: 15px;
    color: var(--primary-light);
}

.hero .content p {
    font-size: 18px;
    margin-bottom: 20px;
    color: var(--light);
}

.hero .content button {
    background-color: var(--primary);
    color: var(--light);
    border: none;
    padding: 12px 24px;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.hero .content button:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
}

/* Findings Section */
.findings {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 20px;
    background-color: var(--dark);
}

.total {
    display: flex;
    justify-content: space-around;
    gap: 30px;
    max-width: 1100px;
    width: 100%;
}

.matter {
    background-color: var(--dark-card);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    text-align: center;
    flex: 1;
    transition: 0.3s ease-in-out;
    border: 1px solid var(--border);
}

.matter:nth-child(1) {
    background: linear-gradient(135deg, var(--dark-card), var(--primary-dark));
}

.matter:nth-child(2) {
    background: linear-gradient(135deg, var(--dark-card), var(--secondary));
}

.matter:nth-child(3) {
    background: linear-gradient(135deg, var(--dark-card), var(--accent));
}

.matter p {
    font-size: 18px;
    font-weight: bold;
    color: var(--light);
    margin: 0;
}

.matter:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
}

/* About Section */
#about {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    background-color: var(--dark-accent);
}

.about-container {
    display: flex;
    align-items: center;
    max-width: 1100px;
    width: 100%;
    gap: 50px;
}

.about-text {
    flex: 1;
    text-align: left;
}

.about-text h2 {
    color: var(--primary-light);
    font-size: 36px;
    margin-bottom: 20px;
}

.about-text p {
    color: var(--light);
    font-size: 18px;
    line-height: 1.6;
}

.about-img {
    flex: 1;
}

.about-img img {
    width: 100%;
    border-radius: 10px;
    max-width: 500px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    border: 1px solid var(--border);
}

/* Services Section */
.services {
    padding: 80px 5%;
    text-align: center;
    background-color: var(--dark);
}

.services h2 {
    font-size: 36px;
    margin-bottom: 15px;
    color: var(--secondary);
}

.services p {
    font-size: 18px;
    color: var(--light);
    margin-bottom: 50px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 30px;
}

.service-card {
    flex: 1;
    min-width: 300px;
    background: var(--dark-card);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    border: 1px solid var(--border);
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.4);
}

.service-card .icon img {
    width: 70px;
    height: 70px;
    margin-bottom: 20px;
    filter: invert(1); /* Makes black icons white */
}

.service-card h3 {
    font-size: 24px;
    color: var(--primary-light);
    margin-bottom: 15px;
}

.service-card p {
    font-size: 16px;
    color: var(--gray);
    margin-bottom: 20px;
}

.service-card a {
    display: inline-block;
    text-decoration: none;
    background: var(--accent);
    color: var(--light);
    padding: 10px 20px;
    border-radius: 5px;
    transition: 0.3s;
    font-weight: bold;
}

.service-card a:hover {
    background: var(--secondary);
    transform: scale(1.05);
}

/* Gallery Section */
.gallery {
    padding: 80px 0;
    text-align: center;
    background-color: var(--dark-accent);
}

.gallery h2 {
    font-size: 36px;
    margin-bottom: 40px;
    color: var(--primary-light);
}

.gallery-wrapper {
    overflow-x: auto;
    white-space: wrap;
    padding: 20px;
    scroll-snap-type: x mandatory;
}

.gallery-container {
    display: flex;
    gap: 30px;
    flex-wrap: nowrap;
    margin: auto;
    max-width: 1200px;
    padding: 10px;
}

.gallery-container .card {
    flex: 0 0 auto;
    width: 300px;
    background-color: var(--dark-card);
    border-radius: 12px;
    scroll-snap-align: center;
    overflow: hidden;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
}

.gallery-container .card:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
}

.gallery-container img {
    height: 250px;
    width: 100%;
    object-fit: cover;
    border-bottom: 1px solid var(--border);
}

.card-body {
    padding: 15px;
}

.card-text {
    color: var(--light);
    font-size: 16px;
    margin: 0;
}

.gallery-wrapper::-webkit-scrollbar {
    height: 8px;
    background-color: var(--dark);
}

.gallery-wrapper::-webkit-scrollbar-thumb {
    background-color: var(--primary);
    border-radius: 4px;
}

.gallery-wrapper::-webkit-scrollbar-track {
    background-color: var(--dark-card);
    border-radius: 4px;
}

/* Footer */
.footer {
    width: 100%;
    background-color: var(--darker);
    color: var(--light);
    display: flex;
    justify-content: space-around;
    align-items: flex-start;
    padding: 50px 20px;
    font-size: 16px;
    border-top: 1px solid var(--border);
}

.footer div {
    width: 300px;
    margin-bottom: 20px;
}

.footer h3 {
    color: var(--primary-light);
    margin-bottom: 20px;
    font-size: 20px;
    position: relative;
}

.footer h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 40px;
    height: 3px;
    background-color: var(--secondary);
}

.footer p {
    color: var(--gray);
    line-height: 1.6;
    margin-bottom: 10px;
}

.footer a {
    color: var(--light);
    text-decoration: none;
    margin-right: 15px;
    font-size: 20px;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: var(--primary-light);
}

.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.social-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: var(--dark-accent);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.social-icons a:hover {
    background-color: var(--primary);
    transform: translateY(-5px);
}

/* Media Queries */
@media (max-width: 992px) {
    .about-container {
        flex-direction: column;
        text-align: center;
    }
    
    .about-text {
        margin-bottom: 30px;
    }
    
    .about-img img {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .row {
        flex-direction: column;
        align-items: center;
    }
    
    .service-card {
        width: 100%;
        max-width: 400px;
    }
    
    .total {
        flex-direction: column;
    }
    
    .matter {
        width: 100%;
        max-width: 350px;
    }
    
    .footer {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .footer div {
        width: 100%;
        max-width: 400px;
    }
    
    .footer h3::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .social-icons {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .hero .content {
        width: 90%;
    }
    
    .navbar .container {
        flex-direction: column;
    }
    
    .navbar-brand {
        margin-bottom: 15px;
    }
}

:root {
      --primary: #4361ee;
      --primary-light: #4cc9f0;
      --primary-dark: #3a0ca3;
      --secondary: #f72585;
      --accent: #7209b7;
      --success: #4ade80;
      --warning: #fbbf24;
      --dark: #0a0b0e;
      --darker: #050507;
      --dark-card: #121418;
      --dark-accent: #1e2028;
      --light: #f8fafc;
      --gray: #94a3b8;
      --border: #2a2d36;
    }

    /* Global Styles */
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      scroll-behavior: smooth;
      background-color: var(--darker);
      color: var(--light);
      font-family: 'Arial', sans-serif;
    }

    /* Navbar Styling */
    .navbar {
      background-color: var(--dark);
      padding: 15px 0;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
    }

    .navbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Brand/Logo Styling */
    .navbar-brand {
      flex: 1;
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .brand-icon {
      font-size: 24px;
      margin-right: 12px;
      color: var(--secondary);
      animation: floatAnimation 3s infinite ease-in-out;
    }

    .brand-text {
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(90deg, var(--secondary), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }


    /* Navigation Links Styling */
    .navbar-nav {
      flex: 2;
      display: flex;
      justify-content: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-item {
      margin: 0 25px; /* Increased spacing between nav items */
    }

    .nav-link {
      color: var(--light);
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      transition: color 0.3s ease;
      display: flex;
      align-items: center;
    }

    .nav-link i {
      margin-right: 8px;
      font-size: 18px;
    }

    .nav-link:hover {
      color: var(--primary-light);
    }

    /* Login Button Styling */
    .login-button {
      flex: 1;
      display: flex;
      justify-content: flex-end;
    }

    .login-btn {
      background-color: var(--accent); /* Changed login button color */
      color: var(--light);
      padding: 10px 16px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .login-btn i {
      margin-right: 8px;
    }

    .login-btn:hover {
      background-color: var(--secondary);
    }

    /* Animation for brand icon */
    @keyframes floatAnimation {
      0% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
      100% { transform: translateY(0); }
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">         
        <div class="container">           
          <!-- Brand -->           
          <a class="navbar-brand" href="#">
            <span class="brand-icon">🔍</span>
            <span class="brand-text">EasyFind</span>
          </a>
                       
          
          <!-- Navigation Links -->           
          <ul class="navbar-nav">             
            <li class="nav-item">               
              <a class="nav-link" href="#home"><i class="fas fa-home"></i> Home</a>             
            </li>             
            <li class="nav-item">               
              <a class="nav-link" href="#about"><i class="fas fa-info-circle"></i> About</a>             
            </li>             
            <li class="nav-item">               
              <a class="nav-link" href="#services"><i class="fas fa-cogs"></i> Services</a>             
            </li>             
            <li class="nav-item">               
              <a class="nav-link" href="#gallery"><i class="fas fa-images"></i> Gallery</a>             
            </li>
          </ul>
          
          <!-- Login Button -->             
          <div class="login-button">                 
            <a href="login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> Log in</a>               
          </div>
        </div>       
      </nav>
    
      <!-- Page content would go here -->
      <!-- <div style="height: 2000px; padding-top: 100px;">
       
        <div class="container">
          <h1>EasyFind Website</h1>
          <p>Scroll down to see the fixed navbar in action.</p>
        </div> -->
      

    <!-- Hero Section -->
    <section class="hero">
        <div class="content">
            <h1>Welcome to EasyFind</h1>
            <p>EasyFind is a platform designed to help students and staff recover lost items on campuses.</p>
            <!-- <button id="startButton" onclick="window.location.href='login.html'">Start</button> -->
        </div>
    </section>
    <section class="findings">
        <div class="total">
            <div class="matter" style="background-color:rgb(211, 203, 236)">
                <p>📅 100+ <br>
                    Found Report Timings</p>
            </div>
            <div class="matter" style="background-color:rgb(253, 232, 237)">
                <p>📦 340+ <br>
                    Found Items</p>
            </div>
            <div class="matter" style="background-color:rgb(155, 176, 154)">
                <p>📥 2k+ <br>
                    Login Downloads</p>
            </div>
        </div>
    </section>
    <!-- About Section (Scroll Down) -->
    <section id="about">
        <div class="about-container">
            <div class="about-text">
                <h2>About EasyFind</h2>
                <p>EasyFind is dedicated to reuniting lost items with their owners, 
                   providing a simple and reliable way to recover lost possessions. Our mission 
                   is to make lost-and-found processes efficient, transparent, and easily accessible.</p>
            </div>
            <div class="about-img">
                <img src="https://images.pexels.com/photos/38519/macbook-laptop-ipad-apple-38519.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200" alt="Laptop image">
            </div>
        </div>
    </section>
    <section class="services" id="services">
        <div class="container">
            <h2>Our Services</h2>
            <p>At EasyFind, we provide a range of services to assist individuals in recovering lost items and reuniting them with their rightful owners.</p>
            
            <div class="row">
                <!-- Item Reporting -->
                <div class="service-card">
                    <div class="icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/929/929556.png" alt="Gear Icon">
                    </div>
                    <h3>Item Reporting</h3>
                    <p>We offer a streamlined process for reporting lost items and provide a central platform for owners to search for and claim their belongings.</p>
                    <a href="#">READ MORE</a>
                </div>

                <!-- Search Assistance -->
                <div class="service-card">
                    <div class="icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/486/486928.png" alt="Wrench Icon">
                    </div>
                    <h3>Search Assistance</h3>
                    <p>Our team is dedicated to assisting individuals in searching for their lost items, offering support and guidance throughout the process.</p>
                    <a href="#">READ MORE</a>
                </div>

                <!-- Lost & Found Events -->
                <div class="service-card">
                    <div class="icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/1087/1087923.png" alt="Monitor Icon">
                    </div>
                    <h3>EasyFind Events</h3>
                    <p>We frequently organize community events to promote awareness of lost and found items, facilitating the dissemination of information to aid in recovery efforts.</p>
                    <a href="#">READ MORE</a>
                </div>
            </div>
        </div>
    </section>
    <section class="gallery" id="gallery">
        <div class="container">
            <h2>Gallery</h2>
    
            
    
            <!-- Scrollable Image Gallery -->
            <div class="gallery-wrapper">
                <div class="gallery-container">
                    <div class="card">
                        <img src="https://images.pexels.com/photos/205421/pexels-photo-205421.jpeg" class="card-img-top" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Found Item</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="https://images.pexels.com/photos/14721/pexels-photo.jpg?cs=srgb&dl=pexels-ingo-14721.jpg&fm=jpg" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Lost Item</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="https://cdn.khadims.com/image/tr:e-sharpen-01,h-822,w-940,cm-pad_resize/data/khadims/11oct2023/30700276190_1.jpg" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Found Item</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="https://d25g9z9s77rn4i.cloudfront.net/uploads/product/450/1734095167_0d305023c06ae4d43ca7.jpg" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Lost Item</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="https://media.istockphoto.com/id/1497591487/photo/credit-cards-stacked-on-white-background.jpg?s=612x612&w=0&k=20&c=2i5DMpRT_-HqV4rS8mvLA1pkP059GkWar8HEqyXcDv4=" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Found Item</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="https://media.istockphoto.com/id/180756294/photo/wallet.jpg?s=612x612&w=0&k=20&c=sc6I6KsEbiv9Y4BtKji8w5rBYono2X63-ipfhYk6Ytg=" alt="Found Item">
                        <div class="card-body">
                            <p class="card-text">Found Item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
    </section>
    <div class="footer">
        <div>
            <h3>Overview</h3>
            <p>Contact us for assistance with lost and found items or inquiries about our services.</p>
        </div>
        <div>
            <h3>Contact</h3>
            <p>📍EasyFind, SR Block</p>
            <p>📞 999 673 984</p>
            <p>📧 support@yourdomain.com</p>
        </div>
        <div>
            <h3>Services</h3>
            <p>Search Assistance</p>
            <p>EasyFind Events</p>
            <p>Item Identification</p>
        </div>
        <div>
            <h3>Follow Us</h3>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>
</body>
</html>
