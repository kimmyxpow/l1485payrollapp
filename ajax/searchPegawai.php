<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$query = "SELECT * FROM user
          WHERE nama LIKE '%$keyword%' AND level = 'Pegawai'
          OR email LIKE '%$keyword%' AND level = 'Pegawai'
          ORDER BY nama ASC";

$golongan = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.level = 'pegawai' ORDER BY user.nama ASC");

$data = mysqli_query($conn, $query);

?>

<table class="table-3">
  <thead>
    <tr>
      <th>No</th>
      <th>Foto</th>
      <th>Nama Lengkap</th>
      <th>Email</th>
      <?php if ($levelsaya == "Pegawai") { ?>
        <th>Tools</th>
      <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
        <th>Detail</th>
        <th>Tools</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($data as $adm) { ?>
      <tr>
        <td data-label="No"><?= $i; ?></td>
        <td data-label="Foto">
          <img src="../img/user/<?= $adm["pic"]; ?>" alt="<?= $adm["nama"]; ?>">
        </td>
        <td data-label="Nama Lengkap" style="text-transform: capitalize;"><?= $adm["nama"]; ?></td>
        <td data-label="Email"><?= $adm["email"]; ?></td>
        <td data-label="Detail">
          <form method="post" action="">
            <input type="hidden" name="username" value="<?= $adm["username"]; ?>">
            <input type="hidden" name="level" value="<?= $adm["level"]; ?>">
            <button type="submit" class="tombol-2" name="detail">Detail</button>
          </form>
        </td>
        <?php if ($levelsaya == "Pegawai") { ?>
        <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
          <td data-label="Tools">
            <form method="post" action="">
              <input type="hidden" name="id" value="<?= $adm["id"]; ?>">
              <input type="hidden" name="nama" value="<?= $adm["nama"]; ?>">
              <button class="tombol-edit" type="submit" name="edit">Edit</button>
              <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
            </form>
          </td>
        <?php } ?>
      </tr>
      <?php $i++; ?>
    <?php } ?>
    <?php if (mysqli_num_rows($golongan) <= 0) { ?>
      <tr>
        <td colspan="9" style="text-align: left;">Maaf, belum ada data apapun disini.</td>
      </tr>
    <?php } else { ?>
      <?php if (mysqli_num_rows($data) <= 0) { ?>
        <tr>
          <td colspan="9" style="text-align: left;">Maaf kami tidak menemukan NIP pegawai, nama pegawai, jabatan pegawai, golongan pegawai ataupun email pegawai yang mengandung <strong style="text-transform: capitalize;">'<?= $keyword; ?>'</strong>.</td>
        </tr>
      <?php } ?>
    <?php } ?>
  </tbody>
</table>