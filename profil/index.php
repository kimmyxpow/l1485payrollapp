<?php include '../modules/nav.php'; ?>

<?php
$id = $_SESSION['jir'];
$dataUser = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.url='$id'");

if (mysqli_num_rows($dataUser) == 0) {
  header("Location: ../pegawai/");
  exit;
}

if (isset($_POST['hapus'])) {
  $nama = $_POST['nama'];
  if (hapususer($_POST) > 0) {
    echo "<script>
                  alert ('Akun anda telah berhasil dihapus!')
                  document.location.href = '../login/'
            </script>";
  } else {
    echo "<script>
                alert ('Akun anda gagal dihapus!')
                document.location.href = '../profil/'
          </script>";
  }
}

if (isset($_POST['edit'])) {
  $_SESSION['prof'] = htmlspecialchars($_POST["id"]);
  $_SESSION['peg'] = htmlspecialchars($_POST["id"]);
  echo "<script>
                document.location.href = '../profil/?view=edit'
          </script>";
}
?>

<?php foreach ($dataUser as $d) { ?>
  <div class="container">
    <?php $view = isset($_GET['view']) ? $_GET['view'] : null;
    switch ($view) {
      default: ?>
        <header>
          <h1>Profil Saya (@<?= $d['username']; ?>)</h1>
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
              <p>Kamu bisa mengganti nama sapaanmu di bagian edit jika kamu merasa tidak cocok dengan <strong style="text-transform: capitalize;"> <?= $d['namapanggilan']; ?></strong>.</p>
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
              <div class="detail-button">
                <form method="post" action="">
                  <input type="hidden" name="id" value="<?= $d['id']; ?>">
                  <input type="hidden" name="nama" value="<?= $d['nama']; ?>">
                  <button type="submit" name="edit" class="tombol-edit">Edit</button>
                  <button type="submit" name="hapus" class="tombol-hapus">Hapus Akun</button>
                  <a href="../logout/" class="tombol-logout">Logout</a>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <?php if ($levelsaya == "Pegawai") { ?>
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
            <?php } else { ?>
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
      <?php
        break;
      case 'edit':
        $id = $_SESSION["peg"];
        $_SESSION['kembali'] = "profil";
        $inidata = query("SELECT * FROM user WHERE id = $id")[0];
        if (isset($_POST["submit"])) {
          if (editprofil($_POST) > 0) {
            echo "<script>
          alert('Data berhasil diedit!')
          document.location.href = '../profil/'
        </script>";
          } else {
            echo "
        <script>
          alert('Data gagal diedit!')
          document.location.href = '../profil/'
        </script>";
          }
        }
      ?>
        <header>
          <h1>Edit profil</h1>
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
              <?php if ($levelsaya == "Pegawai") { ?>
                <div class="input-data">
                  <div class="row">
                    <div class="col-md">
                      <div class="input-form">
                        <input type="number" name="nip" id="nip" placeholder="Masukkan NIP Anda" value="<?= $inidata["nip"]; ?>" readonly>
                        <label for="nip"><i class="far fa-id-card"></i> NIP (Tidak bisa diedit)</label>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="input-form">
                        <input type="text" name="namap" id="namap" placeholder="Masukkan namap Anda" value="<?= $inidata["namapanggilan"]; ?>">
                        <label for="nip"><i class="far fa-id-card"></i> Nama sapaan/panggilan dalam aplikasi</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md">
                      <div class="input-form">
                        <input type="date" name="date" id="date" placeholder="Masukkan date anda" value="<?= $inidata["tgl"]; ?>" autocomplete="off" required>
                        <label for="date"><i class="far fa-id-card"></i> Tanggal Lahir</label>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="input-form">
                        <input type="text" name="nama" value="<?= $inidata["nama"]; ?>" id="nama" placeholder="Masukkan nama anda" autocomplete="off" required>
                        <label for="nama"><i class="far fa-user"></i> Nama</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md">
                      <div class="input-form">
                        <input type="email" name="email" id="email" value="<?= $inidata["email"]; ?>" placeholder="Masukkan email anda" autocomplete="off" required>
                        <label for="email"><i class="far fa-envelope"></i> Email</label>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="input-form">
                        <input type="number" name="no" id="no" value="<?= $inidata["no"]; ?>" placeholder="Masukkan no anda" autocomplete="off" required>
                        <label for="no"><i class="far fa-envelope"></i> Nomor Telepon</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md">
                      <div class="select-form bawah">
                        <select name="jab" disabled>
                          <option value="">- Pilih Jabatan -</option>
                        </select>
                        <i style="margin-top: 5px; display: inline-block; font-size: 15px;">Hanya admin yang dapat mengedit data jabatan.</i>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="select-form">
                        <select name="gol" disabled>
                          <option value="">- Pilih Golongan -</option>
                        </select>
                        <i style="margin-top: 5px; display: inline-block; font-size: 15px;">Hanya admin yang bisa mengedit data golongan.</i>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md">
                      <div class="select-form" style="margin-top: 16px;">
                        <select name="status">
                          <option value="Default" <?php if ($inidata['status'] == "Default") {
                                                    echo "SELECTED";
                                                  } ?>>- Pili Status -</option>
                          <option value="Menikah" <?php if ($inidata['status'] == "Menikah") {
                                                    echo "SELECTED";
                                                  } ?>>Menikah</option>
                          <option value="Belum Menikah" <?php if ($inidata['status'] == "Belum Menikah") {
                                                          echo "SELECTED";
                                                        } ?>>Belum Menikah</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-form">
                      <input type="number" name="jmlh" id="jmlh" value="<?= $inidata["jmlh"]; ?>" min="0" max="99" placeholder="Masukkan jmlh Anda" autocomplete="off" required>
                      <label for="jmlh"><i class="far fa-user-circle"></i> Jumlah Anak</label>
                    </div>
                  </div>
                  <div class="row">
                    <img class="foto-edit" src="../img/user/<?= $inidata['pic']; ?>" alt="<?= $inidata['nama']; ?>">
                  </div>
                </div>
                <div class="button-wrap">
                  <label class="new-button" for="upload">Foto Baru</label>
                  <input type="file" name="cover" id="upload">
                </div>
              <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
                <div class="input-data">
                  <div class="row">
                    <div class="col-md">
                      <div class="input-form">
                        <input type="text" name="nama" style="text-transform: capitalize;" value="<?= $inidata['nama']; ?>" id="nama" placeholder="Masukkan User Baru" autocomplete="off" required>
                        <label for="nama"><i class="fas fa-user"></i> Nama</label>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="input-form">
                        <input type="date" name="date" id="date" placeholder="Masukkan date User Baru" value="<?= $inidata['tgl']; ?>" autocomplete="off" required>
                        <label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-form">
                      <input type="text" name="namap" style="text-transform: capitalize;" id="namap" placeholder="Masukkan namap Anda" value="<?= $inidata["namapanggilan"]; ?>">
                      <label for="nip"><i class="far fa-id-card"></i> Nama sapaan/panggilan dalam aplikasi</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md">
                      <div class="input-form">
                        <input type="number" name="no" id="no" placeholder="Masukkan no User Baru" value="<?= $inidata['no']; ?>" autocomplete="off" required>
                        <label for="no"><i class="fas fa-phone-alt"></i> Nomor Tlp.</label>
                      </div>
                    </div>
                    <div class="col-md">
                      <div class="input-form">
                        <input type="email" name="email" style="text-transform: lowercase;" id="email" value="<?= $inidata['email']; ?>" placeholder="Masukkan Email User Baru" autocomplete="off" required>
                        <label for="email"><i class="fas fa-at"></i> Email</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <img class="foto-edit" src="../img/user/<?= $inidata['pic']; ?>" alt="<?= $inidata['nama']; ?>">
                  </div>
                </div>
                <div class="button-wrap">
                  <label class="new-button" for="upload">Foto Baru</label>
                  <input type="file" name="cover" id="upload">
                </div>
              <?php } ?>
              <div class="input-data">
                <a href="../profil/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
                <button class="tombol" name="submit"><i class="fas fa-edit"></i> Edit</button>
                <p>Klik <a type="submit" class="link" href="../profil/?view=edit-user">disini</a> jika anda ingin mengubah username & password.</p>
              </div>
            </form>
          </div>
        </div>
      <?php break;
      case 'edit-user':
        $id  = $_SESSION["peg"];
        $kembali = $_SESSION['kembali'];
        $inidata = query("SELECT * FROM user WHERE id = $id")[0];
        if (isset($_POST["submit"])) {
          if (edituserdata($_POST) > 0) {
            echo "<script>
										alert ('Data berhasil diedit!')
										document.location.href = '../$kembali'
								</script>";
          } else {
            echo "
							<script>
										alert ('Data gagal diedit!')
										document.location.href = '../$kembali'
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
                    <label for="username"><i class="far fa-id-card"></i> Username Baru (username lama = <?= $inidata['username']; ?>)</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="input-form">
                      <input type="password" name="password" id="password" placeholder="Masukkan password anda" autocomplete="off" required>
                      <label for="password"><i class="far fa-id-card"></i> Password Baru</label>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="input-form">
                      <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Masukkan konfirmasi anda" autocomplete="off" required>
                      <label for="konfirmasi"><i class="far fa-user"></i> Ulangi Password</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="input-data">
                <a href="../<?= $kembali; ?>" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
                <button class="tombol" name="submit"><i class="fas fa-edit"></i> Edit</button>
                <p>Klik <a class="link" href="../profil/?view=edit">disini</a> jika anda tidak ingin mengubah username & password.</p>
              </div>
            </form>
          </div>
        </div>
    <?php break;
    } ?>
  </div>
<?php } ?>
<?php include '../modules/footer.php'; ?>