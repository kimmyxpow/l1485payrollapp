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
      $data = mysqli_query($conn, "SELECT * FROM user WHERE level = 'Pegawai' AND email != '' ORDER BY nama ASC");

      if (isset($_POST["hapus"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        if (hapususer($_POST) > 0) {
          if (hapuskehadiran($_SESSION['hapuskehadiran']) > 0) {
            echo "<script>
									alert ('User $nama berhasil dihapus!')
									document.location.href = '../data-regular-user/'
							</script>";
          } else {
            echo "<script>
									document.location.href = '../data-regular-user/'
							</script>";
          }
        } else {
          echo "<script>
                  alert ('User $nama gagal dihapus!')
                  document.location.href = '../data-regular-user/'
								</script>";
        }
      }

      $_SESSION['kembali'] = "data-regular-user";

      if (isset($_POST['edit'])) {
        $_SESSION['peg'] = htmlspecialchars($_POST["id"]);
        echo "<script>
								document.location.href = '../pegawai/?view=edit'
						</script>";
      }

      if (isset($_POST['detail'])) {
        $_SESSION['detail'] = "data-regular-user";
        $u = $_POST['username'];
        $_SESSION['level'] = $_POST['level'];
        echo "<script>
                        document.location.href = '../detail/?username=$u'
                </script>";
      }

      foreach ($data as $d) {
        $count[] = $d['nip'];
      }
      if (mysqli_num_rows($data) > 0) {
        $jumlahData = count($count);
      } else {
        $jumlahData = 0;
      }
  ?>
      <header>
        <h1>Data Regular User (Pegawai)</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/data-r-user.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Page data regular user menyimpan data orang-orang yang merupakan pegawai/user regular dari aplikasi ini. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> pegawai di L-1485.</p>
          </div>
        </div>
      </div>
      <div class="row tabel-data-admin">
        <div class="row">
          <div class="col-md-6">
            <form class="ini-search" method="post" action="">
              <button type="submit" name="cari" id="tombol-cari"><i class="fas fa-search"></button></i>
              <input class="search" type="text" name="keyword" placeholder="Cari Pegawai..." id="keyword" autocomplete="off">
            </form>
          </div>
          <?php if ($levelsaya == "Pegawai") { ?>
          <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <div class="col-md-6">
              <a href="../data-regular-user/?view=tambah" class="tombol">&plus; Tambah Data</a>
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
                document.location.href = '../data-regular-user/'
              </script>";
      }
      $kembali = $_SESSION['kembali'];
      if (isset($_POST["submit"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        if (tambahuser($_POST) > 0) {
          echo "<script>
											alert ('User $nama berhasil ditambahkan!')
											document.location.href = '../data-regular-user/'
                  </script>";
        } else {
          echo "<script>
											alert ('User $nama gagal ditambahkan!')
											document.location.href = '../data-regular-user/'
                </script>";
        }
      }
    ?>
      <header>
        <h1>Tambah data regular user</h1>
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
            <p>Kamu bisa menambahkan data akun regular/pegawai di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-tambah">
          <form method="post" action="" enctype="multipart/form-data">
            <h2>Silakan Isi Data Dibawah.</h2>
            <div class="input-data">
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="date" name="date" id="date" placeholder="Masukkan date anda" autocomplete="off" required>
                    <label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="text" name="nama" id="nama" placeholder="Masukkan nama anda" autocomplete="off" required>
                    <label for="nama"><i class="fas fa-user"></i> Nama</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md">
                  <div class="input-form">
                    <input type="email" name="email" id="email" placeholder="Masukkan email anda" autocomplete="off" required>
                    <label for="email"><i class="fas fa-at"></i> Email</label>
                  </div>
                </div>
                <div class="col-md">
                  <div class="input-form">
                    <input type="number" name="no" id="no" placeholder="Masukkan no anda" autocomplete="off" required>
                    <label for="no"><i class="fas fa-phone-alt"></i> Nomor Telepon</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="select-form bawah">
                      <select name="jab">
                        <option value="Belum Diatur">- Pilih Jabatan -</option>
                        <?php $jabatan = query("SELECT * FROM jabatan WHERE NOT kode_jabatan = 'Belum Diatur' ORDER BY kode_jabatan ASC"); ?>
                        <?php foreach ($jabatan as $jbt) { ?>
                          <option value="<?= $jbt['kode_jabatan']; ?>"><?= $jbt['kode_jabatan']; ?> - <?= $jbt['nama_jabatan'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="select-form">
                      <select name="gol" id="golongan">
                        <option value="Belum Diatur">- Pilih Golongan -</option>
                        <?php $golongan = query("SELECT * FROM golongan WHERE NOT kode_golongan = 'Belum Diatur' ORDER BY kode_golongan ASC"); ?>
                        <?php foreach ($golongan as $gol) { ?>
                          <option value="<?= $gol['kode_golongan']; ?>"><?= $gol['kode_golongan']; ?> - <?= $gol['nama_golongan'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="select-form">
                      <select name="status">
                        <option value="Belum Diatur">- Pilih Status -</option>
                        <option value="Menikah">Sudah Menikah</option>
                        <option value="Belum Menikah">Belum Menikah</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="input-form">
                    <input type="number" name="jmlh" id="jmlh" max="99" placeholder="Masukkan jmlh Anda" autocomplete="off" required>
                    <label for="jmlh"><i class="fas fa-child"></i> Jumlah Anak</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-form">
                    <input type="text" name="username" id="username" min="6" placeholder="Masukkan Username Anda" autocomplete="off" required>
                    <label for="username"><i class="fas fa-user-tag"></i> Username</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="input-form">
                      <input type="password" name="password" id="password" placeholder="Masukkan Password Anda" min="8" autocomplete="off" required>
                      <label for="password"><i class="fas fa-lock"></i> Password</label>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="input-form">
                      <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Masukkan Konfirmasi Password Anda" autocomplete="off" min="8" required>
                      <label for="konfirmasi"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="button-wrap">
              <label class="new-button" for="upload">Upload Foto</label>
              <input type="file" name="cover" id="upload" required>
            </div>
            <a href="../<?= $kembali; ?>" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
            <button class="tombol" name="submit"><i class="fas fa-plus"></i> Tambah</button>
          </form>
        </div>
      </div>
  <?php
      break;
  } ?>
</div>
<script src="../js/searchPegawai.js"></script>
<?php include '../modules/footer.php'; ?>