<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view'])) {
    $itemId = intval($_POST['view']);
    $query = "SELECT * FROM lostitems WHERE ItemID = $itemId LIMIT 1";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0) {
        $item = mysqli_fetch_assoc($res);
        echo "
        <div class='item-details'>
            <div class='details-table'>
                <table>
                    <tr><th>Name</th><td>".htmlspecialchars($item['ItemName'])."</td></tr>
                    <tr><th>Description</th><td>".htmlspecialchars($item['Description'])."</td></tr>
                    <tr><th>Finder</th><td>".htmlspecialchars($item['RegistrationId'])."</td></tr>
                    <tr><th>Category</th><td>".htmlspecialchars($item['Category'])."</td></tr>
                    <tr><th>Location Found</th><td>".htmlspecialchars($item['Location_Lost'])."</td></tr>
                    <tr><th>Date Found</th><td>".date("m/d/Y", strtotime($item['Date_Lost']))."</td></tr>
                    <tr><th>Status</th><td>Lost</td></tr>
                </table>
            </div>
            <div class='item-image'>";
            if (!empty($item['file']) && file_exists("Uploads/" . $item['file'])) {
              echo "<img src='Uploads/" . htmlspecialchars($item['file']) . "' width='400' height='400' 
                    style='margin:10px; border:1px solid #ccc; object-fit: cover;'>";
          }else {
            echo "<div style='width:300px; height:300px; border:1px dashed #aaa; 
                        display:flex; align-items:center; justify-content:center; 
                        font-style:italic; color:#777; margin:10px;'>
                    No image available
                  </div>";
        }

        echo "</div></div>";
    } else {
        echo "<p style='color:red;'>Item not found.</p>";
    }

    // Stop further execution so the rest of the page doesn't render again
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Find - Lost and Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
      --hover-color: #2d624f;
      --border-radius:8px;
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

        /* Table Container Styling */
        #tableContainer {
            background-color: #0f3460;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 25px;
            
            animation: slideUp 0.6s ease-out;
        }

        #tableContainer h2 {
            color: var(--light);
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 10px;
            animation: fadeIn 1s ease;
        }

        /* Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            color: #faf7f9;
        }

        .table thead th {
            background-color: #0d2d51;
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            border: none;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--border-radius);
        }

        .table thead th:last-child {
            border-top-right-radius: var(--border-radius);
        }

        .table tbody tr {
            background-color: #1a1a2e;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #16213e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Badge Styling */
        .badge.bg-warning {
            background-color: #ffc107 !important;
            animation: fadeIn 0.5s ease;
        }

        .badge.bg-success {
            background-color: var(--accent) !important;
            animation: fadeIn 0.5s ease;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            border-radius: var(--border-radius);
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Item Details Styling */
        .item-details {
            margin-top: 30px;
            background-color: #0f3460;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-wrap: wrap;
            padding: 25px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1140px;
            animation: fadeIn 0.8s ease;
        }

        .details-table {
            flex: 1;
            min-width: 300px;
            padding-right: 20px;
        }

        .item-image {
            flex: 1;
            min-width: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .item-image img {
            max-width: 100%;
            height: auto;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-height: 300px;
            transition: transform 0.5s ease;
        }

        .item-image img:hover {
            transform: scale(1.03);
        }

        .details-table th {
            background-color: #0d2d51;
            color: var(--light);
            padding: 15px;
            width: 30%;
            border: none;
        }

        .details-table td {
            background-color: #1a1a2e;
            padding: 15px;
            border: none; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                transform: translateY(50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes slideIn {
            from { 
                transform: translateX(-30px);
                opacity: 0;
            }
            to { 
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Row animations */
        .table tbody tr {
            animation: slideIn 0.5s ease forwards;
            opacity: 0;
        }
        
        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.5s; }

        @media (max-width: 992px) {
            .navbar-nav .nav-link {
                padding: 0.75rem 0;
            }
            
            .item-details {
                flex-direction: column;
            }
            
            .details-table {
                padding-right: 0;
                margin-bottom: 20px;
            }
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
        .search-container {
            background-color: var(--dark-card);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease-out forwards;
            
           
        }

        .input-group-text, .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius) !important;
           
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(76, 201, 240, 0.25);
            border-color: var(--primary-light);
            background-color: rgba(30, 32, 40, 0.95);
            
        }

        .form-control::placeholder {
            color: rgb(148, 39, 206);
        }

        /* No results message */
        .no-results {
            background: rgba(247, 37, 133, 0.1);
            color: var(--light);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 2rem 0;
            animation: fadeIn 0.5s ease;
        }

        .no-results i {
            font-size: 3rem;
            color: var(--secondary);
            margin-bottom: 1rem;
            display: block;
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
      
          <div class="d-flex align-items-center ms-auto user-nav">
           
      
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

      <div class="container mt-5">
        <!-- Search Container -->
        <div class="search-container">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-dark-accent border-0">
                            <i class="fas fa-search text-primary-light"></i>
                        </span>
                        <input type="text" id="itemSearchInput" class="form-control bg-dark-accent border-0 text-light" placeholder="Search by item name..." onkeyup="filterItems()">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-dark-accent border-0">
                            <i class="fas fa-filter text-primary-light"></i>
                        </span>
                        <select id="categoryFilter" class="form-select bg-dark-accent border-0 " onchange="filterItems()" placeholder="Category" style="color:rgb(148, 39, 206);">
                            <option value="">All Categories</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Personal Items">Personal Items</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Documents">Documents</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-custom-primary w-100" onclick="resetFilters()">
                        <i class="fas fa-undo-alt me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
      <div class="container mt-4" id="tableContainer">
        <h2>Lost Items</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Reported By</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date Found</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>            
                <?php
                include("database.php"); // Ensure this file connects to MySQL

                $query = "SELECT * FROM lostitems";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>".htmlspecialchars($row['ItemName'])."</td>
                            <td>".htmlspecialchars($row['RegistrationId'])."</td>
                            <td>".htmlspecialchars($row['Category'])."</td>
                            <td > <button class='btn btn-sm btn-warning'>LOST</button></td>
                            <td>".date("m/d/Y", strtotime($row['Date_Lost']))."</td>
                            <td class='actions-cell'>
                             <button class='btn btn-sm btn-success' onclick='viewItem(".$row['ItemID'].")'>VIEW</button>  
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No items found.</td></tr>";
                }
                ?>

                </tbody>
</table>
        </div>
    </div>

    <div id="itemDetailsContainer" class="mt-4"></div>

 
      

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-250px";
            } else {
                sidebar.style.left = "0px";
            }
        }
      
function viewItem(itemId) {
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "view=" + encodeURIComponent(itemId)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("itemDetailsContainer").innerHTML = data;
        window.scrollTo({
            top: document.getElementById("itemDetailsContainer").offsetTop,
            behavior: "smooth"
        });
    })
    .catch(err => {
        document.getElementById("itemDetailsContainer").innerHTML = "<p style='color:red;'>Could not load item details.</p>";
    });
}  

function filterItems() {
        const searchInput = document.getElementById('itemSearchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const table = document.querySelector('.table');
        const rows = table.querySelectorAll('tbody tr');
        
        let visibleRows = 0;
        
        rows.forEach(row => {
            const itemName = row.cells[0].textContent.toLowerCase();
            const category = row.cells[2].textContent;
            
            const matchesSearch = itemName.includes(searchInput);
            const matchesCategory = categoryFilter === '' || category === categoryFilter;
            
            if (matchesSearch && matchesCategory) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show "no results" message if no items match
        let noResultsMessage = document.querySelector('.no-results');
        
        if (visibleRows === 0) {
            if (!noResultsMessage) {
                noResultsMessage = document.createElement('div');
                noResultsMessage.className = 'no-results';
                noResultsMessage.innerHTML = `
                    <i class="fas fa-search-minus"></i>
                    <h4>No items match your search</h4>
                    <p>Try adjusting your search criteria or category filter</p>
                `;
                table.parentNode.appendChild(noResultsMessage);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }
    
    function resetFilters() {
        document.getElementById('itemSearchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        filterItems();
    }
    </script>
    <script src="notifications.js"></script>
</body>
</html>