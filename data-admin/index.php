<?php
include '../modules/nav.php';
if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}
?>
<div class="container">
  <?php
  $view = isset($_GET['view']) ? $_GET['view'] : null;
  switch ($view) {
    default:
      $admin = mysqli_query($conn, "SELECT * FROM user WHERE level='admin' ORDER BY nama ASC");
      $_SESSION['kembali'] = "data-admin";

      if (isset($_POST["hapus"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        if (hapususer($_POST) > 0) {
          echo "<script>
                        alert ('User $nama telah berhasil terhapus!')
                        document.location.href = '../data-admin/'
                     </script>";
        } else {
          echo "<script>
                        alert ('Gagal menghapus user $nama!')
                        document.location.href = '../data-admin/'
                     </script>";
        }
      }

      if (isset($_POST['edit'])) {
        $_SESSION['peg'] = htmlspecialchars($_POST["id"]);
        echo "<script>
								document.location.href = '../data-admin/?view=edit'
						</script>";
      }

      if (isset($_POST['detail'])) {
        $_SESSION['detail'] = "data-admin";
        $_SESSION['level'] = $_POST['level'];
        $u = $_POST['username'];
        echo "<script>
                        document.location.href = '../detail/?username=$u'
                </script>";
      }

      foreach ($admin as $d) {
        $count[] = $d['nip'];
      }
      if (mysqli_num_rows($admin) > 0) {
        $jumlahData = count($count);
      } else {
        $jumlahData = 0;
      }
  ?>
      <header>
        <h1>Data Admin</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/data-users.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Page data admin menyimpan data semua admin di aplikasi ini. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> admin di L-1485.</p>
          </div>
        </div>
      </div>
      <div class="row tabel-data-admin">
        <div class="row">
          <div class="col-md-6">
            <form class="ini-search" method="post" action="">
              <button type="submit" name="cari" id="tombol-cari"><i class="fas fa-search"></button></i>
              <input class="search" type="text" name="keyword" placeholder="Cari Admin..." id="keyword" autocomplete="off">
            </form>
          </div>
          <?php if ($levelsaya == "Pegawai") { ?>
          <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <div class="col-md-6">
              <a href="../data-admin/?view=tambah" class="tombol">&plus; Tambah Data</a>
            </div>
          <?php } ?>
        </div>
        <div class="row">
          <div class="table-container" id="table-container">
            <table class="table-3">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Foto</th>
                  <th>Email</th>
                  <th>Nama Lengkap</th>
                  <th>Role</th>
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
                <?php foreach ($admin as $adm) { ?>
                  <tr>
                    <td data-label="No"><?= $i; ?></td>
                    <td data-label="Foto">
                      <img src="../img/user/<?= $adm["pic"]; ?>" alt="<?= $adm["nama"]; ?>">
                    </td>
                    <td data-label="Email"><?= $adm["email"]; ?></td>
                    <td data-label="Nama" style="text-transform: capitalize;"><?= $adm["nama"]; ?></td>
                    <td data-label="Role"><?= $adm["level"]; ?></td>
                    <td data-label="Detail">
                      <form method="post" action="">
                        <input type="hidden" name="username" value="<?= $adm["username"]; ?>">
                        <input type="hidden" name="level" value="<?= $adm["level"]; ?>">
                        <button class="tombol-2" type="submit" name="detail">Detail</button>
                      </form>
                    </td>
                    <?php if ($levelsaya == "Pegawai") { ?>
                    <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
                      <td data-label="Tools">
                        <form method="post" action="">
                          <input type="hidden" name="id" value="<?= $adm["id"]; ?>">
                          <input type="hidden" name="nama" value="<?= $adm["nama"]; ?>">
                          <input type="hidden" name="username" value="<?= $adm["username"]; ?>">
                          <button class="tombol-edit" type="submit" name="edit">Edit</button>
                          <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
                        </form>
                      </td>
                    <?php } ?>
                  </tr>
                  <?php $i++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php
      break;
    case 'tambah':
      if ($levelsaya == "Pegawai") {
        echo "<script>
                document.location.href = '../data-admin/'
              </script>";
      }
      if (isset($_POST["submit"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        if (tambahadmin($_POST) > 0) {
          echo "<script>
                        alert ('User $nama telah berhasil terdaftar!')
                        document.location.href = '../data-admin/'
                     </script>";
        } else {
          echo "<script>
                        alert ('User $nama gagal terdaftar!')
                        document.location.href = '../data-admin/'
                     </script>";
        }
      }
    ?>
      <header>
        <h1>Tambah Data Admin</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/tambah-data.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Kamu bisa menambahkan data admin di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-tambah">
          <form method="post" action="" enctype="multipart/form-data">
            <h2>Silakan Tambahkan Data Dibawah.</h2>
            <div class="input-data">
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="text" name="nama" style="text-transform: capitalize;" id="nama" placeholder="Masukkan User Baru" autocomplete="off" required>
                    <label for="nama"><i class="fas fa-user"></i> Nama</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="date" name="date" id="date" placeholder="Masukkan date User Baru" autocomplete="off" required>
                    <label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="number" name="no" id="no" placeholder="Masukkan no User Baru" autocomplete="off" required>
                    <label for="no"><i class="fas fa-phone-alt"></i> Nomor Tlp.</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="email" name="email" style="text-transform: lowercase;" id="email" placeholder="Masukkan Email User Baru" autocomplete="off" required>
                    <label for="email"><i class="fas fa-at"></i> Email</label>
                  </div>
                </div>
              </div>
              <div class="input-form">
                <input type="text" name="username" style="text-transform: lowercase;" id="username" placeholder="Masukkan User Baru" autocomplete="off" required>
                <label for="username"><i class="fas fa-user-tag"></i> Username</label>
              </div>
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="password" name="password" id="password" placeholder="Masukkan Password User Baru" autocomplete="off" required>
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Ulangi Password User Jabatan Baru" autocomplete="off" required>
                    <label for="konfirmasi"><i class="fas fa-lock"></i> Ulangi Password</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="button-wrap">
              <label class="new-button" for="upload">Upload Foto</label>
              <input type="file" name="cover" id="upload" required>
            </div>
            <a href="../data-admin/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
            <button class="tombol" name="submit"><i class="fas fa-plus"></i> Tambah</button>
          </form>
        </div>
      </div>
    <?php
      break;
    case 'edit':
      if ($levelsaya == "Pegawai") {
        echo "<script>
                document.location.href = '../data-admin/'
              </script>";
      }
      $id  = $_SESSION["peg"];
      $kembali = $_SESSION['kembali'];
      $admn = query("SELECT * FROM user WHERE id = $id")[0];
      if (isset($_POST["submit"])) {
        if (editadmin($_POST) > 0) {
          echo "<script>
                        alert ('Data berhasil di edit!')
                        document.location.href = '../$kembali'
                     </script>";
        } else {
          echo "<script>
                        alert ('Data gagal di edit!')
                        document.location.href = '../$kembali'
                     </script>";
        }
      }
    ?>
      <header>
        <h1>Edit Data Admin</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/Edit-data.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Kamu bisa mengedit data admin di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-edit">
          <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" value="<?= $admn['pic']; ?>" name="gambarLama">
            <h2>Silakan Edit Data Dibawah.</h2>
            <div class="input-data">
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="text" name="nama" style="text-transform: capitalize;" value="<?= $admn['nama']; ?>" id="nama" placeholder="Masukkan User Baru" autocomplete="off" required>
                    <label for="nama"><i class="fas fa-user"></i> Nama</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="date" name="date" id="date" placeholder="Masukkan date User Baru" value="<?= $admn['tgl']; ?>" autocomplete="off" required>
                    <label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="number" name="no" id="no" placeholder="Masukkan no User Baru" value="<?= $admn['no']; ?>" autocomplete="off" required>
                    <label for="no"><i class="fas fa-phone-alt"></i> Nomor Tlp.</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="email" name="email" style="text-transform: lowercase;" id="email" value="<?= $admn['email']; ?>" placeholder="Masukkan Email User Baru" autocomplete="off" required>
                    <label for="email"><i class="fas fa-at"></i> Email</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <img class="foto-edit" src="../img/user/<?= $admn['pic']; ?>" alt="<?= $admn['nama']; ?>">
              </div>
            </div>
            <div class="button-wrap">
              <label class="new-button" for="upload">Foto Baru</label>
              <input type="file" name="cover" id="upload">
            </div>
            <div class="input-data">
              <a href="../<?= $kembali; ?>" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
              <button type="submit" name="submit" class="tombol"><i class="fas fa-edit"></i> Ganti</button>
              <p>Klik <a type="submit" class="link" href="../data-admin/?view=edit-user">disini</a> jika anda ingin mengubah username & password.</p>
            </div>
          </form>
        </div>
      </div>
    <?php
      break;
    case 'edit-user':
      if ($levelsaya == "Pegawai") {
        echo "<script>
                document.location.href = '../data-admin/'
              </script>";
      }
      $kembali = $_SESSION['kembali'];
      $id  = $_SESSION["peg"];
      $inidata = query("SELECT * FROM user WHERE id = $id")[0];
      $kembali = $_SESSION['kembali'];
      if (isset($_POST["submit"])) {
        $_SESSION['username2'] = $_POST['username'];
        if (edituserdata2($_POST) > 0) {
          echo "<script>
										alert ('Data berhasil diedit!')
										document.location.href = '../<?= $kembali; ?>'
								</script>";
        } else {
          echo "
							<script>
										alert ('Data gagal diedit!')
										document.location.href = '../<?= $kembali; ?>'
							</script>";
        }
      }
    ?>
      <header>
        <h1>Edit data pegawai</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/Edit-data.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Kamu bisa mengedit data pegawai di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-edit">
          <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="gambarLama" value="<?= $inidata["pic"]; ?>">
            <h2>Silakan Isi Data Dibawah.</h2>
            <div class="input-data">
              <div class="row">
                <div class="input-form">
                  <input type="text" name="username" id="username" placeholder="Masukkan username Anda" autocomplete="off">
                  <label for="username">
                    <i class="fas fa-user-tag">
                    </i> Username Baru (username lama = <?= $inidata['username']; ?>)
                  </label>
                </div>
              </div>
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="password" name="password" id="password" placeholder="Masukkan password anda" autocomplete="off" required>
                    <label for="password"><i class="fas fa-lock"></i> Password Baru</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Masukkan konfirmasi anda" autocomplete="off" required>
                    <label for="konfirmasi"><i class="fas fa-lock"></i> Ulangi Password</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="input-data">
              <a href="../<?= $kembali; ?>" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
              <button class="tombol" name="submit"><i class="fas fa-edit"></i> Edit</button>
              <p>Klik <a class="link" href="../data-admin/?view=edit">disini</a> jika anda tidak ingin mengubah username & password.</p>
            </div>
          </form>
        </div>
      </div>
  <?php break;
  } ?>
</div>

<script src="../js/searchAdmin.js"></script>
<?php include '../modules/footer.php'; ?>