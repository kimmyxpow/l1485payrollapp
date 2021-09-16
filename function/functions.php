<?php
// Koneksi Database
$conn = mysqli_connect("localhost", "root", "", "tgs_akhir");

if (mysqli_connect_errno()) {
  echo "Koneksi database gagal : " . mysqli_connect_error();
}

function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $data = [];
  while ($admin = mysqli_fetch_assoc($result)) {
    $data[] = $admin;
  }
  return $data;
}

// Tambah Data Jabatan

function tambahjabatan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["kode"]);
  $nama = htmlspecialchars($data["nama"]);
  $gaji = htmlspecialchars($data["gaji"]);
  $tunjangan = htmlspecialchars($data["tunjangan"]);

  $query = "INSERT INTO jabatan VALUES ('$kode', '$nama', '$gaji', '$tunjangan')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

// Edit Jabatan

function editjabatan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["kode"]);
  $nama = htmlspecialchars($data["nama"]);
  $gaji = htmlspecialchars($data["gaji"]);
  $tunjangan = htmlspecialchars($data["tunjangan"]);

  $query = "UPDATE jabatan SET nama_jabatan = '$nama', gaji_pokok = '$gaji', tunjangan_jabatan = '$tunjangan' WHERE kode_jabatan = '$kode'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

// Hapus Jabatan

function hapusjabatan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["id"]);

  mysqli_query($conn, "DELETE FROM jabatan WHERE kode_jabatan = '$kode'");
  return mysqli_affected_rows($conn);
}

// Tambah Data golongan

function tambahgolongan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["kode"]);
  $nama = htmlspecialchars($data["nama"]);
  $tunjangansi = htmlspecialchars($data["tunjangansi"]);
  $tunjangananak = htmlspecialchars($data["tunjangananak"]);
  $um = htmlspecialchars($data["um"]);
  $ul = htmlspecialchars($data["ul"]);
  $askes = htmlspecialchars($data["askes"]);

  $query = "INSERT INTO golongan VALUES ('$kode', '$nama', '$tunjangansi', '$tunjangananak', '$um', '$ul', '$askes')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

// Edit golongan

function editgolongan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["kode"]);
  $nama = htmlspecialchars($data["nama"]);
  $tunjangansi = htmlspecialchars($data["tunjangansi"]);
  $tunjangananak = htmlspecialchars($data["tunjangananak"]);
  $um = htmlspecialchars($data["um"]);
  $ul = htmlspecialchars($data["ul"]);
  $askes = htmlspecialchars($data["askes"]);

  $query = "UPDATE golongan SET nama_golongan = '$nama', tunjangan_si = '$tunjangansi', tunjangan_anak = '$tunjangananak', uang_makan = '$um', uang_lembur = '$ul', askes = '$askes' WHERE kode_golongan = '$kode'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

// Hapus Golongan

function hapusgolongan($data)
{
  global $conn;

  $kode = htmlspecialchars($data["kode"]);

  mysqli_query($conn, "DELETE FROM golongan WHERE kode_golongan = '$kode'");
  return mysqli_affected_rows($conn);
}

// Hapus User

function hapususer($data)
{
  global $conn;

  $id = htmlspecialchars($data["id"]);
  $nip = mysqli_query($conn, "SELECT nip FROM user WHERE id = $id");

  foreach ($nip as $d) {
    $_SESSION['hapuskehadiran'] = $d['nip'];
  }

  mysqli_query($conn, "DELETE FROM user WHERE id = $id");

  return mysqli_affected_rows($conn);
}

function hapuskehadiran($nip)
{
  global $conn;

  $cek = mysqli_query($conn, "SELECT * FROM master_gaji WHERE nip = '$nip'");

  if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "DELETE FROM master_gaji WHERE nip = '$nip'");
    return mysqli_affected_rows($conn);
  } else {
    return 1;
  }
}

// Registrasi

function registrasi($data)
{
  global $conn;
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $nama = htmlspecialchars(stripslashes($data["nama"]));
  $namap = strtok($nama, " ");
  $date = htmlspecialchars($data["date"]);
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));
  $na = strtoupper(substr($nama, 0, 1));
  $nama = $na . substr($nama, 1);

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
  $njay = mysqli_query($conn, "SELECT * FROM `USER` WHERE id IN (SELECT MAX(id) FROM `USER` WHERE level = 'pegawai')");
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

  mysqli_query($conn, "INSERT INTO user VALUES('', '$nip', '$nama', '$namap', '$date', '', '', '$username', '$url', 'Belum Diatur', 'Belum Diatur', '', '', '$password', 'Pegawai', '', '$urut')");
  return mysqli_affected_rows($conn);
}

