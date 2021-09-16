<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}

include '../function/functions.php';

$data = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.level = 'pegawai' ORDER BY nama ASC");

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
  <title>Laporan Data Pegawai | L-1485</title>
</head>

<body>
  <h2 style="text-align: center;">L-1485 Company</h2>
  <p style="text-align: center;">LAPORAN DATA PEGAWAI</p>
  <hr>
  <table class="font-size-14" border="1" cellpadding="4" cellspacing="0" width="100%">
    <tr>
      <th>No</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>Golongan</th>
      <th>Status</th>
      <th>Jumlah Anak</th>
    </tr>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $d['nip']; ?></td>
        <td style="text-transform: capitalize;"><?= $d['nama']; ?></td>
        <td><?= $d['nama_jabatan']; ?></td>
        <td><?= $d['nama_golongan']; ?></td>
        <td><?= $d['status']; ?></td>
        <td><?= $d['jmlh']; ?></td>
        <?php $i++ ?>
      </tr>
    <?php } ?>

    <?php if (mysqli_num_rows($data) == 0) { ?>
      <tr>
        <td colspan="7">Maaf Data Kosong!</td>
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
        <p>_________________________</p>
      </td>
    </tr>
  </table>
  <a href="#" class="no-print" onclick="window.print();">Cetak/Print</a>
</body>

</html>