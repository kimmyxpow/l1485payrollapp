<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}

include '../function/functions.php';

$data = mysqli_query($conn, "SELECT * FROM golongan WHERE NOT kode_golongan = 'Belum Diatur'");

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../img/logo/logoOnly.png">
  <link rel="stylesheet" href="../css/cetak.css">
  <title>Laporan Data Golongan | L-1485</title>
</head>

<body>
  <h2 style="text-align: center;">L-1485 Company</h2>
  <p style="text-align: center;">LAPORAN DATA GOLONGAN</p>
  <hr>
  <table class="font-size-12" border="1" cellpadding="4" cellspacing="0" width="100%">
    <tr>
      <th>No</th>
      <th>Kode Golongan</th>
      <th>Nama Golongan</th>
      <th>Tunj. S/I</th>
      <th>Tunj. Anak</th>
      <th>Uang Makan</th>
      <th>Uang Lembur</th>
      <th>Askes</th>
    </tr>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $d['kode_golongan']; ?></td>
        <td><?= $d['nama_golongan']; ?></td>
        <td><?= rupiah($d['tunjangan_si']); ?></td>
        <td><?= rupiah($d['tunjangan_anak']); ?></td>
        <td><?= rupiah($d['uang_makan']); ?></td>
        <td><?= rupiah($d['uang_lembur']); ?></td>
        <td><?= rupiah($d['askes']); ?></td>
        <?php $i++ ?>
      </tr>
    <?php } ?>

    <?php if (mysqli_num_rows($data) == 0) { ?>
      <tr>
        <td colspan="8">Maaf Data Kosong!</td>
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