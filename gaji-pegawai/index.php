<?php
include '../modules/nav.php';

if (!isset($_SESSION["login"])) {
  header("Location: ../login/");
  exit;
}

if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
  $bulan = $_GET['bulan'];
  $tahun = $_GET['tahun'];
  $bulantahun = $bulan . $tahun;
} else {
  $bulan = date('m');
  $tahun = date('Y');
  $bulantahun = $bulan . $tahun;
}
?>

<div class="container">
  <header>
    <h1>Data Gaji Pegawai</h1>
  </header>
  <div class="row card">
    <div class="col-md-4">
      <div class="container-items">
        <img src="../img/vector/gaji-pegawai.png" alt="Gaji Pegawai">
      </div>
    </div>
    <div class="col-md-8">
      <div class="container-items">
        <?php foreach ($name as $nm) { ?>
          <h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
        <?php } ?>
        <p>Page data gaji pegawai menyimpan data gaji para pegawai di L-1485 <i>Company</i> per-bulan.</p>
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
    </div>
    <div class="row tahun-bulan" style="margin-top: 30px;">
      <form method="get" action="">
        <input type="hidden" id="bulan" value="<?= $bulan; ?>">
        <input type="hidden" id="tahun" value="<?= $tahun; ?>">
        <span>
          <label for="bulan">Bulan</label>
          <select name="bulan">
            <option value="">- Pilih -</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
          </select>
        </span>
        <span>
          <label>Tahun</label>
          <select name="tahun">
            <option value="">- Pilih -</option>
            <?php
            $tahun = date('Y');
            for ($i = 2021; $i <= $tahun + 4; $i++) { ?>
              <option value="<?= $i ?>"><?= $i ?></option>
            <?php } ?>
          </select>
        </span>
        <span>
          <button type="submit" class="tombol-tampilkan"><i class="fas fa-eye"></i> Tampilkan Data</button>
        </span>
      </form>
      <div class="row myalert">
        <div class="col">
          <p>Bulan : <span style="display: inline-block;"><?= bulan($bulan); ?></span>, Tahun : <span style="display: inline-block;"><?= $tahun; ?></span></p>
        </div>
      </div>
    </div>
    <div class="row">
      <div id="table-container" style="margin: 0; padding: 0; box-sizing: border-box;">
        <div class="table-container">
          <table class="table-2">
            <thead>
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Golongan</th>
                <th>Status</th>
                <th>Jumlah Anak</th>
                <th>Gaji Pokok</th>
                <th>Tj. Jabatan</th>
                <th>Tj. S/I</th>
                <th>Tj. Anak</th>
                <th>Uang Makan</th>
                <th>Uang Lembur</th>
                <th>Askes</th>
                <th>Pendapatan</th>
                <th>Potongan</th>
                <th>Total Gaji</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan, jabatan.gaji_pokok, jabatan.tunjangan_jabatan, IF (user.status = 'Menikah', tunjangan_si, 0) AS tjsi, IF (user.status = 'Menikah', tunjangan_anak, 0) AS tjanak, uang_makan AS um, master_gaji.lembur*uang_lembur AS ul, askes, (gaji_pokok+tunjangan_jabatan+(SELECT tjsi)+(SELECT tjanak)+(SELECT um)+(SELECT ul)+askes) AS pendapatan, potongan, (SELECT pendapatan) - potongan AS totalgaji FROM user INNER JOIN master_gaji ON master_gaji.nip=user.nip INNER JOIN golongan ON golongan.kode_golongan=user.kode_golongan INNER JOIN jabatan ON jabatan.kode_jabatan=user.kode_jabatan WHERE master_gaji.bulan='$bulantahun' AND user.level='pegawai' ORDER BY user.id ASC");

              $no = 1;
              ?>
              <?php foreach ($sql as $d) { ?>
                <?php if ($levelsaya == "Pegawai") { ?>
                  <tr>
                    <td data-label="No"><?= $no; ?></td>
                    <td data-label="Foto">
                      <img src="../img/user/<?= $d['pic']; ?>" alt="">
                    </td>
                    <td data-label="NIP"><?= $d['nip']; ?></td>
                    <td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
                    <td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
                    <td data-label="Golongan">Hanya admin</td>
                    <td data-label="Status">Hanya admin </td>
                    <td data-label="Jumlah Anak">Hanya admin</td>
                    <td data-label="Gaji Pokok"><?= rupiah($d['gaji_pokok']); ?></td>
                    <td data-label="Tunjangan Jabatan"><?= rupiah($d['tunjangan_jabatan']); ?></td>
                    <td data-label="Tunjangan S/I">Hanya admin</td>
                    <td data-label="Tunjangan Anak">Hanya admin</td>
                    <td data-label="Uang Makan"><?= rupiah($d['um']); ?></td>
                    <td data-label="Uang Lembur"><?= rupiah($d['ul']); ?></td>
                    <td data-label="Askes"><?= rupiah($d['askes']); ?></td>
                    <td data-label="Pendapatan">Hanya admin</td>
                    <td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
                    <td data-label="Total Gaji">Hanya admin</td>
                  </tr>
                <?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
                  <tr>
                    <td data-label="No"><?= $no; ?></td>
                    <td data-label="Foto">
                      <img src="../img/user/<?= $d['pic']; ?>" alt="">
                    </td>
                    <td data-label="NIP"><?= $d['nip']; ?></td>
                    <td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
                    <td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
                    <td data-label="Golongan"><?= $d['nama_golongan']; ?></td>
                    <td data-label="Status"><?= $d['status']; ?></td>
                    <td data-label="Jumlah Anak"><?= $d['jmlh']; ?></td>
                    <td data-label="Gaji Pokok"><?= rupiah($d['gaji_pokok']); ?></td>
                    <td data-label="Tunjangan Jabatan"><?= rupiah($d['tunjangan_jabatan']); ?></td>
                    <td data-label="Tunjangan S/I"><?= rupiah($d['tjsi']); ?></td>
                    <td data-label="Tunjangan Anak"><?= rupiah($d['tjanak']); ?></td>
                    <td data-label="Uang Makan"><?= rupiah($d['um']); ?></td>
                    <td data-label="Uang Lembur"><?= rupiah($d['ul']); ?></td>
                    <td data-label="Askes"><?= rupiah($d['askes']); ?></td>
                    <td data-label="Pendapatan"><?= rupiah($d['pendapatan']); ?></td>
                    <td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
                    <td data-label="Total Gaji"><?= rupiah($d['totalgaji']); ?></td>
                  </tr>
                <?php } ?>
                <?php $no++; ?>
              <?php } ?>
              <?php if (mysqli_num_rows($sql) <= 0) { ?>
                <tr>
                  <td colspan="18" style="text-align: left;">
                    Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>!
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row baris-cetak">
          <?php if (mysqli_num_rows($sql) > 0) { ?>
            <center>
              <a style="margin-bottom: 10px;" href="../cetak-gaji/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Cetak Data Gaji Pegawai</a> <br>
              <a href="../export-data-gaji/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Export Data Gaji Pegawai (Excel)</a>
            </center>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../js/searchGaji.js"></script>

<?php include '../modules/footer.php'; ?>