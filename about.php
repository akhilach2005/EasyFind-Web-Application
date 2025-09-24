<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EasyFind - About</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
      --light: #f8fafc;
      --gray: #94a3b8;
      --border: #2a2d36;
    }

    body {
      background-color: var(--dark);
      color: var(--light);
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      padding-top:80px;
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

    /* Main content styles */
    .main-content {
      background-color: black;
      max-width: 1000px;
      min-height: 600px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 2rem;
      color: var(--primary-light);
      animation: fadeInUp 1s ease;
    }

    .about-container {
      max-width: 80%;
      margin: 0 auto;
      /* background: linear-gradient(135deg, #0a0e17, #161a26, #1e1a30); */
      /* background-size: 300% 300%; */
      /* background-color: rgba(18, 20, 24, 0.8); */
      background: radial-gradient(circle at 70% 30%, rgba(114, 9, 183, 0.5), transparent 70%);
      padding: 2rem;
      border-radius: 50px;
      box-shadow: 0 0 50px rgba(222, 48, 222, 0.1);
      animation: fadeInUp 1s ease;
    }

    .about-text {
      line-height: 1.7;
      font-size: 1rem;
      color: var(--text-light);
    }

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
  </style>
</head>
<body>
  <!-- Navbar Start -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span class="brand-icon">🔍</span>
        <span class="brand-text">EasyFind</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fas fa-bars text-white"></i>
      </button>
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
  <!-- Navbar End -->

  <!-- About Section with Background -->
  <div class="main-content">
    <div class="about-container">
      <h2 class="section-title">About EasyFind</h2>
      <p class="about-text">
        Login with your SRM credentials to access your personal dashboard.
      </p>
      <p class="about-text">
        Search Lost Items by entering keywords like name, date, or location to find your missing belongings.
      </p>
      <p class="about-text">
        Report Found Items using the form—add item details and upload an image if available
      <p class="about-text">
        Submit a Claim if you find your lost item listed. Just fill out the claim request form.
      </p>
      <p class="about-text">
        View Rewards for returning lost items. Check if any listed item carries a reward.
      </p>
      Get Notifications on matches, claim updates, or found reports directly in your dashboard.
      <p class="about-text"></p>
    </div>
  </div>

  <!-- Scripts -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>