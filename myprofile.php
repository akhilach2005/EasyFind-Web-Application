<?php
session_start();
include("database.php");

$name="";
$RegistrationID=$_SESSION['username'];
$email="";
$department="";
$year="";
$section="";

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submitfb']))
{
     $message=mysqli_real_escape_string($conn,$_POST['message']);
     $insert="INSERT INTO Feedback(RegistrationId,Feedback_Message)
     VALUES
     ('$RegistrationID','$message')";
     $res=mysqli_query($conn,$insert);
     if(!$res)
     {
        echo "Insertion failed. ".mysqli_error($conn);
     }
     else{
            echo "alert('Feedback Submitted Successfully')";
     }
}



$select="SELECT * FROM Students WHERE RegistrationID='$RegistrationID'";
$result=mysqli_query($conn,$select);
if($result && mysqli_num_rows($result)>0)
{
    $row=mysqli_fetch_assoc($result);
    $name=$row['Student_Name'];
    $email=$row['email'];
    $department=$row['Department'];
    $year=$row['year_of_study'];
    $section=$row['section'];
    // echo "<h2 style='color: white; m'>".$name."</h2>";
}
// Query for found items
$found_query = "SELECT COUNT(*) AS total FROM founditems WHERE RegistrationID='$RegistrationID'";
$found_result = mysqli_query($conn, $found_query);
$found_row = mysqli_fetch_assoc($found_result);
$found_items = (int)$found_row['total'];

// Query for lost items
$lost_query = "SELECT COUNT(*) AS total FROM lostitems WHERE RegistrationID='$RegistrationID'";
$lost_result = mysqli_query($conn, $lost_query);
$lost_row = mysqli_fetch_assoc($lost_result);
$lost_items = (int)$lost_row['total'];

$claim_query="SELECT COUNT(*) AS total FROM claims WHERE finder_id='$RegistrationID' AND status='accepted'";
$claim_result = mysqli_query($conn, $claim_query);
$claim_row = mysqli_fetch_assoc($claim_result);
$claim_items = (int)$claim_row['total'];

// Trust Score Calculation
$total_reports = $found_items+$lost_items+$claim_items;
$trust_score = 0;


if ($total_reports > 0) {
    $trust_score = round((($claim_items + ($found_items * 0.5)) / $total_reports) * 100);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
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
            --dark-surface: #1e1e1e;
            --dark-surface-lighter: #2d2d2d;
            --light: #f8fafc;
            --gray: #94a3b8;
            --border: #2a2d36;
            --hover-color: #2d624f;
            --border-radius: 8px;
            --text-primary: rgba(255, 255, 255, 0.87);
            --text-secondary: rgba(255, 255, 255, 0.6);
        }
        
        body {
            background-color: var(--dark);
            color: var(--light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            transition: all 0.3s ease;
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

        .card {
            background-color: var(--dark-surface);
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
            color: #000;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            color: #fff;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border: none;
            color: #000;
        }
        
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 12px;
        }
        
        .badge-found {
            background-color: var(--secondary);
            color: #000;
        }
        
        .badge-lost {
            background-color: #cf6679;
            color: #000;
        }
        
        .progress {
            height: 8px;
            background-color: var(--dark-surface-lighter);
        }
        
        .progress-bar {
            background-color: var(--primary);
        }
        
        .profile-header {
            background-color: var(--dark-surface-lighter);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .profiles-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--primary);
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.5s ease;
        }
        
        .profiles-img:hover {
            transform: scale(1.05);
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
            background-color: var(--dark-surface-lighter);
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            background-color: rgba(187, 134, 252, 0.1);
        }
        
        .item-card {
            cursor: pointer;
            overflow: hidden;
        }
        
        .item-card img {
            transition: transform 0.5s ease;
            height: 180px;
            object-fit: cover;
        }
        
        .item-card:hover img {
            transform: scale(1.1);
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .nav-tabs {
            border-bottom: 1px solid var(--dark-surface-lighter);
        }
        
        .nav-tabs .nav-link {
            color: var(--text-secondary);
            border: none;
            border-bottom: 2px solid transparent;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .nav-tabs .nav-link.active {
            background-color: transparent;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        
        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .floating-action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }
        
        .animate-fadeIn {
            animation: fadeIn 1s;
        }
        
        .animate-slideInUp {
            animation: slideInUp 0.7s;
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.7s;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.7s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            to {
                left: 100%;
            }
        }
        
        .notification-dot {
            width: 8px;
            height: 8px;
            background-color: var(--secondary);
            border-radius: 50%;
            display: inline-block;
            margin-left: 5px;
        }
        
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%233700b3" fill-opacity="0.2" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-repeat: no-repeat;
        }

       
        #feedbackContainer .entry,
        #questionContainer .entry {
    background: var(--dark-surface-lighter);
    border-left: 4px solid #0d6efd;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    color: var(--light);
}

