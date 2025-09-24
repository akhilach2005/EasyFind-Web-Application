<?php 
session_start();
include("database.php");
$regID=$_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyFind - Lost and Found Made Simple</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    /* Custom Variables */
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
      --form-bg:#16213e;

    }

    body {
      background-color: var(--dark);
      color: var(--light);
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }

    /* Animation Keyframes */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes floatAnimation {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    @keyframes pulseGlow {
      0% { box-shadow: 0 0 5px rgba(247, 37, 133, 0.5); }
      50% { box-shadow: 0 0 20px rgba(247, 37, 133, 0.8); }
      100% { box-shadow: 0 0 5px rgba(247, 37, 133, 0.5); }
    }

    @keyframes gradientFlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Global Styles */
    .section-title {
      position: relative;
      margin-bottom: 2rem;
      font-weight: 700;
      text-align: center;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, var(--secondary), var(--primary));
    }

    .btn-custom-primary {
      background: var(--secondary);
      color: white;
      border: none;
      border-radius: 50px;
      padding: 0.8rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-custom-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(247, 37, 133, 0.5);
      animation: pulseGlow 1.5s infinite;
    }

    .btn-custom-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.7s ease;
    }

    .btn-custom-primary:hover::before {
      left: 100%;
    }

    .btn-custom-secondary {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: 2px solid var(--primary-light);
      border-radius: 50px;
      padding: 0.8rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      backdrop-filter: blur(5px);
    }

    .btn-custom-secondary:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(76, 201, 240, 0.3);
    }

    /* Navbar Styles */
    .navbar {
      background: rgba(10, 11, 14, 0.9);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      padding: 0.5rem 0;
      background: rgba(10, 11, 14, 0.98);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .brand-icon {
      font-size: 2rem;
      color: var(--secondary);
      margin-right: 0.5rem;
      animation: floatAnimation 3s infinite ease-in-out;
    }

    .brand-text {
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(90deg, var(--secondary), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .nav-link {
      color: var(--light) !important;
      margin: 0 0.5rem;
      position: relative;
      font-weight: 500;
      padding: 0.5rem 1rem !important;
      transition: all 0.3s ease;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .nav-link:hover {
      color: white !important;
    }

    .nav-link:hover::after {
      width: 80%;
    }

    .navbar-toggler {
      border: none;
      color: white;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    /* User Navigation Icons */
    .user-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .nav-icon-btn {
      background: transparent;
      border: none;
      color: var(--light);
      font-size: 1.2rem;
      padding: 0.5rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-icon-btn:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    .notification-badge {
      position: absolute;
      top: 0;
      right: 0;
      background: var(--secondary);
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: bold;
      animation: pulseGlow 2s infinite;
    }

    .profile-img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--secondary);
    }

    /* Dropdown Menu */
    .dropdown-menu {
      background: var(--dark-accent);
      border: 1px solid var(--border);
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      min-width: 280px;
      margin-top: 1rem;
    }

    .dropdown-item {
      color: var(--light);
      padding: 0.8rem 1rem;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .dropdown-item:hover {
      background: rgba(255, 255, 255, 0.05);
      color: var(--primary-light);
      border-left: 3px solid var(--secondary);
    }

    .dropdown-header {
      border-bottom: 1px solid var(--border);
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .dropdown-title {
      font-weight: 600;
      color: var(--light);
    }

    .dropdown-action {
      color: var(--primary-light);
      font-size: 0.8rem;
      text-decoration: none;
    }

    .dropdown-item-icon {
      background: rgba(67, 97, 238, 0.1);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-light);
    }

    .dropdown-item-content {
      flex: 1;
    }

    .dropdown-item-title {
      font-size: 0.9rem;
      margin-bottom: 0.2rem;
    }

    .dropdown-item-subtitle {
      font-size: 0.8rem;
      color: var(--gray);
    }
    .custom-toggler .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
        }

        .custom-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }


    /* Hero Section */
    .hero {
      position: relative;
      height: 100vh;
      background: linear-gradient(135deg, #0a0e17, #161a26, #1e1a30);
      background-size: 300% 300%;
      animation: gradientFlow 15s ease infinite;
      display: flex;
      align-items: center;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('/api/placeholder/1920/1080');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 70% 30%, rgba(114, 9, 183, 0.5), transparent 70%);
      pointer-events: none;
    }

    .hero-content {
      position: relative;
      z-index: 10;
    }

    .hero-subtitle {
      color: var(--primary-light);
      text-transform: uppercase;
      letter-spacing: 3px;
      margin-bottom: 1rem;
      animation: fadeInUp 0.8s ease forwards;
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
      background: linear-gradient(90deg, #fff, var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: fadeInUp 0.8s ease forwards 0.3s;
    }

    .hero-description {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 2rem;
      max-width: 600px;
      line-height: 1.6;
      animation: fadeInUp 0.8s ease forwards 0.6s;
    }

    .cta-buttons {
      display: flex;
      gap: 1rem;
      animation: fadeInUp 0.8s ease forwards 0.9s;
    }

   

    /* Footer */
    .footer {
      background: var(--darker);
      padding: 4rem 0 2rem;
    }

    .footer-title {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 0.8rem;
    }

    .footer-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 2px;
      background: var(--primary-light);
    }

    .footer-links {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-links li {
      margin-bottom: 0.8rem;
    }

    .footer-links a {
      color: var(--gray);
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .footer-links a:hover {
      color: var(--primary-light);
      transform: translateX(5px);
    }

    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .social-link {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .social-link:hover {
      background: var(--primary);
      transform: translateY(-3px) rotate(8deg);
      box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
    }

    .copyright {
      text-align: center;
      padding-top: 2rem;
      margin-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: var(--gray);
    }

    /* AOS Animation Fallbacks */
    .fade-up {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s ease;
    }

    .fade-up.active {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .hero-title {
        font-size: 2.8rem;
      }
    }

    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.2rem;
      }
      
      .hero-content {
        text-align: center;
      }
      
      .hero-description {
        margin-left: auto;
        margin-right: auto;
      }
      
      .cta-buttons {
        justify-content: center;
      }
      
      .social-links {
        justify-content: center;
      }

      .navbar-collapse {
        background: rgba(10, 11, 14, 0.95);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
      }
    }

    @media (max-width: 576px) {
      .hero-title {
        font-size: 1.8rem;
      }
      
      .cta-buttons {
        flex-direction: column;
        width: 100%;
      }
      
      .btn-custom-primary, .btn-custom-secondary {
        width: 100%;
        text-align: center;
      }
      
      .section-title {
        font-size: 1.8rem;
      }
    }
    .faqs-section {
  background-color: #212529;
  color: #ffffff;
  padding: 3rem 0;
}

.faqs-container {
  max-width: 800px;
  margin: 0 auto;
}

.faqs-section h2 {
  font-size: 2.5rem;
  font-weight: 600;
  margin-bottom: 2rem;
  color: #f8f9fa;
}

.faq-item {
  background-color: #2c2f33;
  border-radius: 10px;
  margin-bottom: 1rem;
  padding: 1rem 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.faq-item:hover {
  background-color: #343a40;
}

.faq-question {
  font-size: 1.1rem;
  font-weight: 500;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.faq-question i {
  transition: transform 0.3s ease;
}

.faq-answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease, opacity 0.4s ease;
  opacity: 0;
  margin-top: 0.5rem;
  font-size: 0.95rem;
  line-height: 1.5;
  color: #e0e0e0;
}

.faq-item.active .faq-answer {
  max-height: 500px; /* Enough to accommodate the text */
  opacity: 1;
}

.faq-item.active .faq-question i {
  transform: rotate(180deg);
}
 
/* Testimonial Section */
.testimonial-section {
  padding: 5rem 0;
  background: linear-gradient(to bottom, var(--dark), var(--darker));
}

/* Scrollable Row */
.testimonial-scroll-container {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  gap: 1rem;
  padding-bottom: 1rem;
  scroll-behavior: smooth;
}

.testimonial-scroll-container::-webkit-scrollbar {
  height: 8px;
}

.testimonial-scroll-container::-webkit-scrollbar-thumb {
  background-color: var(--secondary);
  border-radius: 4px;
}

.testimonial-scroll-container::-webkit-scrollbar-track {
  background-color: transparent;
}

/* Fixed width for each card */
.testimonial-card-wrapper {
  flex: 0 0 auto;
  width: 250px;
}

/* Card Styles */
.testimonial-card {
  background: var(--dark-card);
  border-radius: 12px;
  border: 1px solid var(--border);
  padding: 1.5rem;
  margin-bottom: 1rem;
  transition: all 0.3s ease;
  height: 100%;
  text-align: center;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  border-color: var(--primary-light);
}

/* Image */
.testimonial-img {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--secondary);
  margin-bottom: 1rem;
}

/* Text */
.testimonial-text {
  font-style: italic;
  color: var(--gray);
  margin-bottom: 1rem;
}

/* Name */
.testimonial-name {
  font-weight: 600;
  margin-bottom: 0.2rem;
  color: #fff;
}

/* Role */
.testimonial-role {
  font-size: 0.9rem;
  color: var(--gray);
}

        /* Notification Styles */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            display: none;
        }

        .notification-item {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .notification-icon {
            margin-right: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #666;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #999;
            margin-top: 3px;
        }

        .unread {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .unread .notification-title {
            color: #0d6efd;
        }

        .dropdown-action {
            font-size: 0.8rem;
            color: #0d6efd;
            text-decoration: none;
        }

        .dropdown-action:hover {
            text-decoration: underline;
        }
/* Claim Modal Styling */
.modal-content {
            background-color: var(--form-bg);
            color: var(--text-color);
            border-radius: var(--border-radius);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
        }

        .modal-title {
            color: var(--light-text);
            font-weight: 600;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }


  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand" href="#">
        <span class="brand-icon">🔍</span>
        <span class="brand-text">EasyFind</span>
      </a>
  
      <!-- Toggler for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fas fa-bars text-white"></i>
      </button>
  
      <!-- Navigation links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="lostitems.php"><i class="fas fa-search"></i> Lost Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="founditems.php"><i class="fas fa-box-open"></i> Found Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="rewards.php"><i class="fas fa-gift"></i> Rewards</a>
          </li>
        </ul>
      </div>
  
  
 <div class="d-flex align-items-center ms-auto user-nav">
            <!-- Notifications -->
            <div class="dropdown me-2">
              <button class="nav-icon-btn position-relative" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationCount"></span>
              </button>
              <div class="dropdown-menu dropdown-menu-end notification-menu" style="width: 300px; max-height: 400px; overflow-y: auto;">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                  <span class="dropdown-title">Notifications</span>
                  <a href="#" class="dropdown-action" onclick="markAllAsRead()"></a>
                </div>
                <div id="notificationList">
                    <!-- Notifications will be loaded here -->
                </div>
              </div>
            </div>
  
   
        <!-- Profile -->
        <div class="dropdown">

          <button class="nav-icon-btn" data-bs-toggle="dropdown">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="User" class="profile-img">
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <a href="myprofile.php" class="dropdown-item">
              <div class="dropdown-item-icon"><i class="fas fa-user"></i></div>
              <div class="dropdown-item-content">
                <div class="dropdown-item-title">My Profile</div>
                <div class="dropdown-item-subtitle">View and edit your profile</div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="index.php" class="dropdown-item">
              <div class="dropdown-item-icon"><i class="fas fa-sign-out-alt"></i></div>
              <div class="dropdown-item-content">
                <div class="dropdown-item-title">Sign Out</div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
  
  

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="hero-content">
            <p class="hero-subtitle">Lost & Found Made Simple</p>
            <h1 class="hero-title">Reunite with Your Belongings Effortlessly</h1>
            <p class="hero-description">
              EasyFind connects people who have lost items with those who have found them. Our platform makes the process of recovering lost items simple, secure, and rewarding.
            </p>
            <div class="cta-buttons">
              <a href="lostform.php" class="btn btn-custom-primary">
                <i class="fas fa-search me-2"></i> Find Lost Items
              </a>
              <a href="reportform.php" class="btn btn-custom-secondary">
                <i class="fas fa-plus me-2"></i> Report Found Item
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>



  

  <!-- Testimonials Section -->
  <section class="testimonial-section">
    <div class="container">
      <h2 class="text-center">What Others Are Saying</h2>
      <div class="testimonial-scroll-container">
  <?php

    include("database.php");
    $fb="SELECT * FROM Feedback where RegistrationID!='$regID'";
    $fbRes=mysqli_query($conn,$fb);
    if($fbRes && mysqli_num_rows($fbRes)>0)
    {
      $name="";
        while($row=mysqli_fetch_assoc($fbRes))
        {
          $feedbackMessage=$row['Feedback_Message'];
          $rID=$row['RegistrationId'];

          $select="SELECT Student_Name FROM Students where RegistrationID='$rID'";
          $sRes=mysqli_query($conn,$select);
          if($sRes && mysqli_num_rows($sRes))
          {
            $row1=mysqli_fetch_assoc($sRes);
            $name=$row1['Student_Name'];
          }
   echo' 
        <div class="testimonial-card-wrapper">
          <div class="testimonial-card">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="testimonial-img" alt="User Avatar">

            <p class="testimonial-text">'.$feedbackMessage.'</p>
            <h5 class="testimonial-name">'.$name.'</h5>
            <small class="testimonial-role">Student</small>
          </div>
        </div>';
        }

      }
      ?>
      </div>
    </div>
  </section>


  <!-- FAQs Section -->
  <section class="faqs-section py-5 bg-dark text-white">
    <div class="container faqs-container">
      <h2 class="text-center mb-5">Frequently Asked Questions</h2>

      <div class="faq-item">
        <div class="faq-question d-flex justify-content-between align-items-center">
          <span>How do I report a lost item?</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          Simply click the "Report Lost Item" button and fill out the form with details and images.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question d-flex justify-content-between align-items-center">
          <span>Can I offer a reward?</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          Yes! When reporting, you can mention a reward for the person who finds your item.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question d-flex justify-content-between align-items-center">
          <span>What happens after I find my item?</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          You can mark the item as recovered, and optionally leave feedback or a thank you message.
        </div>
      </div>
    </div>
  </section>

  
<!-- Claim Review Modal -->
<div class="modal fade" id="claimReviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Review Claim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Claimer's Information:</h6>
                    <p id="claimerInfo"></p>
                </div>
                <div class="mb-3">
                    <h6>Description:</h6>
                    <p id="claimDescription"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="rejectClaimBtn">Reject</button>
                <button type="button" class="btn btn-success" id="acceptClaimBtn">Accept</button>
            </div>
        </div>
    </div>
</div>

  <!-- Footer -->
  <footer class="bg-black text-white text-center py-4">
    <div class="container">
      <p class="mb-1">&copy; 2025 EasyFind. All rights reserved.</p>
      <small>Developed by SRM AP Students • Lost & Found Simplified</small>
    </div>
  </footer>

  <!-- JS -->
  
  <script>
    
// Function to open claim modal
function openClaimModal(itemId) {
    updateDebug('Opening modal for item: ' + itemId);
    
    // Clear previous debug info
    document.getElementById('debugText').textContent = '';
    
    // Reset form and errors
    const form = document.getElementById('claimForm');
    const errorDiv = document.getElementById('errorMessage');
    form.reset();
    errorDiv.style.display = 'none';
    
    // Set item ID
    document.getElementById('itemId').value = itemId;
    updateDebug('Item ID set to: ' + itemId);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('claimModal'));
    modal.show();
}

  
    document.querySelectorAll('.faq-question').forEach((question) => {
      question.addEventListener('click', () => {
        const item = question.parentElement;
        item.classList.toggle('active');
      });
    });
  </script>
<script src="notifications.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>