<?php 
session_start();
include("database.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $ItemName = mysqli_real_escape_string($conn, $_POST['ItemName']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $username = $_SESSION['username'];
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
   
        
        if (empty($ItemName) || empty($category) || empty($location) || empty($date) || empty($contact) || empty($description)) {
            echo "<script>alert('❌ All fields are required.'); window.history.back();</script>";
            exit();
        }
        
        if (!preg_match('/^[a-zA-Z\s]+$/', $ItemName)) {
            echo "<script>alert(' Invalid Item Name.'); window.history.back();</script>";
            exit();
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $category)) {
            echo "<script>alert(' Invalid Category.'); window.history.back();</script>";
            exit();
        }
        
        if(!preg_match('/^[a-zA-Z0-9\s-]+$/',$location))
        {
            echo "<script>alert(' Invalid Location.'); window.history.back();</script>";
            exit();
        }

        $submitted_date = strtotime($date);
        $today=strtotime(date('Y-m-d'));
        $thirty_days_ago=strtotime("-30 days");
     
         if($submitted_date>$today)
         {
             echo "<script>alert('Date cannot be in future.'); window.history.back();</script>";
             exit();
          }
          
        if($submitted_date < $thirty_days_ago)
        {
            echo "<script>alert('Date must be within the last 30 days.'); window.history.back();</script>";
            exit();
         }
        if (!preg_match('/^\d{10}$/', $contact)) {
            echo "<script>alert('Contact must be a 10-digit number.'); window.history.back();</script>";
            exit();
        }
         $file_name="";
         if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $tempname = $_FILES['file']['tmp_name'];
        $upload_folder = "Uploads/";
        $upload_path = $upload_folder . $file_name;

      
        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        if (!move_uploaded_file($tempname, $upload_path)) {
            echo "<script>alert('❌ Failed to upload file.'); window.history.back();</script>";
            exit();

        }
         }
            // ✅ Insert into database
            $query = "INSERT INTO lostitems (RegistrationID, ItemName, Category, Location_Lost, Date_Lost, Contact, Description, file)
                      VALUES ('$username', '$ItemName', '$category', '$location', '$date', '$contact', '$description', '$file_name')";
            
            if (mysqli_query($conn, $query)) {

                // ✅ Run image matcher Python script
                $escaped_filename = escapeshellarg($file_name);
                $script_path = escapeshellcmd("python imageMatcher.py $escaped_filename");
                shell_exec($script_path);

                // ✅ Read matching result from file
                $result_file = "last_match_result.txt";
                $match_message = "No match found.";

                if (file_exists($result_file)) {
                    $lines = file($result_file, FILE_IGNORE_NEW_LINES);
                    if ($lines[0] == "match") {
                        $matched_image = $lines[1];
                        $similarity = $lines[2];
                        $match_message = "🎉 Match found! Similar item: $matched_image (Similarity: $similarity%)";
                    }
                }
                echo "<script>alert('Lost item submitted!\\n$match_message');</script>";
            } else {
                echo "❌ Could not insert: " . mysqli_error($conn);
            }
        } 
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Lost Item</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    background: linear-gradient(
        rgba(0, 0, 0, 0.75), 
        rgba(0, 0, 0, 0.85)
    ),
    url("https://images.pexels.com/photos/87284/ocean-seacoast-rocks-water-87284.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: var(--text-light);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
}
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
      background: linear-gradient(90deg, var(--secondary), var(--primary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
.user-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .nav-icon-btn {
      background: transparent;
      border: none;
      color: var(--text-light);
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
      background: var(--dark);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      min-width: 280px;
      margin-top: 1rem;
    }

    .dropdown-item {
      color: var(--text-light);
      padding: 0.8rem 1rem;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .dropdown-item:hover {
      background: rgba(255, 255, 255, 0.05);
      color: var(--primary);
      border-left: 3px solid var(--secondary);
    }

    .dropdown-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .dropdown-title {
      font-weight: 600;
      color: var(--text-light);
    }

    .dropdown-action {
      color: var(--primary);
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
      color: var(--primary);
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
      color: rgba(255, 255, 255, 0.6);
    }

.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 15px;
    min-height: calc(100vh - 76px);
    perspective: 1000px;
}

#lostItemForm {
    background: rgba(10, 10, 10, 0.7);
    padding: 35px;
    border-radius: 15px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transform-style: preserve-3d;
    animation: formEntrance 1s ease forwards;
}

@keyframes formEntrance {
    0% {
        opacity: 0;
        transform: translateY(30px) rotateX(10deg);
    }
    100% {
        opacity: 1;
        transform: translateY(0) rotateX(0);
    }
}

/* Fix for h2 title display */
/* #lostItemForm h2 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    font-size: 2rem;
    letter-spacing: 1px;
    position: relative;
    background: linear-gradient(90deg, #9c27b0, #3498db, #9c27b0);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    color: white; /* Fallback color */
    /* -webkit-text-fill-color: transparent;
    animation: gradient 3s linear infinite, titleGlow 2s ease-in-out infinite alternate;
    display: block; 
}

@keyframes titleGlow {
    0% {
        text-shadow: 0 0 5px rgba(156, 39, 176, 0.5);
    }
    100% {
        text-shadow: 0 0 15px rgba(156, 39, 176, 0.8), 0 0 30px rgba(156, 39, 176, 0.4);
    }
}

#lostItemForm h2::after {
    content: '';
    position: absolute;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    bottom: -10px;
    left: calc(50% - 25px);
    border-radius: 2px;
} */ */

form {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards 0.5s;
}

@keyframes fadeIn {
    to { opacity: 1; }
}

label {
    display: block;
    margin-top: 18px;
    margin-bottom: 6px;
    font-weight: 500;
    color: var(--text-light);
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    opacity: 0.85;
}

input[type="text"],
input[type="number"],
input[type="file"],
input[type="date"],
textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-light);
    transition: all 0.3s ease;
    font-size: 0.95rem;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="date"]:focus,