#questionContainer .entry {
    border-left-color: #28a745;
}

        form textarea {
            resize: none;
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

    <!-- Main Content -->
    <div class="container py-4" style="margin-top: 6rem;">
        <!-- Profile Header -->
        <div class="profile-header mb-4 animate-fadeIn">
            <div class="wave"></div>
            <div class="row">
                <div class="col-md-2 col-sm-4 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Image" class="profiles-img mb-3">
                </div>
                <div class="col-md-7 col-sm-8">
                    <h2 class="mb-1" style="padding-bottom:8px;"><?php echo $name;?></h2>
                    <p class="text-secondary mb-2"><i class="fas fa-map-marker-alt me-1"></i> Registration ID : <?php echo $RegistrationID;?></p>
                    <p class="text-secondary mb-3"><i class="fa-solid fa-building-columns me-2"></i>Department: <?php echo $department;?></p>
                    <p class="text-secondary mb-3"><i class="fa-solid fa-envelope"></i>  Email: <?php echo $email;?></p>
                    <p class="text-secondary mb-3"><i class="fa-solid fa-graduation-cap"></i>Year: <?php echo $year;?></p>
                    <p class="text-secondary mb-3"><i class="fa-solid fa-layer-group me-2"></i>Section: <?php echo $section;?></p>
                   
                </div>
                <div class="col-md-3 mt-3 mt-md-0">
                    <div class="reputation-box p-3 bg-dark rounded-3 text-center shimmer">
                        <h6 class="text-secondary">Trust Score</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <h2 class="mb-0 me-2"><?php echo $trust_score;?> % </h2>
                            <i class="fas fa-shield-alt text-primary fs-3"></i>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-secondary mt-2 d-block">Based on <?php echo $total_reports;?>  interactions</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row animate-slideInUp">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <i class="fas fa-search fs-2 text-primary mb-3"></i>
                    <h3><?php echo $lost_items;?> </h3>
                    <p class="text-secondary mb-0">Items Reported Lost</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <i class="fas fa-hands-helping fs-2 text-danger mb-3"></i>
                    <h3><?php echo $found_items;?> </h3>
                    <p class="text-secondary mb-0">Items Found</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <i class="fas fa-check-circle fs-2 text-success mb-3"></i>
                    <h3><?php echo $claim_items;?></h3>
                    <p class="text-secondary mb-0">Successfully Returned</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <i class="fas fa-medal fs-2 text-warning mb-3"></i>
                    <h3>
                        <?php 
                        include("database.php");
                        $badges="SELECT Badges FROM Rewards WHERE RegistrationId='$RegistrationID'";      
                          $badgesRes=mysqli_query($conn,$badges);
                        if($badgesRes && mysqli_num_rows($badgesRes)>0)
                        {
                            $badgeRow=mysqli_fetch_assoc($badgesRes);
                            $badge=$badgeRow['Badges'];
                            echo $badge;    
                        }
                        else {
                            echo "No Badges"; 
                        }
                        ?>
                        </h3>
                    <p class="text-secondary mb-0">Good Samaritan Badges</p>
                </div>
            </div>
        </div>

    <!-- Tabs -->
<ul class="nav nav-tabs" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab" aria-controls="items" aria-selected="true">
            <i class="fas fa-list me-1"></i> My Items
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback" type="button" role="tab" aria-controls="feedback" aria-selected="false">
            <i class="fas fa-history me-1"></i> Feedback
        </button>
    </li>
</ul>

<!-- Tab content -->
<div class="tab-content mt-4" id="profileTabsContent">

    <!-- My Items Tab -->
    <div class="tab-pane fade show active" id="items" role="tabpanel" aria-labelledby="items-tab">
        <div class="row">
            <?php
            include("database.php");
            if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['ItemID']))
            {
                $itemId=$_POST['ItemID'];
                $delete="DELETE FROM lostitems WHERE ItemID='$itemId'";
                if(!mysqli_query($conn,$delete))
                {
                    echo "Deletion failed!".mysqli_error($conn);
                }
            }
           
            $RegistrationID=$_SESSION['username']; // Assuming this is set
            $lost_query="SELECT * FROM lostitems WHERE RegistrationID='$RegistrationID'";
            $lost_result=mysqli_query($conn, $lost_query);
            $delay = 0.1;

            while($row = mysqli_fetch_assoc($lost_result)) {
                $itemName = $row['ItemName'];
                $description = $row['Description'];
                $location = $row['Location_Lost'];
                $date = date("F j, Y", strtotime($row['Date_Lost']));
                $status = 'Lost';
                $badgeClass = 'badge-lost';
                $ItemId=$row['ItemID'];
                echo '
                <div class="col-lg-4 col-md-6 mb-4 animate-slideInLeft" style="animation-delay: '.$delay.'s;">
                    <div class="card item-card h-100">
                        <div class="position-relative">
                            <span class="badge '.$badgeClass.' position-absolute top-0 end-0 m-2">'.$status.'</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="color:rgb(233, 164, 217)">'.htmlspecialchars($itemName).'</h5>
                            <p class="card-text" style="color:beige">Lost at '.htmlspecialchars($location).' on '.$date.'.</p>
                        </div>
                           <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                            
                            <form method="POST" action="" onsubmit="return confirm(\'Are you sure you want to delete this item?\');" style="display:inline;">
                                <input type="hidden" name="ItemID" value="'.$ItemId.'">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>';
                $delay += 0.1;
            }
            ?>
        </div>
    </div>

    <div class="tab-pane fade" id="feedback" role="tabpanel" aria-labelledby="feedback-tab">
        <div class="mb-4">
            <h4>Feedback</h4>
            <p class="text-secondary">Submit your feedback and help us improve!</p>
        </div>

        <form id="feedbackForm" class="mb-4" method="post" action="">
            <div class="mb-3">
                <label for="message" class="form-label">Your Feedback</label>
                <textarea class="form-control" name="message" id="message" rows="3" placeholder="Write your feedback here..." required></textarea>
            </div>
            <input type="submit" name="submitfb" class="btn btn-primary" id="submitfb" value="Submit Feedback">
        </form>
    </div>

</div> <!-- tab-content -->

        <!-- JavaScript for the feedback and questions functionality -->
    
         <script src="notifications.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>