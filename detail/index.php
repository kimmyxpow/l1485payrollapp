<?php include '../modules/nav.php'; ?>

<?php

if (!isset($_SESSION['detail'])) {
  header("Location: ../home/");
  exit;
} else {
  $asal = $_SESSION['detail'];
  if (!isset($_GET['username'])) {
    header("Location: ../$asal");
    exit;
  }
}

$id = $_GET['username'];
$dataUser = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.username='$id'");

$_SESSION['kembali'] = "detail/?username=$id";

$kembali = $_SESSION['kembali'];
$level = $_SESSION['level'];

if (mysqli_num_rows($dataUser) == 0) {
  header("Location: ../$asal");
  exit;
}

if (isset($_POST["hapus"])) {
  $nama = htmlspecialchars($_POST["nama"]);
  if (hapususer($_POST) > 0) {
    echo "<script>
                  alert ('User $nama berhasil dihapus!')
                  document.location.href = '../$kembali'
            </script>";
  } else {
    echo "<script>
                alert ('User $nama gagal dihapus!')
                document.location.href = '../$kembali'
          </script>";
  }
}

if (isset($_POST['edit'])) {
  $_SESSION['peg'] = htmlspecialchars($_POST["id"]);
  if ($level == "Pegawai") {
    echo "<script>
                document.location.href = '../pegawai/?view=edit'
          </script>";
  } elseif ($level == "Admin") {
    echo "<script>
                document.location.href = '../data-admin/?view=edit'
          </script>";
  } elseif ($level == "Super Admin") {
    echo "<script>
                document.location.href = '../data-super-admin/?view=edit'
          </script>";
  }
}
?>

<?php foreach ($dataUser as $d) { ?>
  <div class="container">
    <header>
      <h1>Profil User (@<?= $d['username']; ?>)</h1>
    </header>
    <div class="row card">
      <div class="col-md-4">
        <div class="container-items">
          <img src="../img/vector/data-pegawai.png" alt="Hero Image">
        </div>
      </div>
      <div class="col-md-8">
        <div class="container-items">
          <?php foreach ($name as $nm) { ?>
            <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
          <?php } ?>
          <p>Selamat datang di profil @<?= $d['username']; ?>, beberapa data disembunyikan dan hanya dapat dilihat oleh admin.</p>
        </div>
      </div>
    </div>
    <div class="row card detail">
      <div class="col-lg-2">
        <div class="user-foto">
          <img src="../img/user/<?= $d['pic']; ?>" alt="<?= $d['nama']; ?>">
        </div>
      </div>
      <div class="col-lg-4">
        <div class="deskripsi">
          <h2><?= $d['nama']; ?></h2>
          <?php if ($d['level'] == "Pegawai") { ?>
            <p><?= $d['nama_jabatan']; ?></p>
          <?php } elseif ($d['level'] == "Admin") { ?>
            <p><?= $d['level']; ?></p>
          <?php } elseif ($d['level'] == "Super Admin") { ?>
            <p><?= $d['level']; ?></p>
          <?php } ?>

          <?php if ($d['level'] == "Pegawai") { ?>
            <?php if ($levelsaya == "Pegawai") { ?>
            <?php } else { ?>
              <div class="detail-button">
                <form method="post" action="">
                  <input type="hidden" name="id" value="<?= $d['id']; ?>">
                  <input type="hidden" name="nama" value="<?= $d['nama']; ?>">
                  <button type="submit" name="edit" class="tombol-edit">Edit</button>
                  <button type="submit" name="hapus" class="tombol-hapus">Hapus</button>
                </form>
              </div>
            <?php } ?>
          <?php } elseif ($d['level'] == "Admin") { ?>
            <?php if ($levelsaya == "Pegawai") { ?>
            <?php } else { ?>
              <div class="detail-button">
                <form method="post" action="">
                  <input type="hidden" name="id" value="<?= $d['id']; ?>">
                  <input type="hidden" name="nama" value="<?= $d['nama']; ?>">
                  <button type="submit" name="edit" class="tombol-edit">Edit</button>
                  <button type="submit" name="hapus" class="tombol-hapus">Hapus</button>
                </form>
              </div>
            <?php } ?>
          <?php } elseif ($d['level'] == "Super Admin") { ?>
            <?php if ($levelsaya == "Pegawai" || $levelsaya == "Admin") { ?>
            <?php } else { ?>
              <div class="detail-button">
                <form method="post" action="">
                  <input type="hidden" name="id" value="<?= $d['id']; ?>">
                  <input type="hidden" name="nama" value="<?= $d['nama']; ?>">
                  <button type="submit" name="edit" class="tombol-edit">Edit</button>
                  <button type="submit" name="hapus" class="tombol-hapus">Hapus</button>
                </form>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="col-lg-6">
        <?php if ($d['level'] == "Pegawai") { ?>
          <?php if ($levelsaya == "Pegawai") { ?>
            <div class="informasi">
              <div class="data-informasi">
                <p class="field">NIP</p>
                <p class="text-gray"><?= $d['nip']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Age</p>
                <p class="text-gray"><?= umur($d['tgl']) . " (" . $d['tgl'] . ")"; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Email</p>
                <p class="text-gray"><?= $d['email']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">No. Tlp.</p>
                <p class="text-gray"><?= $d['no']; ?></p>
              </div>
            </div>
          <?php } else { ?>
            <div class="informasi">
              <div class="data-informasi">
                <p class="field">NIP</p>
                <p class="text-gray"><?= $d['nip']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Golongan</p>
                <p class="text-gray"><?= $d['nama_golongan']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Age</p>
                <p class="text-gray"><?= umur($d['tgl']) . " (" . $d['tgl'] . ")"; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Status</p>
                <p class="text-gray"><?= $d['status']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">J. Anak</p>
                <p class="text-gray"><?= $d['jmlh']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">Email</p>
                <p class="text-gray"><?= $d['email']; ?></p>
              </div>
              <div class="data-informasi">
                <p class="field">No. Tlp.</p>
                <p class="text-gray"><?= $d['no']; ?></p>
              </div>
            </div>
          <?php } ?>
        <?php } elseif ($d['level'] == "Admin" || $d['level'] == "Super Admin") { ?>
          <div class="informasi">
            <div class="data-informasi">
              <p class="field">Age</p>
              <p class="text-gray"><?= umur($d['tgl']) . " (" . $d['tgl'] . ")"; ?></p>
            </div>
            <div class="data-informasi">
              <p class="field">Email</p>
              <p class="text-gray"><?= $d['email']; ?></p>
            </div>
            <div class="data-informasi">
              <p class="field">No. Tlp.</p>
              <p class="text-gray"><?= $d['no']; ?></p>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>
<?php include '../modules/footer.php'; ?>