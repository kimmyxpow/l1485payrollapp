<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}

include '../function/functions.php';

$data = mysqli_query($conn, "SELECT * FROM jabatan WHERE NOT kode_jabatan = 'Belum diatur'");

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
  <title>Laporan Data Jabatan | L-1485</title>
</head>

<body>
  <h2 style="text-align: center;">L-1485 Company</h2>
  <p style="text-align: center;">LAPORAN DATA JABATAN</p>
  <hr>
  <table class="font-size-16" border="1" cellpadding="4" cellspacing="0" width="100%">
    <tr>
      <th>No</th>
      <th>Kode Jabatan</th>
      <th>Nama Jabatan</th>
      <th>Gaji Pokok</th>
      <th>Tunj. Jabatan</th>
    </tr>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $d['kode_jabatan']; ?></td>
        <td><?= $d['nama_jabatan']; ?></td>
        <td><?= rupiah($d['gaji_pokok']); ?></td>
        <td><?= rupiah($d['tunjangan_jabatan']); ?></td>
        <?php $i++ ?>
      </tr>
    <?php } ?>

    <?php if (mysqli_num_rows($data) == 0) { ?>
      <tr>
        <td colspan="5">Maaf Data Kosong!</td>
      </tr>
    <?php } ?>
  </table>
  <table width="100%">
    <tr>
      <td></td>
      <td width="220px">
        <p class="font-size-16">
          Kota Bogor, <?= tanggal(date("Y-m-d")); ?>. <br> Administrator.
        </p>
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