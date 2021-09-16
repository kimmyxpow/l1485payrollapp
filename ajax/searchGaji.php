<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$bulantahun = $bulan . $tahun;

$query = "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan, jabatan.gaji_pokok, jabatan.tunjangan_jabatan, 
          IF (user.status = 'Menikah', tunjangan_si, 0) AS tjsi, 
          IF (user.status = 'Menikah', tunjangan_anak, 0) AS tjanak, 
          uang_makan AS um, master_gaji.lembur*uang_lembur AS ul, askes, 
          (gaji_pokok+tunjangan_jabatan+(SELECT tjsi)+(SELECT tjanak)+(SELECT um)+(SELECT ul)+askes) AS pendapatan, potongan, 
          (SELECT pendapatan) - potongan AS totalgaji 
          FROM user INNER JOIN master_gaji ON master_gaji.nip=user.nip 
          INNER JOIN golongan ON golongan.kode_golongan=user.kode_golongan 
          INNER JOIN jabatan ON jabatan.kode_jabatan=user.kode_jabatan 
          WHERE user.nip LIKE '%$keyword%' AND master_gaji.bulan=$bulantahun AND user.level = 'Pegawai' 
          OR user.nama LIKE '%$keyword%' AND master_gaji.bulan=$bulantahun AND user.level = 'Pegawai' 
          ORDER BY user.id ASC";

$query2 = "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan, jabatan.gaji_pokok, jabatan.tunjangan_jabatan, 
          IF (user.status = 'Menikah', tunjangan_si, 0) AS tjsi, 
          IF (user.status = 'Menikah', tunjangan_anak, 0) AS tjanak, 
          uang_makan AS um, master_gaji.lembur*uang_lembur AS ul, askes, 
          (gaji_pokok+tunjangan_jabatan+(SELECT tjsi)+(SELECT tjanak)+(SELECT um)+(SELECT ul)+askes) AS pendapatan, potongan, 
          (SELECT pendapatan) - potongan AS totalgaji 
          FROM user INNER JOIN master_gaji ON master_gaji.nip=user.nip 
          INNER JOIN golongan ON golongan.kode_golongan=user.kode_golongan 
          INNER JOIN jabatan ON jabatan.kode_jabatan=user.kode_jabatan 
          WHERE master_gaji.bulan='$bulantahun' AND user.level='pegawai' 
          ORDER BY user.id ASC";

$data = mysqli_query($conn, $query);
$data2 = mysqli_query($conn, $query2);

if (mysqli_num_rows($data2) > 0) {
  foreach ($data2 as $d2) {
    $count[] = $d2['nip'];
  }

  $jumlahData = count($count);
}

?>

<div class="table-container">
  <table class="table-2">
    <thead>
      <tr>
        <th>No</th>
        <th>Foto</th>
        <th>NIP</th>
        <th>Nama Pegawai</th>
        <th>Jabatan</th>
        <th>Golongan</th>
        <th>Status</th>
        <th>Jumlah Anak</th>
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
      <?php $no = 1; ?>
      <?php foreach ($data as $d) { ?>
        <?php if ($levelsaya == "Pegawai") { ?>
          <tr>
            <td data-label="No"><?= $no; ?></td>
            <td data-label="Foto">
              <img src="../img/user/<?= $d['pic']; ?>" alt="">
            </td>
            <td data-label="NIP"><?= $d['nip']; ?></td>
            <td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
            <td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
            <td data-label="Golongan">Hanya admin</td>
            <td data-label="Status">Hanya admin </td>
            <td data-label="Jumlah Anak">Hanya admin</td>
            <td data-label="Gaji Pokok"><?= rupiah($d['gaji_pokok']); ?></td>
            <td data-label="Tunjangan Jabatan"><?= rupiah($d['tunjangan_jabatan']); ?></td>
            <td data-label="Tunjangan S/I">Hanya admin</td>
            <td data-label="Tunjangan Anak">Hanya admin</td>
            <td data-label="Uang Makan"><?= rupiah($d['um']); ?></td>
            <td data-label="Uang Lembur"><?= rupiah($d['ul']); ?></td>
            <td data-label="Askes"><?= rupiah($d['askes']); ?></td>
            <td data-label="Pendapatan">Hanya admin</td>
            <td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
            <td data-label="Total Gaji">Hanya admin</td>
          </tr>
        <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <tr>
            <td data-label="No"><?= $no; ?></td>
            <td data-label="Foto">
              <img src="../img/user/<?= $d['pic']; ?>" alt="">
            </td>
            <td data-label="NIP"><?= $d['nip']; ?></td>
            <td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
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
        <?php } ?>
        <?php $no++; ?>
      <?php } ?>
      <?php if (mysqli_num_rows($data2) > 0) { ?>
        <?php if (mysqli_num_rows($data) <= 0) { ?>
          <tr>
            <td colspan="11" style="text-align: left;">
              Maaf data (<?= $keyword; ?>) yang anda cari tidak ditemukan!
            </td>
          </tr>
        <?php } ?>
      <?php } else { ?>
        <?php if (mysqli_num_rows($data) == 0) { ?>
          <tr>
            <td colspan="18" style="text-align: left;">
              Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>!
            </td>
          </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row baris-cetak">
  <?php if (mysqli_num_rows($data2) > 0) { ?>
    <?php if (mysqli_num_rows($data) == $jumlahData) { ?>
      <center>
        <a style="margin-bottom: 10px;" href="../cetak-gaji/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Cetak Data Gaji Pegawai</a> <br>
        <a href="../export-data-gaji/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Export Data Gaji Pegawai (Excel)</a>
      </center>
    <?php } ?>
  <?php } ?>
</div>