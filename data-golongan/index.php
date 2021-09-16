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
      $data = mysqli_query($conn, "SELECT * FROM golongan WHERE NOT kode_golongan = 'Belum Diatur'");
      if (isset($_POST['hapus'])) {
        $nama = htmlspecialchars($_POST["nama"]);
        $kode = htmlspecialchars($_POST["kode"]);
        if (hapusgolongan($_POST) > 0) {
          echo "<script>
                  alert ('Golongan $nama dengan kode $kode berhasil dihapus!')
                  document.location.href = '../data-golongan/'
                </script>";
        } else {
          echo "<script>
                  alert ('Golongan $nama dengan kode $kode gagal dihapus!')
                  document.location.href = '../data-golongan/'
                </script>";
        }
      }

      if (isset($_POST['edit'])) {
        $_SESSION['gol'] = htmlspecialchars($_POST["kode"]);
        echo "<script>
                  document.location.href = '../data-golongan/?view=edit'
                </script>";
      }

      foreach ($data as $d) {
        $count[] = $d['kode_golongan'];
      }

      $jumlahData = count($count);
  ?>
      <header>
        <h1>Data Golongan</h1>
      </header>
      <div class="row card">
        <div class="col-md-5">
          <div class="container-items">
            <img src="../img/vector/data-golongan.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-7">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Page data golongan ini menyimpan semua data golongan yang ada di EXO Company ini beserta besaran tunjangannya. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> golongan yang terinput.</p>
          </div>
        </div>
      </div>
      <div class="row tabel-data-admin">
        <div class="row">
          <div class="col-md-6">
            <form class="ini-search" method="post" action="">
              <button type="submit" name="cari" id="tombol-cari"><i class="fas fa-search"></button></i>
              <input class="search" type="text" name="keyword" placeholder="Cari Golongan..." id="keyword" autocomplete="off">
            </form>
          </div>
          <?php if ($levelsaya == "Pegawai") {
          } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <div class="col-md-6">
              <a href="../data-golongan/?view=tambah" class="tombol">&plus; Tambah Data</a>
            </div>
          <?php } ?>
        </div>
        <div class="row">
          <div class="table-container" id="table-container">
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
                foreach ($data as $d) { ?>
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
              </tbody>
            </table>
          </div>
          <div class="row baris-cetak">
            <?php if (mysqli_num_rows($data) > 0) { ?>
              <center>
                <a href="../cetak-laporan-golongan/" target="_blank" class="tombol-inituh">Laporan Golongan</a>
              </center>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php
      break;
    case 'tambah':
      if ($levelsaya == "Pegawai") {
        echo "<script>
                document.location.href = '../data-golongan/'
              </script>";
      }
      $simbol = 'G';
      $tambahgolongan = mysqli_query($conn, "SELECT max(kode_golongan) AS last FROM golongan WHERE kode_golongan LIKE '%$simbol%'");
      $data = mysqli_fetch_array($tambahgolongan);

      $kodeterakhir = $data['last'];
      $nomorterakhir = substr($kodeterakhir, 1, 2);
      $nextnumber = $nomorterakhir + 1;
      $nextcode = $simbol . sprintf('%02s', $nextnumber);

      if (isset($_POST["submit"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        $kode = htmlspecialchars($_POST["kode"]);
        if (tambahgolongan($_POST) > 0) {
          echo "
              <script>
                alert ('Golongan $nama dengan kode $kode berhasil ditambahkan!')
                document.location.href = '../data-golongan/'
              </script>";
        } else {
          echo "
              <script>
                alert ('Golongan $nama dengan kode $kode gagal ditambahkan!')
                document.location.href = '../data-golongan/'
              </script>";
        }
      }
    ?>
      <header>
        <h1>Tambah Data Golongan</h1>
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
            <p>Kamu bisa menambahkan data golongan di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-tambah">
          <form method="post" action="">
            <h2>Silakan Tambahkan Data Dibawah.</h2>
            <div class="input-data">
              <div class="input-form">
                <input type="text" name="kode" id="kode" placeholder="Masukkan Kode golongan Baru" value="<?= $nextcode; ?>" autocomplete="off" readonly required>
                <label for="kode"><i class="fas fa-code"></i> Kode Golongan</label>
              </div>
              <div class="input-form">
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Golongan Baru" autocomplete="off" required>
                <label for="nama"><i class="fas fa-comment-dots"></i> Nama golongan</label>
              </div>
              <div class="input-form">
                <input type="number" name="tunjangansi" id="tunjangansi" placeholder="Masukkan Tunjangan S/I Golongan Baru" autocomplete="off" required>
                <label for="tunjangansi"><i class="fas fa-hand-holding-heart"></i> Tunjangan S/I</label>
              </div>
              <div class="input-form">
                <input type="number" name="tunjangananak" id="tunjangananak" placeholder="Masukkan Tunjangan Anak Golongan Baru" autocomplete="off" required>
                <label for="tunjangananak"><i class="fas fa-hand-holding-water"></i> Tunjangan Anak</label>
              </div>
              <div class="input-form">
                <input type="number" name="um" id="um" placeholder="Masukkan Uang Makan Golongan Baru" autocomplete="off" required>
                <label for="um"><i class="fas fa-comment-medical"></i> Uang Makan</label>
              </div>
              <div class="input-form">
                <input type="number" name="ul" id="ul" placeholder="Masukkan Uang Lembur Golongan Baru" autocomplete="off" required>
                <label for="ul"><i class="fas fa-comment-dollar"></i> Uang Lembur</label>
              </div>
              <div class="input-form">
                <input type="number" name="askes" id="askes" placeholder="Masukkan Askes Golongan Baru" autocomplete="off" required>
                <label for="askes"><i class="fas fa-hand-holding-medical"></i> Askes</label>
              </div>
              <a href="../data-golongan/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
              <button class="tombol" name="submit"><i class="fas fa-plus"></i> Tambah</button>
            </div>
          </form>
        </div>
      </div>
    <?php
      break;
    case 'edit':
      if ($levelsaya == "Pegawai") {
        echo "<script>
                document.location.href = '../data-golongan/'
              </script>";
      }
      $id  = $_SESSION["gol"];
      $inidata = query("SELECT * FROM golongan WHERE kode_golongan = '$id'")[0];
      if (isset($_POST["submit"])) {
        $kode = $_POST['kode'];
        if (editgolongan($_POST) > 0) {
          echo "
              <script>
                alert ('Data dari golongan dengan kode $kode berhasil diedit!')
                document.location.href = '../data-golongan/'
              </script>";
        } else {
          echo "
              <script>
                alert ('Data dari golongan dengan kode $kode gagal diedit!')
                document.location.href = '../data-golongan/'
              </script>";
        }
      }
    ?>
      <header>
        <h1>Edit Data Golongan</h1>
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
            <p>Kamu bisa mengedit data golongan di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-edit">
          <form method="post" action="">
            <h2>Silakan Edit Data Dibawah.</h2>
            <div class="input-data">
              <div class="input-form">
                <input type="text" name="kode" id="kode" placeholder="Masukkan Kode golongan Baru" value="<?= $inidata["kode_golongan"]; ?>" autocomplete="off" readonly required>
                <label for="kode"><i class="fas fa-code"></i> Kode Golongan</label>
              </div>
              <div class="input-form">
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Golongan Baru" autocomplete="off" value="<?= $inidata["nama_golongan"]; ?>" required>
                <label for="nama"><i class="fas fa-comment-dots"></i> Nama golongan</label>
              </div>
              <div class="input-form">
                <input type="number" name="tunjangansi" id="tunjangansi" placeholder="Masukkan Tunjangan S/I Golongan Baru" value="<?= $inidata["tunjangan_si"]; ?>" autocomplete="off" required>
                <label for="tunjangansi"><i class="fas fa-hand-holding-heart"></i> Tunjangan S/I</label>
              </div>
              <div class="input-form">
                <input type="number" name="tunjangananak" id="tunjangananak" placeholder="Masukkan Tunjangan Anak Golongan Baru" autocomplete="off" value="<?= $inidata["tunjangan_anak"]; ?>" required>
                <label for="tunjangananak"><i class="fas fa-hand-holding-medical"></i> Tunjangan Anak</label>
              </div>
              <div class="input-form">
                <input type="number" name="um" id="um" placeholder="Masukkan Uang Makan Golongan Baru" autocomplete="off" value="<?= $inidata["uang_makan"]; ?>" required>
                <label for="um"><i class="fas fa-comment-medical"></i> Uang Makan</label>
              </div>
              <div class="input-form">
                <input type="number" name="ul" id="ul" placeholder="Masukkan Uang Lembur Golongan Baru" autocomplete="off" value="<?= $inidata["uang_lembur"]; ?>" required>
                <label for="ul"><i class="fas fa-comment-dollar"></i> Uang Lembur</label>
              </div>
              <div class="input-form">
                <input type="number" name="askes" id="askes" placeholder="Masukkan Askes Golongan Baru" autocomplete="off" value="<?= $inidata["askes"]; ?>" required>
                <label for="askes"><i class="fas fa-hand-holding-medical"></i> Askes</label>
              </div>
              <a href="../data-golongan/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
              <button class="tombol" name="submit">
                <i class="fas fa-edit"></i> Edit
              </button>
            </div>
          </form>
        </div>
      </div>
  <?php
      break;
  }
  ?>
</div>
<script src="../js/searchGolongan.js"></script>
<?php include '../modules/footer.php'; ?>