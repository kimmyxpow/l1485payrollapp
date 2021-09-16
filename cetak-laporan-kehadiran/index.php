<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}

include '../function/functions.php';

if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
  $bulan = $_GET['bulan'];
  $tahun = $_GET['tahun'];
  $bulantahun = $bulan . $tahun;
} else {
  $bulan = date('m');
  $tahun = date('Y');
  $bulantahun = $bulan . $tahun;
}

$data = mysqli_query($conn, "SELECT user.nip, user.nama, jabatan.nama_jabatan, golongan.nama_golongan, master_gaji.masuk, golongan.uang_makan, golongan.uang_makan * master_gaji.masuk AS total FROM user INNER JOIN master_gaji ON master_gaji.nip=user.nip INNER JOIN jabatan ON jabatan.kode_jabatan=user.kode_jabatan INNER JOIN golongan ON golongan.kode_golongan=user.kode_golongan WHERE master_gaji.bulan='$bulantahun' ORDER BY user.nama ASC");

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cetak.css">
  <link rel="icon" href="../img/logo/logoOnly.png">
  <title>Laporan Data Kehadiran | L-1485</title>
</head>

<body>
  <h2 style="text-align: center;">L-1485 Company</h2>
  <p style="text-align: center;">LAPORAN DATA KEHADIRAN</p>
  <hr>
  <table class="font-size-14">
    <tr>
      <td>Bulan</td>
      <td>:</td>
      <td><?= bulan($bulan); ?></td>
    </tr>
    <tr>
      <td>Tahun</td>
      <td>:</td>
      <td><?= $tahun; ?></td>
    </tr>
  </table>
  <hr>
  <table class="font-size-14" border="1" cellpadding="4" cellspacing="0" width="100%">
    <tr>
      <th>No</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>Golongan</th>
      <th>Jml. Masuk (Hari)</th>
      <th>Uang Makan</th>
      <th>Total</th>
    </tr>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $d['nip']; ?></td>
        <td style="text-transform: capitalize;"><?= $d['nama']; ?></td>
        <td><?= $d['nama_jabatan']; ?></td>
        <td><?= $d['nama_golongan']; ?></td>
        <td><?= $d['masuk']; ?></td>
        <td><?= rupiah($d['uang_makan']); ?></td>
        <td><?= rupiah($d['total']); ?></td>
        <?php $i++ ?>
      </tr>
    <?php } ?>

    <?php if (mysqli_num_rows($data) == 0) { ?>
      <tr>
        <td colspan="8">Belum ada data!</td>
      </tr>
    <?php } ?>
  </table>
  <table width="100%">
    <tr>
      <td></td>
      <td width="220px">
        <p class="font-size-14">
          Kota Bogor, <?= tanggal(date("Y-m-d")); ?>. <br> Administrator.
        </p>
        <br>
        <br>
        <br>
        <br>
        <p>____________________________</p>
      </td>
    </tr>
  </table>
  <a href="#" class="no-print" onclick="window.print();">Cetak/Print</a>
</body>

</html>