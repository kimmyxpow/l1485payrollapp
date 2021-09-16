<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$query = "SELECT * FROM jabatan
          WHERE nama_jabatan LIKE '%$keyword%' AND NOT kode_jabatan = 'Belum diatur'
          OR kode_jabatan LIKE '%$keyword%' AND NOT kode_jabatan = 'Belum diatur'
          ORDER BY kode_jabatan ASC";

$data = mysqli_query($conn, $query);

?>

<table class="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Jabatan</th>
      <th>Nama Jabatan</th>
      <th>Gaji Pokok</th>
      <th>Tunjangan Jabatan</th>
      <?php if ($levelsaya == "Pegawai") {
      } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
        <th>Tools</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($data as $d) { ?>
      <tr>
        <td data-label="No"><?= $i; ?></td>
        <td data-label="Kode Jabatan"><?= $d["kode_jabatan"]; ?></td>
        <td data-label="Nama Jabatan"><?= $d["nama_jabatan"]; ?></td>
        <td data-label="Gaji Pokok"><?= rupiah($d["gaji_pokok"]); ?></td>
        <td data-label="Tunjangan Jabatan"><?= rupiah($d["tunjangan_jabatan"]); ?></td>
        <?php if ($levelsaya == "Pegawai") {
        } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <td data-label="Tools">
            <form method="post" action="">
              <button type="submit" name="edit" class="tombol-edit">Edit</button>
              <input type="hidden" name="id" value="<?= $d["kode_jabatan"]; ?>">
              <input type="hidden" name="nama" value="<?= $d["nama_jabatan"]; ?>">
              <input type="hidden" name="kode" value="<?= $d["kode_jabatan"]; ?>">
              <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
            </form>
          </td>
        <?php } ?>
      </tr>
      <?php $i++; ?>
    <?php } ?>
    <?php if (mysqli_num_rows($data) <= 0) { ?>
      <tr>
        <td colspan="6" style="text-align: left;">Maaf kami tidak menemukan jabatan dengan nama atau kode <strong style="text-transform: capitalize;">'<?= $keyword; ?>'</strong>.</td>
      </tr>
    <?php } ?>
  </tbody>
</table>