textarea:focus {
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 8px rgba(156, 39, 176, 0.4), inset 0 2px 5px rgba(0, 0, 0, 0.2);
    outline: none;
}

input::placeholder,
textarea::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

#submit {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    padding: 14px;
    color: white;
    font-weight: bold;
    border-radius: 8px;
    margin-top: 25px;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 1px;
    text-transform: uppercase;
    box-shadow: 0 5px 15px rgba(156, 39, 176, 0.4);
    position: relative;
    overflow: hidden;
}

#submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.5s;
}

#submit:hover::before {
    left: 100%;
}

#submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(156, 39, 176, 0.6);
}

#submit:active {
    transform: translateY(1px);
}

#imagePreview {
    margin-top: 20px;
    display: none;
    text-align: center;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

#previewImg {
    max-width: 100%;
    max-height: 200px;
    border-radius: 10px;
    border: 2px solid var(--primary);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
    text-decoration: none;
}

.home-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    color: white;
}

.floating {
    animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

@keyframes floatAnimation {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

@keyframes inputFocus {
    0% { box-shadow: 0 0 0 rgba(156, 39, 176, 0); }
    100% { box-shadow: 0 0 10px rgba(156, 39, 176, 0.5); }
}

.form-group {
    position: relative;
    margin-bottom: 20px;
}

input[type="file"] {
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    cursor: pointer;
}

input[type="file"]::file-selector-button {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    margin-right: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    transform: translateY(-2px);
}

.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

#lostItemForm > * {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

#lostItemForm > *:nth-child(1) { animation-delay: 0.1s; }
#lostItemForm > *:nth-child(2) { animation-delay: 0.2s; }
#lostItemForm > *:nth-child(3) { animation-delay: 0.3s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#lostItemForm:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(156, 39, 176, 0.4);
    transform: translateY(-5px);
    transition: all 0.5s ease;
}

body, h1, h2, h3, p, label, input, textarea, button, a {
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

@keyframes wave {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

@keyframes gradient {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

@media (max-width: 768px) {
    #lostItemForm {
        padding: 25px;
    }
    #lostItemForm h2 {
        font-size: 1.8rem;
    }
}

@media (max-width: 576px) {
    #lostItemForm {
        padding: 20px;
    }
    #lostItemForm h2 {
        font-size: 1.6rem;
    }
    .form-wrapper {
        padding: 15px;
    }
}
.navbar {
    padding: 0.5rem 0; /* Reduced padding to make navbar smaller */
    min-height: 60px; /* Set fixed height for the navbar */
  }
  
  .report-text {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
  }
  
  .report-text h5 {
    font-weight: 600;
    letter-spacing: 0.5px;
    /* color: var(--text-light); */
  }
  
  /* Make notification badge animation */
  @keyframes pulseGlow {
    0% {
      box-shadow: 0 0 0 0 rgba(156, 39, 176, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(156, 39, 176, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(156, 39, 176, 0);
    }
  }
  
  /* Adjust top padding for form wrapper to account for smaller navbar */
  .form-wrapper {
    padding-top: 80px;
  }
  /* .form-title {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    font-size: 2rem;
    color: white !important;
    letter-spacing: 1px;
    position: relative;
    display: block;
} */

/* If you still want the gradient effect, you can apply it like this */
.form-title {
    background: linear-gradient(90deg, #9c27b0, #3498db, #9c27b0);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient 3s linear infinite, titleGlow 2s ease-in-out infinite alternate;
}
h3{
    color:white;
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
        
          
      
          <!-- Profile on right side -->
          <div class="user-nav ms-auto">
            <!-- Profile dropdown -->
            <div class="dropdown">
              <button class="nav-icon-btn" type="button" data-bs-toggle="dropdown">
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
      <!-- <div>
      <h2 style="color:wheat; align-items: center;">Lost Item Report</h2>
      </div> -->

    <div class="form-wrapper">
      
        <div id="lostItemForm">
            <h2 class="form-title" style="align-items: center;">Fill Out the Lost Form</h2>

            <form action="lostform.php" method="post" enctype="multipart/form-data" id="lostform" onsubmit="return validateForm(event)">
                <label for="item">Name of Item</label>
                <input type="text" id="item" placeholder="Enter the item"  name="ItemName"required>

                <label for="category">Category</label>
                <input type="text" id="category" placeholder="Enter the category" name="category" required>

                <label for="location">Location (where you lost it)</label>
                <input type="text" id="location" placeholder="Enter the location"  name="location"required>
               
                <label for="date">Date (when you lost it)</label>
                <input type="date" id="date" placeholder="Enter the date"  name="date"required>

                <label for="contact">Contact Info</label>
                <input type="number" id="contact" placeholder="Enter your phone number" name="contact" required>

                <label for="description">Description</label>
                <textarea id="description" rows="4" placeholder="Enter details about the lost item" name="description"></textarea>

                <label for="file">Upload Image</label>
                <input type="file" id="file" accept="image/*" name  ="file">

                <div id="imagePreview">
                    <img id="previewImg" class="img-fluid rounded" name="preview">
                </div>

                <input type="submit" name="submit" id="submit">
                <!-- <h3 id="sm"></h3>     Form submission -->
                <div id="sm" class="d-none alert"></div>
            </form>
            
        </div>
    </div>
    
    <a href="home.php" class="home-btn floating">
        <i class="fas fa-home"></i>
    </a>

    <script>
        document.getElementById("file").addEventListener("change", function (event) {
            let file = event.target.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("previewImg").src = e.target.result;
                document.getElementById("imagePreview").style.display = "block";
            };
            reader.readAsDataURL(file);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            // Create canvas for particles
            const particlesCanvas = document.createElement('canvas');
            particlesCanvas.classList.add('particles');
            document.body.appendChild(particlesCanvas);
            
            const ctx = particlesCanvas.getContext('2d');
            particlesCanvas.width = window.innerWidth;
            particlesCanvas.height = window.innerHeight;
            
            // Particles array
            let particlesArray = [];
            
            // Create particle class
            class Particle {
                constructor() {
                    this.x = Math.random() * window.innerWidth;
                    this.y = Math.random() * window.innerHeight;
                    this.size = Math.random() * 2 + 0.5;
                    this.speedX = Math.random() * 1 - 0.5;
                    this.speedY = Math.random() * 1 - 0.5;
                    this.color = this.getRandomColor();
                }
                
                getRandomColor() {
                    const colors = [
                        'rgba(156, 39, 176, 0.7)',
                        'rgba(108, 52, 131, 0.7)',
                        'rgba(52, 152, 219, 0.7)'
                    ];
                    return colors[Math.floor(Math.random() * colors.length)];
                }
                
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    
                    if (this.size > 0.2) this.size -= 0.01;
                    
                    if (this.x < 0 || this.x > window.innerWidth) this.speedX *= -1;
                    if (this.y < 0 || this.y > window.innerHeight) this.speedY *= -1;
                }
                
                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
            
            // Initialize particles
            function init() {
                particlesArray = [];
                for (let i = 0; i < 50; i++) {
                    particlesArray.push(new Particle());
                }
            }
            
            // Animation loop
            function animate() {
                ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
                
                for (let i = 0; i < particlesArray.length; i++) {
                    particlesArray[i].update();
                    particlesArray[i].draw();
                }
                
                // Replace particles that get too small
                for (let i = 0; i < particlesArray.length; i++) {
                    if (particlesArray[i].size <= 0.2) {
                        particlesArray.splice(i, 1);
                        particlesArray.push(new Particle());
                    }
                }
                
                requestAnimationFrame(animate);
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                particlesCanvas.width = window.innerWidth;
                particlesCanvas.height = window.innerHeight;
                init();
            });
            
            // Add form field animation effects
            const formInputs = document.querySelectorAll('input, textarea');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.animation = 'inputFocus 1s ease infinite alternate';
                });
                
                input.addEventListener('blur', function() {
                    this.style.animation = '';
                });
            });
            
            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Initialize
            init();
            animate();
        });
        
        // Home button animation
        const homeBtn = document.querySelector('.home-btn');
        homeBtn.addEventListener('mouseenter', function() {
            this.classList.add('animate__pulse');
        });
        
        homeBtn.addEventListener('mouseleave', function() {
            this.classList.remove('animate__pulse');
        });
        
        homeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.add('animate__fadeOut');
            setTimeout(() => {
                window.location.href = 'home.php';
            }, 500);
        });