function umur($tanggal)
{
  $today = date('Y-m-d');
  $umur = date_diff(date_create($tanggal), date_create($today));
  $umur = $umur->format('%y');

  return $umur;
}

function tambahuser($data)
{
  global $conn;
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $status = htmlspecialchars(strtolower(stripslashes($data["status"])));
  $jmlh = htmlspecialchars(strtolower(stripslashes($data["jmlh"])));
  $gol = htmlspecialchars(stripslashes($data["gol"]));
  $jab = htmlspecialchars(stripslashes($data["jab"]));
  $nama = htmlspecialchars(strtolower(stripslashes($data["nama"])));
  $namap = strtok($nama, " ");
  $date = htmlspecialchars($data["date"]);
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));
  $cover = upload();
  if (!$cover) {
    return false;
  }

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

  mysqli_query($conn, "INSERT INTO user VALUES('', '$nip', '$nama', '$namap', '$date', '$email', '$no', '$username', '$url', '$jab', '$gol', '$status', $jmlh, '$password', 'Pegawai', '$cover', '$urut')");
  return mysqli_affected_rows($conn);
}

function foto($data)
{
  global $conn;
  $id = $_SESSION['jir'];
  $cover = upload();
  if (!$cover) {
    return false;
  }

  mysqli_query($conn, "UPDATE user SET pic='$cover' WHERE url='$id'");
  return mysqli_affected_rows($conn);
}

function pendataan($data)
{
  global $conn;
  $id = $_SESSION['jir'];
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $jmlh = htmlspecialchars(strtolower(stripslashes($data["jmlh"])));
  $status = htmlspecialchars(stripslashes($data["status"]));

  mysqli_query($conn, "UPDATE `user` SET `email` = '$email', `no` = '$no', `status` = '$status', `jmlh` = $jmlh WHERE `url` = '$id'");
  return mysqli_affected_rows($conn);
}

function upload()
{
  $nama = $_FILES["cover"]["name"];
  $ukuran = $_FILES["cover"]["size"];
  $error = $_FILES["cover"]["error"];
  $tmpName = $_FILES["cover"]["tmp_name"];

  if ($error === 4) {
    echo "<script>
            alert ('Silakan Upload Cover Ceritanya!')
          </script>";
    return false;
  }

  $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
  $ekstensiGambar = explode(".", $nama);
  $ekstensiGambar = strtolower(end($ekstensiGambar));

  if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo "<script>
            alert ('Silakan Upload Gambar Dengan Ekstensi jpg, jpeg atau png!')
          </script>";
    return false;
  }

  if ($ukuran > 1000000) {
    echo "<script>
            alert ('Ukuran Gambar Terlalu Besar!')
          </script>";
    return false;
  }

  $namaBaru = uniqid();
  $namaBaru .= ".";
  $namaBaru .= $ekstensiGambar;

  move_uploaded_file($tmpName, '../img/user/' . $namaBaru);
  return $namaBaru;
}

