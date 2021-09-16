<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$bulantahun = $bulan . $tahun;

$query = "SELECT master_gaji.*, user.nama, user.pic, user.kode_jabatan, user.id, jabatan.nama_jabatan 
          FROM master_gaji INNER JOIN user ON master_gaji.nip=user.nip 
          INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan 
          WHERE user.nip LIKE '%$keyword%' AND master_gaji.bulan=$bulantahun AND user.level = 'Pegawai' 
          OR user.nama LIKE '%$keyword%' AND master_gaji.bulan=$bulantahun AND user.level = 'Pegawai' 
          ORDER BY user.id ASC";

$query2 = "SELECT master_gaji.*, user.nama, user.pic, user.id, user.kode_jabatan, jabatan.nama_jabatan 
          FROM master_gaji INNER JOIN user ON master_gaji.nip=user.nip 
          INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan 
          WHERE master_gaji.bulan=$bulantahun AND user.level = 'Pegawai' 
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

<table class="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Foto</th>
      <th>NIP</th>
      <th>Nama Pegawai</th>
      <th>Jabatan</th>
      <th>Masuk</th>
      <th>Sakit</th>
      <th>Izin</th>
      <th>Alpha</th>
      <th>Lembur</th>
      <th>Potongan</th>
    </tr>
  </thead>
  <?php $no = 1; ?>
  <tbody>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td data-label="No"><?= $no; ?></td>
        <td data-label="Foto">
          <img src="../img/user/<?= $d['pic']; ?>">
        </td>
        <td data-label="NIP"><?= $d['nip']; ?></td>
        <td data-label="Nama Pegawai" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
        <td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
        <td data-label="Masuk"><?= $d['masuk']; ?></td>
        <td data-label="Sakit"><?= $d['sakit']; ?></td>
        <td data-label="Izin"><?= $d['izin']; ?></td>
        <td data-label="Alpha"><?= $d['alpha']; ?></td>
        <td data-label="Lembur"><?= $d['lembur']; ?></td>
        <td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
      </tr>
      <?php $no++ ?>
    <?php } ?>

    <?php if (mysqli_num_rows($data2) > 0) { ?>
      <?php if (mysqli_num_rows($data) <= 0) { ?>
        <tr>
          <td colspan="11" style="text-align: left;">
            Maaf data (<?= $keyword; ?>) yang anda cari tidak ditemukan!
          </td>
        </tr>
      <?php } ?>
      <?php if (mysqli_num_rows($data) == $jumlahData) { ?>
        <?php if ($levelsaya == "Pegawai") {
        } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <tr>
            <td colspan="11"><a href="../kehadiran-pegawai/?view=edit&bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" class="tombol-juga">Edit Data</a></td>
          </tr>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <?php if (mysqli_num_rows($data) == 0) { ?>
        <?php if ($levelsaya == "Pegawai") { ?>
          <tr>
            <td colspan="11" style="text-align: left;">
              Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>!
            </td>
          </tr>
        <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <tr>
            <td colspan="11" style="text-align: left;">
              Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>! Silakan tambahkan data!
            </td>
          </tr>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </tbody>
</table>

<!--  -->