function validateForm(event)
{
  document.querySelectorAll(".error").forEach(el => el.textContent = "");

  let isValid = true;

  const item = document.getElementById("item").value.trim();
  const category = document.getElementById("category").value.trim();
  const location = document.getElementById("location").value.trim();
  const date = document.getElementById("date").value;
  const contact = document.getElementById("contact").value.trim();
  const fileInput = document.getElementById("file");

  const itemRegex = /^[A-Za-z\s]+$/;
  const categoryRegex = /^[A-Za-z\s]+$/;
  const locationRegex = /^[A-Za-z0-9\s-]+$/;
  const contactRegex = /^[0-9]{10}$/;

  // Item name validation
  if (!itemRegex.test(item)) {
    document.getElementById("itemError").textContent = "Only alphabets and spaces allowed.";
    isValid = false;
  }

  // Category validation
  if (!categoryRegex.test(category)) {
    document.getElementById("categoryError").textContent = "Only alphabets and spaces allowed.";
    isValid = false;
  }

  // Location validation
  if (!locationRegex.test(location)) {
    document.getElementById("locationError").textContent = "Use alphabets, numbers, spaces, and '-' only.";
    isValid = false;
  }

  // Date validation
  const selectedDate = new Date(date);
  const today = new Date();
  const oneMonthAgo = new Date();
  oneMonthAgo.setMonth(today.getMonth() - 1);

  if (selectedDate > today || selectedDate < oneMonthAgo) {
    document.getElementById("dateError").textContent = "Select a date within the past one month.";
    isValid = false;
  }

  // Contact number validation
  if (!contactRegex.test(contact)) {
    document.getElementById("contactError").textContent = "Enter a valid 10-digit phone number.";
    isValid = false;
  }

  // File validation (optional)
//   if (!fileInput.files.length) {
//     document.getElementById("fileError").textContent = "Please select an image to upload.";
//     isValid = false;
//   }

  if (!isValid) {
    event.preventDefault();
  }
  return isValid;
}

// Image preview
document.getElementById("file").addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("previewImg").src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});


    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="notifications.js"></script>
</body>
</html>