function edituser($data)
{
  global $conn;

  $id = $_SESSION['peg'];
  // $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  // $url = substr($d, 0, 10);
  // $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $status = htmlspecialchars(strtolower(stripslashes($data["status"])));
  $jmlh = htmlspecialchars(strtolower(stripslashes($data["jmlh"])));
  $gol = htmlspecialchars(strtoupper(stripslashes($data["gol"])));
  $jab = htmlspecialchars(strtoupper(stripslashes($data["jab"])));
  $nama = htmlspecialchars(strtolower(stripslashes($data["nama"])));
  $namap = strtok($nama, " ");
  $date = htmlspecialchars($data["date"]);
  $gambarLama = $data['gambarLama'];

  // Cek apakah user pilih gambar baru atau tidak

  if ($_FILES['cover']['error'] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE user SET nama = '$nama', namapanggilan = '$namap', tgl = '$date', email = '$email', no = '$no', kode_jabatan = '$jab', kode_golongan='$gol', status='$status', jmlh='$jmlh', pic='$gambar' WHERE id = '$id'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function editprofil($data)
{
  global $conn;

  $id = $_SESSION["prof"];
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $status = htmlspecialchars(strtolower(stripslashes($data["status"])));
  $jmlh = htmlspecialchars(strtolower(stripslashes($data["jmlh"])));
  $nama = htmlspecialchars(strtolower(stripslashes($data["nama"])));
  $namap = htmlspecialchars(strtolower(stripslashes($data["namap"])));
  $date = htmlspecialchars($data["date"]);
  $gambarLama = $data['gambarLama'];

  // Cek apakah user pilih gambar baru atau tidak

  if ($_FILES['cover']['error'] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE user SET nama = '$nama', namapanggilan = '$namap', tgl = '$date', email = '$email', no = '$no', status='$status', jmlh='$jmlh', pic='$gambar' WHERE id = '$id'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function edituserdata($data)
{
  global $conn;

  $id = $_SESSION['peg'];
  $usernameUser = $_SESSION['njay'];
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));

  // Cek apakah ganti username atau tidak

  if ($username == $usernameUser) {
    $username = $username;
  } else {
    // Cek username sudah dipakai atau belum

    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
      echo "<script>alert('Username sudah terdaftar!');</script>";
      return false;
    }
  }

  // Konsirmasi Password

  if ($password !== $konfirmasi) {
    echo "<script>alert('Konfirmasi password tidak sesuai');</script>";
    return false;
  }

  // Enkripsi password

  $password = password_hash($password, PASSWORD_DEFAULT);

  $query = "UPDATE user SET username = '$username', url = '$url', password = '$password' WHERE id = '$id'";
  mysqli_query($conn, $query);
  $_SESSION["jir"] = $url;
  $_SESSION["njay"] = $username;

  return mysqli_affected_rows($conn);
}

function edituserdata2($data)
{
  global $conn;

  $id = $_SESSION['peg'];
  $usernameUser = $_SESSION['username2'];
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));

  // Cek apakah ganti username atau tidak

  if ($username == $usernameUser) {
    $username = $username;
  } else {
    // Cek username sudah dipakai atau belum

    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
      echo "<script>alert('Username sudah terdaftar!');</script>";
      return false;
    }
  }

  // Konsirmasi Password

  if ($password !== $konfirmasi) {
    echo "<script>alert('Konfirmasi password tidak sesuai');</script>";
    return false;
  }

  // Enkripsi password

  $password = password_hash($password, PASSWORD_DEFAULT);

  $query = "UPDATE user SET username = '$username', url = '$url', password = '$password' WHERE id = '$id'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function simpankehadiran($data)
{
  global $conn;

  $bulan = $data["bulan"];
  $nip = $data["nip"];
  $masuk = $data["masuk"];
  $sakit = $data["sakit"];
  $izin = $data["izin"];
  $alpha = $data["alpha"];
  $lembur = $data["lembur"];
  $potongan = $data["potongan"];

  $count = count($nip);

  $sql = "INSERT INTO master_gaji (bulan, nip, masuk, sakit, izin, alpha, lembur, potongan) VALUES ";

  for ($i = 0; $i < $count; $i++) {
    $sql .= "('{$bulan[$i]}','{$nip[$i]}','{$masuk[$i]}','{$sakit[$i]}','{$izin[$i]}','{$alpha[$i]}','{$lembur[$i]}','{$potongan[$i]}')";
    $sql .= ",";
  }

  $sql = rtrim($sql, ",");

  mysqli_query($conn, $sql);

  return mysqli_affected_rows($conn);
}

function editkehadiran($data)
{
  global $conn;

  $bulan = $data["bulan"];
  $id = $data["id"];
  $nip = $data["nip"];
  $masuk = $data["masuk"];
  $sakit = $data["sakit"];
  $izin = $data["izin"];
  $alpha = $data["alpha"];
  $lembur = $data["lembur"];
  $potongan = $data["potongan"];

  $count = count($nip);

  for ($i = 0; $i < $count; $i++) {
    mysqli_query($conn, "UPDATE master_gaji SET masuk='" . $masuk[$i] . "', sakit='" . $sakit[$i] . "', izin='" . $izin[$i] . "', alpha='" . $alpha[$i] . "', lembur='" . $lembur[$i] . "', potongan='" . $potongan[$i] . "' WHERE bulan = '" . $bulan[$i] . "' AND nip='" . $nip[$i] . "';");
  }

  return 1;
}

