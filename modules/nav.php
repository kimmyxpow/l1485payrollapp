<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("Location: ../login/");
   exit;
}
include '../function/functions.php';

$url = $_SESSION['jir'];
$name =  query("SELECT * FROM `user` WHERE `url` = '$url'");
$carilevel =  query("SELECT level FROM `user` WHERE `url` = '$url'");
foreach ($carilevel as $d) {
   $levelsaya = $d['level'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Buat tugas</title>

   <!-- My Style -->
   <link rel="stylesheet" href="../css/style.css">
   <link rel="icon" href="../img/logo/logoOnly.png">

   <!-- Font Awesome Icons -->
   <script src="https://kit.fontawesome.com/a076d05399.js"></script>
   <link rel="stylesheet" href="../css/css/all.min.css">
</head>

<body>
   <div class="wrapper">
      <nav>
         <input type="checkbox" id="show-search">
         <input type="checkbox" id="show-menu">
         <label for="show-menu" class="menu-icon"><i class="fas fa-bars"></i></label>
         <div class="content">
            <div class="logo"><a href="../home/"><img src="../img/logo/logoOnly.png" alt="" width="10%"> Payroll App L-1485</a></div>
            <ul class="links">
               <li><a href="../home/">Home</a></li>
               <li>
                  <a href="#" class="desktop-link">Data Company<i class="fas fa-angle-down arrow"></i></a>
                  <input type="checkbox" id="show-users">
                  <label for="show-users">Data Company<i class="fas fa-angle-down arrow"></i></label>
                  <ul>
                     <li><a href="../data-jabatan/">Data Jabatan</a></li>
                     <li><a href="../data-golongan/">Data Golongan</a></li>
                     <li>
                        <a href="#" class="desktop-link">Data Pegawai<i class="fas fa-angle-right arrow"></i></a>
                        <input type="checkbox" id="show-items">
                        <label for="show-items">Data Pegawai<i class="fas fa-angle-down arrow"></i></label>
                        <ul>
                           <li><a href="../pegawai/">Daftar Pegawai</a></li>
                           <li><a href="../kehadiran-pegawai/">Kehadiran Pegawai</a></li>
                           <li><a href="../gaji-pegawai/">Gaji Pegawai</a></li>
                        </ul>
                     </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="desktop-link">Data Aplikasi<i class="fas fa-angle-down arrow"></i></a>
                  <input type="checkbox" id="show-services">
                  <label for="show-services">Data Aplikasi<i class="fas fa-angle-down arrow"></i></label>
                  <ul>
                     <li><a href="../data-users/">Data All User</a></li>
                     <li><a href="../data-regular-user/">Data Regular User</a></li>
                     <li><a href="../data-admin/">Data Admin</a></li>
                     <li><a href="../data-super-admin/">Data Super Admin</a></li>
                  </ul>
               </li>
               <!-- <li>
                  <a href="#" class="desktop-link">Laporan<i class="fas fa-angle-down arrow"></i></a>
                  <input type="checkbox" id="show-features">
                  <label for="show-features">Laporan<i class="fas fa-angle-down arrow"></i></label>
                  <ul>
                     <li><a href="../cetak-laporan-pegawai/" target="_blank">Laporan Data Pegawai</a></li>
                     <li><a href="../cetak-laporan-golongan/" target="_blank">Laporan Data Golongan</a></li>
                     <li><a href="../cetak-laporan-jabatan/" target="_blank">Laporan Data Jabatan</a></li>
                     <li><a href="../cetak-laporan-kehadiran/" target="_blank">Laporan Kehadiran Pegawai</a></li>
                     <li><a href="../cetak-laporan-lembur/" target="_blank">Laporan Lembur Pegawai</a></li>
                     <li><a href="../cetak-laporan-potongan-gaji/" target="_blank">Laporan Potongan Gaji</a></li>
                  </ul>
               </li> -->
            </ul>
         </div>
         <div class="btn">
            <!-- <a href="../logout/" class="logout">Logout</a> -->
            <?php foreach ($name as $n) { ?>
               <a href="../profil/">
                  <img src="../img/user/<?= $n['pic']; ?>" alt="<?= $n['nama']; ?>">
               </a>
            <?php } ?>
         </div>
      </nav>
   </div>
   <form method="post" action="">
      <input type="hidden" value="<?= $levelsaya; ?>" name="levelsaya" id="levelsaya">
   </form>
   <br>
   <br>
   <br>
   <br>