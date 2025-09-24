<?php
session_start();
include("database.php");
$username=$_SESSION['username'];
$name="";
$points=0;
$badges="";
$display = "SELECT S.Student_Name, R.Points, R.Badges
            FROM Students AS S
            INNER JOIN Rewards AS R
            ON S.RegistrationID = R.RegistrationId
            WHERE S.RegistrationID = '$username'";
$res = mysqli_query($conn, $display);
if ($res && mysqli_num_rows($res) > 0) {
    $rowRes = mysqli_fetch_assoc($res);
    $name = $rowRes['Student_Name'];
    $points = $rowRes['Points'];
    $badges = $rowRes['Badges'];
} else {
    echo "No data found for the given username.";
}
$percent = min(100, ($points / 50) * 100);
$toNext = max(0, 51 - $points);

$helperProgress = min(100, max(0, ($points / 20) * 100));

// Guardian badge (21 - 50 points)
if ($points >= 21 && $points <= 49) {
    $guardianProgress = (($points - 21) / (50 - 21)) * 100;
} elseif ($points >= 50) {
    $guardianProgress = 100;
} else {
    $guardianProgress = 0;
}

// Hero badge (51+ points)
if ($points >= 51) {
    $heroProgress = min(100, (($points - 51) / (100 - 51)) * 100);
} else {
    $heroProgress = 0;
}
$rank=0;
$query1="SELECT 
DENSE_RANK() OVER (ORDER BY Points DESC, Name ASC) AS Rank,
RegistrationId
FROM Rewards
ORDER BY Points DESC,Name ASC";
$queryResult=mysqli_query($conn,$query1);
if($queryResult &&  mysqli_num_rows($queryResult)>0){
   while( $queryRes=mysqli_fetch_assoc($queryResult)){
   if($queryRes['RegistrationId']==$username)
   {
    $rank=$queryRes['Rank'];
   } 
}
}
else{
echo "No details found for ".htmlspecialchars($username);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Find - Your Rewards</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #9c27b0;
            --secondary: #6c3483;
            --success: #2ecc71;
            --dark: #121212;
            --darker: #0a0a0a;
            --light-dark: #1e1e1e;
            --text-light: #e0e0e0;
            --accent: #ff6b6b;
        }
        
         body {
            background: linear-gradient(135deg, var(--dark) 0%, var(--darker) 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding-top:80px;
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
      --hover-color: #2d624f;
      --border-radius:8px;
    }

    body {
      background-color: var(--dark);
      color: var(--light);
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      padding-top:50px;
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
            background: var(--light-dark);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .card-title {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        
        .text-primary {
            color: var(--primary) ;
        }
        
        .text-success {
            color: var(--success) ;
        }
        
        .badge-card {
            background: rgba(30, 30, 30, 0.7);
            transition: all 0.3s ease;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .badge-card:hover {
            transform: scale(1.05);
            background: rgba(40, 40, 40, 0.9);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .progress {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            box-shadow: 0 0 10px rgba(156, 39, 176, 0.5);
        }
        
        .display-4 {
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary), #3498db);
            -webkit-background-clip: text;
            background-clip: text;
            color:white;
        }
        
        .table {
            color: var(--text-light);
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .table-striped tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .table thead th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            color: var(--primary);
        }
        
        .home-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            font-size: 24px;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        .glow {
            box-shadow: 0 0 20px rgba(156, 39, 176, 0.5);
        }
        
        /* User profile styling */
        .user-profile {
            padding: 30px 0;
            background: rgba(20, 20, 20, 0.7);
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .profiles-img floating {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid blueviolet;
            padding: 3px;
            background: var(--darker);
            margin-bottom: 15px;
            transition: all 0.3s ease;
            object-fit: cover;
        }
        
        .leaderboard-rank {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        /* Blurry lights in background */
        .bg-light {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: -1;
            opacity: 0.4;
        }
        
        .bg-light-1 {
            top: 10%;
            left: 5%;
            width: 300px;
            height: 300px;
            background: rgba(156, 39, 176, 0.5);
        }
        
        .bg-light-2 {
            bottom: 10%;
            right: 5%;
            width: 250px;
            height: 250px;
            background: rgba(52, 152, 219, 0.5);
        }
        
        /* Badge animations */
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Achievements section */
        .achievement-item {
            background: rgba(30, 30, 30, 0.7);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .achievement-item:hover {
            transform: translateX(5px);
            background: rgba(40, 40, 40, 0.9);
        }
        
        .achievement-date {
            font-size: 0.8rem;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Background Lights -->
    <!-- <div class="bg-light bg-light-1"></div>
    <div class="bg-light bg-light-2"></div> -->
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
    <div class="container mt-5 mb-5 animate_animated animate_fadeIn">
        <h1 class="text-center mb-4 text-primary animate_animated animate_bounceIn">🏆 Your Rewards Dashboard</h1>
        <p class="text-center mb-5 animate_animated animatefadeIn animate_delay-1s">Earn rewards by helping others find their lost items. Be a hero on campus!</p>
        
        <!-- User Profile Summary -->
        <div class="user-profile shadow-sm animate_animated animate_fadeInUp">
            <div class="container text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" width="150" height="150" alt="User Profile" class="profiles-img floating">
                <h3 class="fw-bold mt-2"><?php echo $name; ?> </h3>
                
                <div class="leaderboard-rank"><?php echo $rank;?> </div>
            </div>
        </div>
<!-- User Points -->
        <div class="card mb-4 shadow-sm animate_animated animatefadeInUp animate_delay-1s">
    <div class="card-body text-center">
        <h5 class="card-title"><i class="fas fa-star-half-alt me-2"></i>Your Current Points</h5>
        <h1 class="display-4 fw-bold"><?php echo $points; ?> Points</h1>

        <?php
            $percent = min(100, ($points / 50) * 100);
            if ($points < 21) {
                $toNext = 21 - $points;
                $nextBadge = "Guardian";
            } else if ($points < 51) {
                $toNext = 51 - $points;
                $nextBadge = "Campus Hero";
            } else {
                $toNext = 0;
                $nextBadge = "You've unlocked all badges!";
            }
        ?>

        <div class="progress mt-3" style="height: 20px;">
            <div class="progress-bar bg-success glow" style="width: <?php echo $percent; ?>%;" role="progressbar" aria-valuenow="<?php echo $points; ?>" aria-valuemin="0" aria-valuemax="50">
                <?php echo round($percent); ?>% to <?php echo is_string($nextBadge) ? $nextBadge : ''; ?>
            </div>
        </div>

        <div class="mt-3">
            <?php if ($toNext > 0): ?>
                <small class="text-warning">Just <?php echo $toNext; ?> more point<?php echo $toNext > 1 ? 's' : ''; ?> to reach <?php echo $nextBadge; ?>!</small>
            <?php else: ?>
                <small class="text-success">Congratulations! You've unlocked all badges 🎉</small>
            <?php endif; ?>
        </div>
    </div>
</div>

        
        <!-- Badge Levels -->
        <div class="card mb-4 shadow-sm animate_animated animatefadeInUp animate_delay-2s">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-medal me-2"></i>Badge Levels</h5>
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <div class="badge-card p-3 text-center h-100">
                            <!-- <img src="/api/placeholder/128/128" alt="Helper" width="60" class="mb-2"> -->
                            <h6 class="fw-bold text-white">👤 Helper</h6>
                            <p class="mb-0" style="color:white">0 - 20 Points</p>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: <?= $helperProgress ?>%;"></div>
                            </div>
                            <small class="<?php 
                        if($points==0) echo 'text-danger'; // Locked - Red
                        elseif($points<20) echo 'text-primary'; // In Progress - Blue
                        elseif($points>=20) echo 'text-success'; // Completed - Green
                    ?>">
                              
                              <?php 
                            if($points==0)
                            echo "Locked";
                            elseif($points<20)
                            echo "In Progess";
                            elseif($points>=20)
                            echo "Completed";     
                            ?></small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="badge-card p-3 text-center h-100 pulse">
                            <!-- <img src="/api/placeholder/128/128" alt="Guardian" width="60" class="mb-2"> -->
                            <h6 class="fw-bold text-white">🛡 Guardian</h6>
                            <p class="mb-0" style="color:white">21 - 50 Points</p>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: <?= $guardianProgress ?>%;"></div>
                            </div>
                            <small class="<?php 
                        if($points<=20) echo 'text-danger'; // Locked - Red
                        elseif($points>20 && $points<50) echo 'text-primary'; // In Progress - Blue
                        elseif($points>=50) echo 'text-success'; // Completed - Green
                    ?>"> <?php 
                            if($points<=20)
                            echo "Locked";
                            elseif($points>20 && $points<50)
                            echo "In Progess";
                            elseif($points>=50)
                            echo "Completed";
                            ?></small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="badge-card p-3 text-center h-100">
                            <!-- <img src="/api/placeholder/128/128" alt="Hero" width="60" class="mb-2"> -->
                            <h6 class="fw-bold text-white">🏆 Campus Hero</h6>
                            <p class="mb-0" style="color:white">51+ Points</p>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: <?= $Progress ?>%;"></div>
                            </div>
                            <small class="<?php 
                        if($points<=50) echo 'text-danger'; // Locked - Red
                        elseif($points>=51) echo 'text-primary'; // In Progress - Blue
                    ?>"><?php 
                            if($points<=50)
                            echo "Locked";
                            elseif($points>=51)
                            echo "In Progess";
                
                            
                            ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Achievements -->
        <div class="card mb-4 shadow-sm animate_animated animatefadeInUp animate_delay-3s">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-trophy me-2"></i>Recent Achievements</h5>
                <div class='achievement-item'>
                        <?php 
               
                          $report="SELECT *FROM founditems WHERE RegistrationID='$username' ORDER BY Date_Found DESC LIMIT 2";
                          $reportRes=mysqli_query($conn,$report);
                          if($reportRes && mysqli_num_rows($reportRes)>0)
                          {
                            echo "
                            <div class='d-flex justify-content-between'>
                                <h6 class='mb-1' style='color: rgb(229, 162, 17)'>Reported Lost Item</h6>
                                
                            </div>";
                            
                            while($reportRow=mysqli_fetch_assoc($reportRes))
                            {
                             echo "<div class='achievement-item'>
                    <div class='d-flex justify-content-between'>
                        <h6 class='mb-1' style='color: rgb(118, 212, 95)'>Found" . htmlspecialchars($reportRow['ItemName']) . "</h6>
                        <span class='badge bg-primary'>+10 pts</span>
                    </div>
                            
               <p class='mb-1' style='color:white'>" . " Reported " . htmlspecialchars($reportRow['ItemName']) ." found at ". htmlspecialchars($reportRow['Location_Found']) . "</p>
                    <small class='achievement-date' style='color: beige'>
                                <i class='far fa-clock me-1'></i>" . htmlspecialchars(date("F j, Y", strtotime($reportRow['Date_Found']))) . "
                            </small>
                        </div>";
                            }

                          }
                          else{
                            echo "<h4 style='color:white;'>No results found</h4>";
                          }

                     ?>

                </div>   
            </div>
        </div>
        
        <!-- How to Earn Points -->
        <div class="card mb-4 shadow-sm animate_animated animatefadeInUp animate_delay-3s">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-lightbulb me-2"></i>How to Earn Points</h5>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="p-3 badge-card mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-search fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="color:bisque">Report a found item</h6>
                                    <h5 class="mb-0 text-primary">10 Points</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
         
        <!-- Leaderboard -->
        <div class="card mb-4 shadow-sm animate_animated animatefadeInUp animate_delay-4s">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-crown me-2"></i>Top Contributors</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th>Badge</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                 
                        <?php 
                        include("database.php");
                        $query="SELECT 
                            DENSE_RANK() OVER (ORDER BY Points DESC, Name ASC) AS Rank,
                            Name,
                            Badges,
                            Points
                        FROM Rewards
                        ORDER BY Points DESC, Name ASC;";
                        $queryResult=mysqli_query($conn,$query);
                        if($queryResult && mysqli_num_rows($queryResult)>0)
                        {

                            while($rowResult=mysqli_fetch_assoc($queryResult)){

                    
                                switch ($rowResult['Badges']) {
                                    case 'Helper':
                                        $badgeStyle = "linear-gradient(90deg, #ff6b6b, #ee5253);";
                                        break;
                                    case 'Guardian':
                                        $badgeStyle = "linear-gradient(90deg,rgb(51, 66, 40),rgb(192, 90, 168));";
                                        break;
                                    case 'Campus Hero':
                                        $badgeStyle = "linear-gradient(90deg, #f9d423, #ff4e50);";
                                        break;
                                    default:
                                        $badgeStyle = "grey"; // fallback for unknown badges
                                }
                               
                                  
                            echo "<tr class'animate_animated animate_fadeIn'>
                            <td>".$rowResult['Rank']."</td>
                            <td>".htmlspecialchars($rowResult['Name'])."</td>
                            <td> <span class='badge' style='background: $badgeStyle'>".htmlspecialchars($rowResult['Badges'])."</span></td>
                            <td>".htmlspecialchars($rowResult['Points'])."</td>
                            </tr>";
                            }
                            }
                            else{
                                echo "<h4 style='color:white;'>No results found</h4>";
                            }
                            
                        
                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>

        

    <!-- Home Button -->
    <a href="home.html" class="home-btn animate_animated animatebounceIn animate_delay-4s">
        <i class="fas fa-home"></i>
    </a>

    <!-- Particles Canvas -->
    <canvas class="particles"></canvas>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animated counter for points
        document.addEventListener('DOMContentLoaded', function() {
            animateValue(document.querySelector('.display-4'), 0, <?php echo $points;?>, 2000);
            
            // Animate elements when they scroll into view
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.card:not(.animate__animated)');
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight - 100;
                    
                    if (elementPosition < screenPosition) {
                        element.classList.add('animate_animated', 'animate_fadeInUp');
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            
            // Initialize particles
            initParticles();
        });

        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start) + " Points";
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
        
        // Particle animation
        function initParticles() {
            const canvas = document.querySelector('.particles');
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            let particlesArray = [];
            
            // Create particle
            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 3 + 1;
                    this.speedX = Math.random() * 2 - 1;
                    this.speedY = Math.random() * 2 - 1;
                    this.color = this.getRandomColor();
                }
                
                getRandomColor() {
                    const colors = [
                        'rgba(156, 39, 176, 0.7)',
                        'rgba(103, 58, 183, 0.7)',
                        'rgba(33, 150, 243, 0.7)',
                        'rgba(3, 169, 244, 0.7)'
                    ];
                    return colors[Math.floor(Math.random() * colors.length)];
                }
                
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    
                    if (this.size > 0.2) this.size -= 0.01;
                    
                    if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
                }
                
                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
            
            function init() {
                particlesArray = [];
                for (let i = 0; i < 50; i++) {
                    particlesArray.push(new Particle());
                }
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < particlesArray.length; i++) {
                    particlesArray[i].update();
                    particlesArray[i].draw();
                }
                requestAnimationFrame(animate);
            }
            
            window.addEventListener('resize', function() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                init();
            });
            
            init();
            animate();
        }
        
        // Add some interactivity to badges
        document.querySelectorAll('.badge-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('animate_animated', 'animate_pulse');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('animate_animated', 'animate_pulse');
            });
        });
        
        // Home button animation
        const homeBtn = document.querySelector('.home-btn');
        homeBtn.addEventListener('mouseenter', function() {
            this.classList.add('animate_animated', 'animate_rubberBand');
        });
        
        homeBtn.addEventListener('mouseleave', function() {
            this.classList.remove('animate_animated', 'animate_rubberBand');
        });
        
        homeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.add('animate_animated', 'animate_fadeOut');
            setTimeout(() => {
                alert('Going to Home Page');
                document.body.classList.remove('animate_animated', 'animate_fadeOut');
            }, 500);
        });
    </script>
    <script src="notifications.js"></script>
</body>
</html>