function tambahadmin($data)
{
  global $conn;
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $nama = htmlspecialchars(strtolower(stripslashes($data["nama"])));
  $namap = strtok($nama, " ");
  $date = htmlspecialchars($data["date"]);
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));
  $cover = upload();
  if (!$cover) {
    return false;
  }

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

  $njay = mysqli_query($conn, "SELECT * FROM `USER` WHERE id IN (SELECT MAX(id) FROM `USER` WHERE level = 'Admin')");
  foreach ($njay as $d) {
    $lastNIP = $d['nip'];
  }

  $nip = $lastNIP + 1;

  // Tambah user baru ke database

  mysqli_query($conn, "INSERT INTO user VALUES('', '$nip', '$nama', '$namap', '$date', '$email', '$no', '$username', '$url', 'Belum Diatur', 'Belum Diatur', 'Belum Diatur', 0, '$password', 'Admin', '$cover', '$nip')");
  return mysqli_affected_rows($conn);
}

function tambahsadmin($data)
{
  global $conn;
  $d = str_replace("e", "", hash("sha256", htmlspecialchars(strtolower(stripslashes($data["username"])))));
  $url = substr($d, 0, 10);
  $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $nama = htmlspecialchars(stripslashes($data["nama"]));
  $namap = strtok($nama, " ");
  $date = htmlspecialchars($data["date"]);
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
  $konfirmasi = htmlspecialchars(mysqli_real_escape_string($conn, $data["konfirmasi"]));
  $cover = upload();
  if (!$cover) {
    return false;
  }

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

  $njay = mysqli_query($conn, "SELECT * FROM `USER` WHERE id IN (SELECT MAX(id) FROM `USER` WHERE level = 'Admin')");
  foreach ($njay as $d) {
    $lastNIP = $d['nip'];
  }

  $nip = $lastNIP + 1;

  // Tambah user baru ke database

  mysqli_query($conn, "INSERT INTO user VALUES('', '$nip', '$nama', '$namap', '$date', '$email', '$no', '$username', '$url', 'Belum Diatur', 'Belum Diatur', 'Belum Diatur', 0, '$password', 'Super Admin', '$cover', '$nip')");
  return mysqli_affected_rows($conn);
}

function editadmin($data)
{
  global $conn;

  $id = $_SESSION['peg'];
  $no = htmlspecialchars(strtolower(stripslashes($data["no"])));
  $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
  $nama = htmlspecialchars(strtolower(stripslashes($data["nama"])));
  $date = htmlspecialchars($data["date"]);
  $gambarLama = $data['gambarLama'];

  // Cek apakah user pilih gambar baru atau tidak

  if ($_FILES['cover']['error'] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE user SET nama = '$nama', tgl = '$date', email = '$email', no = '$no', pic='$gambar' WHERE id = '$id'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function rupiah($data)
{
  $uang = "Rp. " . number_format($data, 0, ',', '.') . ",00";
  return $uang;
}

function bulan($bulan)
{
  switch ($bulan) {
    case '1':
      $bulan = 'Januari';
      break;
    case '2':
      $bulan = 'Februari';
      break;
    case '3':
      $bulan = 'Maret';
      break;
    case '4':
      $bulan = 'April';
      break;
    case '5':
      $bulan = 'Mei';
      break;
    case '6':
      $bulan = 'Juni';
      break;
    case '7':
      $bulan = 'Juli';
      break;
    case '8':
      $bulan = 'Agustus';
      break;
    case '9':
      $bulan = 'September';
      break;
    case '10':
      $bulan = 'Oktober';
      break;
    case '11':
      $bulan = 'November';
      break;
    case '12':
      $bulan = 'Desember';
      break;
  }

  return $bulan;
}

function tanggal($date)
{
  $tanggal = substr($date, 8, 2);
  $bulan = bulan(substr($date, 5, 2));
  $tahun = substr($date, 0, 4);

  $tanggal = $tanggal . " " . $bulan . " " . $tahun;

  return $tanggal;
}

function caripegawai($keyword)
{
  $query = "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE nip LIKE '%$keyword%' OR nama LIKE '%$keyword%' AND user.level = 'pegawai' ORDER BY urut ASC";
  return query($query);
}
