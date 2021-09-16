<?php

require 'function/functions.php';

require_once 'faker/vendor/fzaninotto/Faker/src/autoload.php';
$faker = Faker\Factory::create('id_ID');

function faker()
{
  global $conn;
  global $faker;

  $no = htmlspecialchars(strtolower(stripslashes($faker->phoneNumber)));
  $nama = htmlspecialchars(strtolower(stripslashes($faker->name($gender = 'female'))));
  $email = str_replace(" ", "", $nama) . "@gmail.com";
  $status = 'Menikah';
  $jmlh = 0;
  $gol = 'G03';
  $jab = 'J05';
  $namap = strtok($nama, " ");
  $date = $faker->date($format = 'Y-m-d', $max = '-17 years');
  $username = str_replace(" ", "", $nama);
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($username)))));
  $url = substr($d, 0, 10);
  $password = $username;
  $konfirmasi = $password;

  // Cek username sudah dipakai atau belum

  $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

  if (mysqli_fetch_assoc($result)) {
    echo "<script>alert('Username sudah terdaftar!');</script>";
    return false;
  }

  // Konsirmasi Password

  if ($password !== $konfirmasi) {
    echo "<script>alert('Konfirmasi password tidak sesuai');</script>";
    return false;
  }

  // Enkripsi password

  $password = password_hash($password, PASSWORD_DEFAULT);

  // Buat NIP
  // NIP dibuat dari tanggal lahir, tahun masuk, nomor masuk, tahun perusahaan berdiri
  $njay = mysqli_query($conn, "SELECT * FROM `USER` WHERE id IN (SELECT MAX(id) FROM `USER`)");
  foreach ($njay as $d) {
    $lastNIP = $d['nip'];
  }

  $potong = substr($lastNIP, 8);
  // $date = $_POST['date'];
  $datenow = date("y");
  $datenew = str_replace("-", "", $date);
  $datenew = substr($datenew, 2);
  $tambahno = $potong + 1;
  $potong = substr($tambahno, 2);
  $nip = $datenow . $datenew . "20" . $potong;

  $urut = substr($nip, 8);


  // Tambah user baru ke database

  mysqli_query($conn, "INSERT INTO user VALUES('', '$nip', '$nama', '$namap', '$date', '$email', '$no', '$username', '$url', '$jab', '$gol', '$status', $jmlh, '$password', 'Pegawai', '', '$urut')");
  return mysqli_affected_rows($conn);
}

if (isset($_POST['submit'])) {
  if (faker() > 0) {
    echo "bisa";
  } else {
    echo "gbs<br>";
    echo mysqli_errno($conn) . "<br>";
    echo mysqli_error($conn) . "<br>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form method="post" action="" enctype="multipart/form-data">
    <button type="submit" name="submit">submit</button>
  </form>
</body>

</html>