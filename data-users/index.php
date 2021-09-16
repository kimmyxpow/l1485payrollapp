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
      $data = mysqli_query($conn, "SELECT * FROM user WHERE email != '' ORDER BY nama ASC");

      if (isset($_POST["hapus"])) {
        $nama = htmlspecialchars($_POST["nama"]);
        if (hapususer($_POST) > 0) {
          if (hapuskehadiran($_SESSION['hapuskehadiran']) > 0) {
            echo "<script>
									alert ('User $nama berhasil dihapus!')
									document.location.href = '../data-users/'
							</script>";
          } else {
            echo "<script>
									document.location.href = '../data-users/'
							</script>";
          }
        } else {
          echo "<script>
                  alert ('User $nama gagal dihapus!')
                  document.location.href = '../data-users/'
								</script>";
        }
      }

      if (isset($_POST['detail'])) {
        $_SESSION['detail'] = "data-users";
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
        <h1>Data Semua User</h1>
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
            <p>Page data all user menyimpan nama-nama semua user dari semua role di aplikasi ini. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> user di L-1485.</p>
          </div>
        </div>
      </div>
      <div class="row tabel-data-admin">
        <div class="row">
          <div class="col-md-6">
            <form class="ini-search" method="post" action="">
              <button type="submit" name="cari" id="tombol-cari"><i class="fas fa-search"></button></i>
              <input class="search" type="text" name="keyword" placeholder="Cari User..." id="keyword" autocomplete="off">
            </form>
          </div>
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
                  <th>Role</th>
                  <th>Tools</th>
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
                    <td data-label="Role"><?= $adm["level"]; ?></td>
                    <td data-label="Tools">
                      <form method="post" action="">
                        <input type="hidden" name="id" value="<?= $adm["id"]; ?>">
                        <input type="hidden" name="nama" value="<?= $adm["nama"]; ?>">
                        <input type="hidden" name="username" value="<?= $adm["username"]; ?>">
                        <input type="hidden" name="level" value="<?= $adm["level"]; ?>">
                        <button type="submit" class="tombol-2" name="detail">Detail</button>
                        <?php if ($levelsaya == "Pegawai") {
                        } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
                          <button class="tombol-baru" type="submit" name="hapus" onclick="return confirm ('Yakin?');">Hapus</button>
                        <?php } ?>
                      </form>
                    </td>
                  </tr>
                  <?php $i++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  <?php break;
  } ?>
</div>
<script src="../js/searchUser.js"></script>
<?php include '../modules/footer.php'; ?>