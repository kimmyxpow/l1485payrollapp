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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Gaji Pegawai</title>

  <!-- My Style -->
  <link rel="stylesheet" href="../css/cetak.css">
  <link rel="icon" href="../img/logo/logoOnly.png">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <link rel="stylesheet" href="../css/css/all.min.css">
</head>

<body>
  <h3>L-1485 Company<br>DAFTAR GAJI PEGAWAI</h3>
  <hr>
  <table>
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
  <br>
  <table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>No</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Jab.</th>
        <th>Gol.</th>
        <th>Status</th>
        <th>J. Anak</th>
        <th>Gaji Pokok</th>
        <th>Tj. Jabatan</th>
        <th>Tj. S/I</th>
        <th>Tj. Anak</th>
        <th>Uang Makan</th>
        <th>Uang Lembur</th>
        <th>Askes</th>
        <th>Pendapatan</th>
        <th>Potongan</th>
        <th>Total Gaji</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan, jabatan.gaji_pokok, jabatan.tunjangan_jabatan, IF (user.status = 'Menikah', tunjangan_si, 0) AS tjsi, IF (user.status = 'Menikah', tunjangan_anak, 0) AS tjanak, uang_makan AS um, master_gaji.lembur*uang_lembur AS ul, askes, (gaji_pokok+tunjangan_jabatan+(SELECT tjsi)+(SELECT tjanak)+(SELECT um)+(SELECT ul)+askes) AS pendapatan, potongan, (SELECT pendapatan) - potongan AS totalgaji FROM user INNER JOIN master_gaji ON master_gaji.nip=user.nip INNER JOIN golongan ON golongan.kode_golongan=user.kode_golongan INNER JOIN jabatan ON jabatan.kode_jabatan=user.kode_jabatan WHERE master_gaji.bulan='$bulantahun' AND user.level='pegawai' ORDER BY user.nama ASC");

      $no = 1;
      ?>
      <?php foreach ($sql as $d) { ?>
        <tr>
          <td data-label="No"><?= $no; ?></td>
          <td data-label="NIP"><?= $d['nip']; ?></td>
          <td data-label="Nama Pegawai" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
          <td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
          <td data-label="Golongan"><?= $d['nama_golongan']; ?></td>
          <td data-label="Status"><?= $d['status']; ?></td>
          <td data-label="Jumlah Anak"><?= $d['jmlh']; ?></td>
          <td data-label="Gaji Pokok"><?= rupiah($d['gaji_pokok']); ?></td>
          <td data-label="Tunjangan Jabatan"><?= rupiah($d['tunjangan_jabatan']); ?></td>
          <td data-label="Tunjangan S/I"><?= rupiah($d['tjsi']); ?></td>
          <td data-label="Tunjangan Anak"><?= rupiah($d['tjanak']); ?></td>
          <td data-label="Uang Makan"><?= rupiah($d['um']); ?></td>
          <td data-label="Uang Lembur"><?= rupiah($d['ul']); ?></td>
          <td data-label="Askes"><?= rupiah($d['askes']); ?></td>
          <td data-label="Pendapatan"><?= rupiah($d['pendapatan']); ?></td>
          <td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
          <td data-label="Total Gaji"><?= rupiah($d['totalgaji']); ?></td>
        </tr>
        <?php $no++; ?>
      <?php } ?>
    </tbody>
  </table>
  <table width="100%">
    <tr>
      <td></td>
      <td width="200px">
        <p>
          Kota Bogor, <?= tanggal(date("Y-m-d")); ?>. <br> Bendahara.
        </p>
        <br>
        <br>
        <br>
        <p>_______________________</p>
      </td>
    </tr>
  </table>
  <a href="#" class="no-print" onclick="window.print();">Cetak/Print</a>
</body>

</html>