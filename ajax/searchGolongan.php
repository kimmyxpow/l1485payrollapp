<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$query = "SELECT * FROM golongan
          WHERE nama_golongan LIKE '%$keyword%' AND NOT kode_golongan = 'Belum diatur'
          OR kode_golongan LIKE '%$keyword%' AND NOT kode_golongan = 'Belum diatur'
          ORDER BY kode_golongan ASC";

$data = mysqli_query($conn, $query);

?>

<table class="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode</th>
      <th>Nama Golongan</th>
      <th>Tunjangan S/I</th>
      <th>Tunjangan Anak</th>
      <th>Uang Makan</th>
      <th>Uang Lembur</th>
      <th>Askes</th>
      <?php if ($levelsaya == "Pegawai") {
      } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
        <th>Tools</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php
    foreach ($data as $d) {
    ?>
      <tr>
        <td data-label="No"><?= $i; ?></td>
        <td data-label="Kode"><?= $d["kode_golongan"]; ?></td>
        <td data-label="Nama Golongan"><?= $d["nama_golongan"]; ?></td>
        <td data-label="Tunjangan S/I"><?= rupiah($d['tunjangan_si']); ?></td>
        <td data-label="Tunjangan Anak"><?= rupiah($d["tunjangan_anak"]); ?></td>
        <td data-label="Uang Makan"><?= rupiah($d["uang_makan"]); ?></td>
        <td data-label="Uang Lembur"><?= rupiah($d["uang_lembur"]); ?></td>
        <td data-label="Askes"><?= rupiah($d["askes"]); ?></td>
        <?php if ($levelsaya == "Pegawai") {
        } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <td data-label="Tools">
            <form method="post" action="">
              <button type="submit" name="edit" class="tombol-edit">Edit</button>
              <input type="hidden" name="nama" value="<?= $d["nama_golongan"]; ?>">
              <input type="hidden" name="kode" value="<?= $d["kode_golongan"]; ?>">
              <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
            </form>
          </td>
        <?php } ?>
      </tr>
      <?php $i++; ?>
    <?php } ?>

    <?php if (mysqli_num_rows($data) <= 0) { ?>
      <tr>
        <td colspan="9" style="text-align: left;">Maaf kami tidak menemukan golongan dengan nama atau kode <strong style="text-transform: capitalize;">'<?= $keyword; ?>'</strong>.</td>
      </tr>
    <?php } ?>
  </tbody>
</table>