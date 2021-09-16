<?php
session_start();
require '../function/functions.php';


if (isset($_POST['submit'])) {
   if (registrasi($_POST) > 0) {
      $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($_POST["username"])))));
      $jir = substr($d, 0, 10);
      $username = htmlspecialchars(strtolower(stripslashes($_POST["username"])));
      $_SESSION["jir"] = $jir;
      $_SESSION["njay"] = $username;
      $_SESSION["registrasi"] = true;
      echo "<script>
                     document.location.href = '../pendataan/'
            </script>";
   } else {
      echo mysqli_error($conn);
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrasi</title>
   <link rel="icon" href="../img/logo/logoOnly.png">

   <!-- My Style -->
   <link rel="stylesheet" href="../css/register.css">

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
               <h1>Form Registrasi</h1>
               <div class="input-data">
                  <div class="input-form">
                     <input type="text" name="nama" id="nama" placeholder="Masukkan nama Anda" autocomplete="off" required>
                     <label for="nama"><i class="fas fa-user"></i> Nama</label>
                  </div>
                  <div class="input-form">
                     <input type="date" name="date" id="date" placeholder="Masukkan date Anda" autocomplete="off" required>
                     <label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
                  </div>
                  <div class="input-form">
                     <input type="text" name="username" id="username" placeholder="Masukkan username Anda" autocomplete="off" required>
                     <label for="username"><i class="fas fa-user-tag"></i> Username</label>
                  </div>
                  <div class="input-form">
                     <input type="password" name="password" id="password" placeholder="Masukkan password Anda" autocomplete="off" required>
                     <label for="password"><i class="fas fa-lock"></i> Password</label>
                  </div>
                  <div class="input-form">
                     <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Masukkan konfirmasi Anda" autocomplete="off" required>
                     <label for="konfirmasi"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                  </div>
               </div>
               <button type="submit" name="submit" class="submit">Registrasi</button>
               <!-- <p>Akan ada tahap kedua registrasi setelah anda klik tombol Registrasi.</p> -->
               <p>Sudah punya akun? silakan klik <a href="../login/">di sini</a> untuk langsung melakukan login</p>
            </form>
         </div>
      </div>
   </div>
   <script src="../js/floating-placeholder.js"></script>
</body>

</html>