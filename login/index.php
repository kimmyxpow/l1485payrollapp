<?php
session_start();
require '../function/functions.php';

if (isset($_SESSION["login"])) {
   header("Location: ../home/");
   exit;
}

if (isset($_POST['submit'])) {
   $password = $_POST["password"];
   $username = $_POST['username'];
   $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($_POST["username"])))));
   $url = substr($d, 0, 10);
   $inidata = query("SELECT url FROM user WHERE username = '$username'");
   foreach ($inidata as $rst) {
      $name = $rst["url"];
   }

   $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

   // Cek username

   if (mysqli_num_rows($result) === 1) {
      // Cek password
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
         // Set session
         $_SESSION["login"] = true;
         $_SESSION["jir"] = $name;
         $_SESSION['njay'] = $username;

         header("location: ../home/");
         exit;
      }
   }

   $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="icon" href="../img/logo/logoOnly.png">

   <!-- My Style -->
   <link rel="stylesheet" href="../css/login.css">
   <style>
      * {
         font-family: 'poppins', Arial, Helvetica, sans-serif;
      }
   </style>

   <!-- My Icons -->
   <script src="https://kit.fontawesome.com/249f1068c1.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="../css/css/all.min.css">

   <!-- My Fonts -->
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
   <div class="main">
      <div class="row">
         <div class="col">
            <img src="../img/logo/Group 2.png" alt="APeK EXO Company">
         </div>
      </div>
      <div class="row">
         <div class="col-lg-6">
            <div class="ntah">
               <img class="main-img" src="../img/vector/data-r-user.png" alt="Login Picture">
            </div>
         </div>
         <div class="col-lg-5">
            <form action="" method="post">
               <h1>Form Login</h1>
               <?php if (isset($error)) { ?>
                  <p style="color: red; font-style: italic;">Username/password salah</p>
               <?php } ?>
               <div class="input-data">
                  <div class="input-form">
                     <input type="text" name="username" id="username" placeholder="Masukkan Username Anda" autocomplete="off" required>
                     <label for="username"><i class="fas fa-user"></i> Username</label>
                  </div>
                  <div class="input-form">
                     <input type="password" name="password" id="password" placeholder="Masukkan Password Anda" autocomplete="off" required>
                     <label for="password"><i class="fas fa-lock"></i> Password</label>
                  </div>
               </div>
               <div class="ingatSaya" style="display: block;">
                  <input style="outline: none;" type="checkbox" name="rememberMe" id="rememberMe">
                  <label for="rememberMe">Ingat saya</label>
               </div>
               <button type="submit" name="submit" class="submit">Login</button>
               <p>Belum punya akun? silakan klik <a href="../registrasi/">di sini</a> untuk melakukan registrasi</p>
            </form>
         </div>
      </div>
   </div>
   <script src="../js/floating-placeholder.js"></script>
</body>

</html>