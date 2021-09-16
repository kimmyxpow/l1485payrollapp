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
      $data = mysqli_query($conn, "SELECT * FROM jabatan WHERE NOT kode_jabatan = 'Belum diatur'");
      if (isset($_POST['hapus'])) {
        $nama = htmlspecialchars($_POST["nama"]);
        $kode = htmlspecialchars($_POST["kode"]);
        if (hapusjabatan($_POST) > 0) {
          echo "<script>
									alert ('Jabatan $nama dengan kode $kode berhasil dihapus!')
									document.location.href = '../data-jabatan/'
								</script>";
        } else {
          echo "<script>
									alert ('Jabatan $nama dengan kode $kode berhasil dihapus!')
									document.location.href = '../data-jabatan/'
								</script>";
        }
      }

      if (isset($_POST['edit'])) {
        $_SESSION['jab'] = htmlspecialchars($_POST["kode"]);
        echo "<script>
                  document.location.href = '../data-jabatan/?view=edit'
              </script>";
      }

      foreach ($data as $d) {
        $count[] = $d['kode_jabatan'];
      }

      $jumlahData = count($count);
  ?>
      <header>
        <h1>Data Jabatan</h1>
      </header>
      <div class="row card">
        <div class="col-md-4">
          <div class="container-items">
            <img src="../img/vector/data-jabatan.png" alt="Hero Image">
          </div>
        </div>
        <div class="col-md-8">
          <div class="container-items">
            <?php foreach ($name as $nm) { ?>
              <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
            <?php } ?>
            <p>Page data jabatan ini menyimpan semua data jabatan yang ada di EXO L-1485 Company ini beserta gaji dan tunjangannya. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> jabatan yang terinput.</p>
          </div>
        </div>
      </div>
      <div class="row tabel-data-admin">
        <div class="row">
          <div class="col-md-6">
            <form class="ini-search" method="post" action="">
              <button type="submit" name="cari" id="tombol-cari"><i class="fas fa-search"></button></i>
              <input class="search" type="text" name="keyword" placeholder="Cari Jabatan..." id="keyword" autocomplete="off">
            </form>
          </div>
          <?php if ($levelsaya == "Pegawai") {
          } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
            <div class="col-md-6">
              <a href="../data-jabatan/?view=tambah" class="tombol">&plus; Tambah Data</a>
            </div>
          <?php } ?>
        </div>
        <div class="row">
          <div class="table-container" id="table-container">
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
              </tbody>
            </table>
          </div>
          <div class="row baris-cetak">
            <?php if (mysqli_num_rows($data) > 0) { ?>
              <center>
                <a href="../cetak-laporan-jabatan/" target="_blank" class="tombol-inituh">Laporan Jabatan</a>
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
                document.location.href = '../data-jabatan/'
              </script>";
      }
      $simbol = 'J';
      $tambahjabatan = mysqli_query($conn, "SELECT max(kode_jabatan) AS last FROM jabatan WHERE kode_jabatan LIKE '%$simbol%'");

      $data = mysqli_fetch_array($tambahjabatan);

      $kodeterakhir = $data['last'];
      $nomorterakhir = substr($kodeterakhir, 1, 2);
      $nextnumber = $nomorterakhir + 1;
      $nextcode = $simbol . sprintf('%02s', $nextnumber);

      if (isset($_POST["submit"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        $kode = htmlspecialchars($_POST["kode"]);
        if (tambahjabatan($_POST) > 0) {
          echo "
              <script>
                alert ('Jabatan $nama dengan kode $kode berhasil ditambahkan!')
                document.location.href = '../data-jabatan/'
              </script>";
        } else {
          echo "
              <script>
                alert ('Jabatan $nama dengan kode $kode gagal ditambahkan!')
                document.location.href = '../data-jabatan/'
              </script>";
        }
      }

    ?>
      <header>
        <h1>Tambah Data Jabatan</h1>
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
            <p>Kamu bisa menambahkan data jabatan di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-tambah">
          <form method="post" action="">
            <h2>Silakan Tambahkan Data Dibawah.</h2>
            <div class="input-data">
              <div class="input-form">
                <input type="text" name="kode" id="kode" placeholder="Masukkan Kode Jabatan Baru" value="<?= $nextcode; ?>" autocomplete="off" readonly required>
                <label for="kode"><i class="fas fa-code"></i> Kode Jabatan</label>
              </div>
              <div class="input-form">
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Jabatan Baru" autocomplete="off" required>
                <label for="nama"><i class="fas fa-comment-dots"></i> Nama Jabatan</label>
              </div>
              <div class="input-form">
                <input type="number" name="gaji" id="gaji" placeholder="Masukkan Gaji Pokok Jabatan Baru" autocomplete="off" required>
                <label for="gaji"><i class="fas fa-comment-dollar"></i> Gaji Jabatan</label>
              </div>
              <div class="input-form">
                <input type="number" name="tunjangan" id="tunjangan" placeholder="Masukkan Tunjangan Jabatan Baru" autocomplete="off" required>
                <label for="tunjangan"><i class="fas fa-comment-medical"></i> Tunjangan Jabatan</label>
              </div>
              <a href="../data-jabatan/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
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
                document.location.href = '../data-jabatan/'
              </script>";
      }
      $id  = $_SESSION["jab"];
      $inidata = query("SELECT * FROM jabatan WHERE kode_jabatan = '$id'")[0];
      if (isset($_POST["submit"])) {
        $kode = $_POST['kode'];
        if (editjabatan($_POST) > 0) {
          echo "<script>
                alert ('Data dari jabatan dengan kode $kode berhasil diedit!')
                document.location.href = '../data-jabatan/'
              </script>";
        } else {
          echo "
              <script>
                alert ('Data dari jabatan dengan kode $kode gagal diedit!')
                document.location.href = '../data-jabatan/'
              </script>";
        }
      }
    ?>
      <header>
        <h1>Edit Data Jabatan</h1>
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
            <p>Kamu bisa mengedit data jabatan di page ini.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-edit">
          <form method="post" action="">
            <h2>Silakan Edit Data Dibawah.</h2>
            <div class="input-data">
              <div class="input-form">
                <input type="text" name="kode" id="kode" placeholder="Masukkan Kode Jabatan Baru" value="<?= $inidata["kode_jabatan"]; ?>" autocomplete="off" readonly required>
                <label for="kode"><i class="fas fa-code"></i> Kode Jabatan</label>
              </div>
              <div class="input-form">
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Jabatan Baru" value="<?= $inidata["nama_jabatan"]; ?>" autocomplete="off" required>
                <label for="nama"><i class="fas fa-comment-dots"></i> Nama Jabatan</label>
              </div>
              <div class="input-form">
                <input type="text" name="gaji" id="gaji" placeholder="Masukkan Gaji Pokok Jabatan Baru" value="<?= $inidata["gaji_pokok"]; ?>" autocomplete="off" required>
                <label for="gaji"><i class="fas fa-comment-dollar"></i> Gaji Jabatan</label>
              </div>
              <div class="input-form">
                <input type="text" name="tunjangan" id="tunjangan" value="<?= $inidata["tunjangan_jabatan"]; ?>" placeholder="Masukkan Tunjangan Jabatan Baru" autocomplete="off" required>
                <label for="tunjangan"><i class="fas fa-comment-medical"></i> Tunjangan Jabatan</label>
              </div>
              <a href="../data-jabatan/" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
              <button class="tombol" name="submit" type="submit"><i class="fas fa-edit"></i> Edit</button>
            </div>
          </form>
        </div>
      </div>
  <?php
      break;
  }
  ?>
</div>
<script src="../js/searchJabatan.js"></script>
<?php include '../modules/footer.php'; ?>