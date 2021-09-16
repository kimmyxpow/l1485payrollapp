<?php
session_start();
require '../function/functions.php';

if (!isset($_SESSION['registrasi'])) {
   header("Location: ../registrasi/");
   exit;
}


if (isset($_POST['submit'])) {
   if (pendataan($_POST) > 0) {
      echo "<script>
                  alert ('Anda Telah Berhasil Registrasi!')
                  document.location.href = '../upload-profil/'
            </script>";
   } else {
      echo mysqli_error($conn);
      echo mysqli_errno($conn);
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pendataan</title>
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
               <h1>Pendataan</h1>
               <div class="input-data">
                  <div class="input-form">
                     <input type="email" name="email" id="email" placeholder="Masukkan email Anda" autocomplete="off" required>
                     <label for="email"><i class="fas fa-at"></i> Email Aktif</label>
                  </div>
                  <div class="input-form">
                     <input type="number" name="no" id="no" placeholder="Masukkan no Anda" autocomplete="off" required>
                     <label for="no"><i class="fas fa-phone-alt"></i> Nomor Telepon Aktif</label>
                  </div>
                  <div class="select-form">
                     <select name="status" id="status">
                        <option value="Belum Diatur">
                           - Status -
                        </option>
                        <option value="Menikah">Sudah Menikah</option>
                        <option value="Belum Menikah">Belum Menikah</option>
                     </select>
                  </div>
                  <div class="input-form">
                     <input type="number" name="jmlh" id="jmlh" placeholder="Masukkan jmlh Anda" autocomplete="off" required>
                     <label for="jmlh"><i class="fas fa-child"></i> Jumlah Anak</label>
                  </div>
               </div>
               <button type="submit" name="submit" class="submit">Kirim</button>
               <p>Sebagian data tidak akan diperlihatkan pada user lain.</p>
            </form>
         </div>
      </div>
   </div>
   <script src="../js/floating-placeholder.js"></script>
</body>

</html>