<?php
session_start();
include("database.php");

if($_SERVER["REQUEST_METHOD"]=="POST")
{
if(isset($_POST["submit"]))
{
 
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $errorMessage="";

    $select="SELECT RegistrationID,Password FROM Students WHERE RegistrationID='$username' AND  Password='$password'";
    $rows=mysqli_query($conn,$select);
    if(mysqli_num_rows($rows)>0)   
    {
        $_SESSION['username']=$username;
        echo "<script>window.location.href = 'home.php';</script>";
    }
    else
    {
    $s1="SELECT RegistrationID FROM Students WHERE  RegistrationID='$username'";
   
    if(mysqli_num_rows(mysqli_query($conn,$s1))>0)
    {
         $errorMessage="Invalid Password";
    }
    else if(mysqli_num_rows(mysqli_query($conn,$s1))==0)
    {
         $errorMessage="Username is not registered!<br>Contact your admin.";
    }
    else{
    $errorMessage='Invalid username or password';
    }  
 }
 echo "<script>
 window.onload = function() {
     document.getElementById('error-message').innerHTML = '".addslashes($errorMessage)."';
 };
 </script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Easy Find - Login</title>
  <style>
    :root {
      --primary: #4361ee;
      --primary-dark: #3a0ca3;
      --primary-light: #4cc9f0;
      --secondary: #f72585;
      --accent: #7209b7;
      --success: #4ade80;
      --warning: #fbbf24;
      --dark: #0a0b0e;
      --light: #f8fafc;
      --gray: #94a3b8;
      --border: #334155;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      font-family: 'Poppins', sans-serif;
      background-color: var(--dark);
    }

    .container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    .form-section {
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: linear-gradient(to bottom right, #0a0b0e, #1d1737, #241d35, #1a0234);
}


    .image-section {
      width: 35%;
      background: url('EasyFind_Login.jpeg') no-repeat center center / cover;
      border-radius:20px;
      border:1px solid rgb(117, 102, 64);
    }

    .form-heading {
      font-size: 50px;
      color: var(--primary);
      margin-bottom: 40px;
      text-align: center;
    }

    .form-box {
      width: 100%;
      max-width: 420px;
      background-color: #111827;
      padding: 40px;
      border: 2px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 0 24px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-box:hover {
      transform: scale(1.02);
      box-shadow: 0 0 30px rgba(67, 97, 238, 0.5);
    }

    label {
      color: var(--gray);
      font-size: 14px;
      margin-bottom: 5px;
      display: block;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 18px;
      font-size: 15px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background-color: #1f2937;
      color: white;
    }

    input::placeholder {
      color: var(--gray);
    }

    input:focus {
      outline: none;
      border-color: var(--primary-light);
      box-shadow: 0 0 8px var(--primary-light);
    }

    #login {
      width: 100%;
      padding: 12px;
      background: linear-gradient(to right, var(--primary), var(--primary-dark));
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    #login:hover {
      background: linear-gradient(to right, var(--primary-dark), var(--accent));
      transform: scale(1.03);
    }

    .error {
      color: var(--warning);
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .form-section, .image-section {
        width: 100%;
        height: 50%;
      }

      .form-box {
        margin: 0 20px;
      }
    }
    .particles {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 0;
  pointer-events: none;
}

.form-section {
  position: relative; /* Add this */
  overflow: hidden;   /* Prevent canvas overflow */
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: linear-gradient(to bottom right, #0a0b0e, #06001f, #120d1e, #0d011a);
}

.form-box, .form-heading {
  z-index: 1; /* Ensure they appear above the canvas */
}

  </style>
</head>
<body>
  <div class="container">
    <div class="form-section">
      <h1 class="form-heading">Easy Find</h1>
      <div class="form-box">
      <form action="login.php" method="post">
       <div id="loginform" >
        <label for="uname">User Name</label><br>
        <input type="text" id="uname"name="username" placeholder="APXXXXXXXXXXX" required><br><br>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>
        <input type="submit" name="submit" id="login" value="Log In">
        <p class="error" id="error-message"></p>
    </div>
    </form>
      </div>
    </div>
    <div class="image-section"></div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const formSection = document.querySelector('.form-section');
    
      const particlesCanvas = document.createElement('canvas');
      particlesCanvas.classList.add('particles');
      formSection.appendChild(particlesCanvas);
    
      const ctx = particlesCanvas.getContext('2d');
      particlesCanvas.width = formSection.clientWidth;
      particlesCanvas.height = formSection.clientHeight;
    
      let particlesArray = [];
    
      class Particle {
        constructor() {
          this.x = Math.random() * particlesCanvas.width;
          this.y = Math.random() * particlesCanvas.height;
          this.size = Math.random() * 2 + 0.5;
          this.speedX = Math.random() * 1 - 0.5;
          this.speedY = Math.random() * 1 - 0.5;
          this.color = this.getRandomColor();
        }
    
        getRandomColor() {
          const colors = [
            'rgba(156, 39, 176, 0.6)',
            'rgba(108, 52, 131, 0.6)',
            'rgba(52, 152, 219, 0.6)'
          ];
          return colors[Math.floor(Math.random() * colors.length)];
        }
    
        update() {
          this.x += this.speedX;
          this.y += this.speedY;
          if (this.size > 0.2) this.size -= 0.01;
    
          if (this.x < 0 || this.x > particlesCanvas.width) this.speedX *= -1;
          if (this.y < 0 || this.y > particlesCanvas.height) this.speedY *= -1;
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
        ctx.clearRect(0, 0, particlesCanvas.width, particlesCanvas.height);
        for (let i = 0; i < particlesArray.length; i++) {
          particlesArray[i].update();
          particlesArray[i].draw();
        }
    
        for (let i = 0; i < particlesArray.length; i++) {
          if (particlesArray[i].size <= 0.2) {
            particlesArray.splice(i, 1);
            particlesArray.push(new Particle());
          }
        }
    
        requestAnimationFrame(animate);
      }
    
      window.addEventListener('resize', function () {
        particlesCanvas.width = formSection.clientWidth;
        particlesCanvas.height = formSection.clientHeight;
        init();
      });
    
      init();
      animate();
    });
    </script>
    
</body>
</html>