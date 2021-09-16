<?php
require '../function/functions.php';

$keyword = $_GET['keyword'];
$levelsaya = $_GET['level'];

$query = "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user 
          INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan 
          INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan 
          WHERE user.nip LIKE '%$keyword%' AND user.level = 'Pegawai' 
          OR user.nama LIKE '%$keyword%' AND user.level = 'Pegawai'
          OR jabatan.nama_jabatan LIKE '%$keyword%' AND user.level = 'Pegawai'
          OR golongan.nama_golongan LIKE '%$keyword%' AND user.level = 'Pegawai'
          OR user.email LIKE '%$keyword%' AND user.level = 'Pegawai'
          ORDER BY user.urut ASC";

$golongan = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.level = 'pegawai' ORDER BY urut ASC");

$data = mysqli_query($conn, $query);

?>

<table class="table-4">
  <thead>
    <tr>
      <th>No</th>
      <th>Foto</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Jabatan</th>
      <th>Golongan</th>
      <?php if ($levelsaya == "Pegawai") { ?>
        <th>Tool</th>
      <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
        <th>Detail</th>
        <th>Tools</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($data as $d) { ?>
      <?php if ($levelsaya == "Pegawai") { ?>
        <tr>
          <td data-label="No"><?= $i; ?></td>
          <td data-label="Foto">
            <img src="../img/user/<?= $d["pic"]; ?>" alt="<?= $d["nama"]; ?>">
          </td>
          <td data-label="NIP"><?= $d["nip"]; ?></td>
          <td data-label="Nama" style="text-transform: capitalize;"><?= $d["nama"]; ?></td>
          <td data-label="Email" style="text-transform: lowercase;"><?= $d["email"]; ?></td>
          <td data-label="Kode Jabatan"><?= $d["nama_jabatan"]; ?></td>
          <td data-label="Kode Golongan">Hanya Admin</td>
          <td data-label="Detail">
            <form method="post" action="">
              <input type="hidden" name="username" value="<?= $d["username"]; ?>">
              <input type="hidden" name="level" value="<?= $d["level"]; ?>">
              <button type="submit" class="tombol-2" name="detail">Detail</button>
            </form>
          </td>
          <?php if ($levelsaya == "Pegawai") {
          } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <td data-label="Tools">
              <form method="post" action="">
                <button type="submit" name="edit" class="tombol-edit">Edit</button>
                <input type="hidden" name="id" value="<?= $d["id"]; ?>">
                <input type="hidden" name="nama" value="<?= $d["nama"]; ?>">
                <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
              </form>
            </td>
          <?php } ?>
        </tr>
      <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
        <tr>
          <td data-label="No"><?= $i; ?></td>
          <td data-label="Foto">
            <img src="../img/user/<?= $d["pic"]; ?>" alt="<?= $d["nama"]; ?>">
          </td>
          <td data-label="NIP"><?= $d["nip"]; ?></td>
          <td data-label="Nama" style="text-transform: capitalize;"><?= $d["nama"]; ?></td>
          <td data-label="Email" style="text-transform: lowercase;"><?= $d["email"]; ?></td>
          <td data-label="Kode Jabatan"><?= $d["nama_jabatan"]; ?></td>
          <td data-label="Kode Golongan"><?= $d["nama_golongan"]; ?></td>
          <td data-label="Detail">
            <form method="post" action="">
              <input type="hidden" name="username" value="<?= $d["username"]; ?>">
              <input type="hidden" name="level" value="<?= $d["level"]; ?>">
              <button type="submit" class="tombol-2" name="detail">Detail</button>
            </form>
          </td>
          <?php if ($levelsaya == "Pegawai") {
          } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <td data-label="Tools">
              <form method="post" action="">
                <button type="submit" name="edit" class="tombol-edit">Edit</button>
                <input type="hidden" name="id" value="<?= $d["id"]; ?>">
                <input type="hidden" name="nama" value="<?= $d["nama"]; ?>">
                <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
              </form>
            </td>
          <?php } ?>
        </tr>
      <?php } ?>
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