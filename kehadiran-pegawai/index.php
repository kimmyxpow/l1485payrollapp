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
			<header>
				<h1>Data Kehadiran Pegawai</h1>
			</header>
			<div class="row card">
				<div class="col-md-4">
					<div class="container-items">
						<img src="../img/vector/data-kehadiran.png" alt="Hero Image">
					</div>
				</div>
				<div class="col-md-8">
					<div class="container-items">
						<?php foreach ($name as $nm) { ?>
							<h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
						<?php } ?>
						<p>Page data kehadiran pegawai menyimpan data kehadiran pegawai di L-1485 <i>Company</i> per-bulan.</p>
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
					<?php if ($levelsaya == "Pegawai") {
					} elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
						<div class="col-md-6">
							<a href="../kehadiran-pegawai/?view=tambah&bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" class="tombol">&plus; Tambah Data</a>
						</div>
					<?php } ?>
				</div>
				<div class="row tahun-bulan">
					<form method="get" action="">
						<input type="hidden" id="bulan" value="<?= $bulan; ?>">
						<input type="hidden" id="tahun" value="<?= $tahun; ?>">
						<span>
							<label>Bulan</label>
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
					<div class="table-container" id="table-container">
						<table class="table">
							<thead>
								<tr>
									<th>No</th>
									<th>Foto</th>
									<th>NIP</th>
									<th>Nama Pegawai</th>
									<th>Jabatan</th>
									<th>Masuk</th>
									<th>Sakit</th>
									<th>Izin</th>
									<th>Alpha</th>
									<th>Lembur</th>
									<th>Potongan</th>
								</tr>
							</thead>
							<?php
							$sql = mysqli_query($conn, "SELECT master_gaji.*, 
																					user.nama, user.pic, user.kode_jabatan, 
																					jabatan.nama_jabatan 
																					FROM master_gaji 
																					INNER JOIN user ON master_gaji.nip=user.nip 
																					INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan 
																					WHERE master_gaji.bulan=$bulantahun AND user.level = 'pegawai' 
																					ORDER BY user.nama ASC");
							$no = 1;
							?>
							<tbody>
								<?php foreach ($sql as $d) { ?>
									<tr>
										<td data-label="No"><?= $no; ?></td>
										<td data-label="Foto">
											<img src="../img/user/<?= $d['pic']; ?>">
										</td>
										<td data-label="NIP"><?= $d['nip']; ?></td>
										<td data-label="Nama Pegawai" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
										<td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
										<td data-label="Masuk"><?= $d['masuk']; ?></td>
										<td data-label="Sakit"><?= $d['sakit']; ?></td>
										<td data-label="Izin"><?= $d['izin']; ?></td>
										<td data-label="Alpha"><?= $d['alpha']; ?></td>
										<td data-label="Lembur"><?= $d['lembur']; ?></td>
										<td data-label="Potongan"><?= rupiah($d['potongan']); ?></td>
									</tr>
									<?php $no++ ?>
								<?php } ?>

								<?php if (mysqli_num_rows($sql) > 0) { ?>
									<?php if ($levelsaya == "Pegawai") {
									} elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
										<tr>
											<td colspan="11"><a href="../kehadiran-pegawai/?view=edit&bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" class="tombol-juga">Edit Data</a></td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<?php if ($levelsaya == "Pegawai") { ?>
										<tr>
											<td colspan="11" style="text-align: left;">
												Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>!
											</td>
										</tr>
									<?php } elseif ($levelsaya == "Admin" || $levelsaya == "Super Admin") { ?>
										<tr>
											<td colspan="11" style="text-align: left;">
												Belum ada data pada bulan <?= bulan($bulan); ?> tahun <?= $tahun; ?>! Silakan tambahkan data!
											</td>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="row baris-cetak">
						<?php if (mysqli_num_rows($sql) > 0) { ?>
							<center>
								<a style="margin-bottom: 10px;" href="../cetak-laporan-kehadiran/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Laporan Kehadiran</a><br>
								<a style="margin-bottom: 10px;" href="../cetak-laporan-lembur/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Laporan Lembur</a><br>
								<a href="../cetak-laporan-potongan/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="tombol-inituh">Laporan Potongan</a>
							</center>
						<?php } ?>
					</div>
				</div>
			</div>

		<?php
			break;
		case "tambah":
			if ($levelsaya == "Pegawai") {
				echo "<script>
                document.location.href = '../kehadiran-pegawai/'
              </script>";
			}
			$golongan = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.level = 'pegawai' ORDER BY user.nama ASC");
			if (isset($_POST["simpan"])) {
				$bulann = $_POST['bulann'];
				$tahunn = $_POST['tahunn'];
				if (simpankehadiran($_POST) > 0) {
					echo "<script>
											alert ('Data berhasil ditambahkan!')
											document.location.href = '../kehadiran-pegawai/?bulan=$bulann&tahun=$tahunn'
                  </script>";
				} else {
					echo "<script>
											alert ('Data gagal ditambahkan!')
											document.location.href = '../kehadiran-pegawai/?bulan=$bulann&tahun=$tahunn'
                </script>";
				}
			}
		?>
			<header>
				<h1>Tambah Data Kehadiran Pegawai</h1>
			</header>
			<div class="row card">
				<div class="col-md-4">
					<div class="container-items">
						<img src="../img/vector/data-kehadiran.png" alt="Hero Image">
					</div>
				</div>
				<div class="col-md-8">
					<div class="container-items">
						<?php foreach ($name as $nm) { ?>
							<h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
						<?php } ?>
						<p>Page data kehadiran pegawai menyimpan data kehadiran pegawai di L-1485 <i>Company</i> per-bulan.</p>
					</div>
				</div>
			</div>
			<div class="row tabel-data-admin">
				<div class="row tahun-bulan">
					<form method="get" action="">
						<input type="hidden" name="view" value="tambah">
						<span>
							<label for="bulan">Bulan</label>
							<select name="bulan" id="bulan">
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
							<label for="tahun">Tahun</label>
							<select name="tahun" id="tahun">
								<option value="">- Pilih -</option>
								<?php
								$tahun = date('Y');
								for ($i = 2021; $i <= $tahun + 4; $i++) { ?>
									<option value="<?= $i ?>"><?= $i ?></option>
								<?php } ?>
							</select>
						</span>
						<span>
							<button type="submit" class="tombol-tampilkan"><i class="fas fa-eye"></i> Generate Form</button>
						</span>
					</form>
					<?php
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
					<div class="row myalert">
						<div class="col">
							<p>Bulan : <span><?= $bulan; ?></span>, Tahun : <span><?= $tahun; ?></span></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="table-container">
						<table class="table">
							<thead>
								<tr>
									<th>No</th>
									<th>Foto</th>
									<th>NIP</th>
									<th>Nama Pegawai</th>
									<th>Jabatan</th>
									<th>Masuk</th>
									<th>Sakit</th>
									<th>Izin</th>
									<th>Alpha</th>
									<th>Lembur</th>
									<th>Potongan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$query = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan FROM user INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan WHERE NOT EXISTS (SELECT * FROM master_gaji WHERE bulan='$bulantahun' AND user.nip=master_gaji.nip) AND user.level='pegawai' ORDER BY user.nama ASC");
								$jumlahPegawai = mysqli_num_rows($query);
								$no = 1;
								?>
								<form method="post" action="">
									<?php foreach ($query as $d) { ?>
										<input type="hidden" name="bulan[]" value="<?= $bulantahun; ?>">
										<input type="hidden" name="bulann" value="<?= $bulan; ?>">
										<input type="hidden" name="tahunn" value="<?= $tahun; ?>">
										<input type="hidden" name="nip[]" value="<?= $d['nip']; ?>">
										<tr>
											<td data-label="No"><?= $no; ?></td>
											<td data-label="Foto">
												<img src="../img/user/<?= $d['pic']; ?>">
											</td>
											<td data-label="NIP"><?= $d['nip']; ?></td>
											<td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
											<td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
											<td data-label="Masuk">
												<input type="number" name="masuk[]" id="masuk" value="0" required>
											</td>
											<td data-label="Sakit">
												<input type="number" name="sakit[]" id="sakit" value="0" required>
											</td>
											<td data-label="Izin">
												<input type="number" name="izin[]" id="izin" value="0" required>
											</td>
											<td data-label="Alpha">
												<input type="number" name="alpha[]" id="alpha" value="0" required>
											</td>
											<td data-label="Lembur">
												<input type="number" name="lembur[]" id="lembur" value="0" required>
											</td>
											<td data-label="Potongan">
												<input type="number" name="potongan[]" id="potongan" value="0" required>
											</td>
										</tr>
										<?php $no++ ?>
									<?php } ?>
									<?php if (mysqli_num_rows($golongan) <= 0) { ?>
										<tr>
											<td colspan="9" style="text-align: left;">Maaf, data pegawai kosong, jadi anda tidak bisa menginputkan data kehadiran pegawai!</td>
										</tr>
									<?php } else { ?>
										<?php if ($jumlahPegawai > 0) { ?>
											<tr>
												<td colspan="10" style="text-align: left;">
													<a href="../kehadiran-pegawai/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" class="tombol-kehadiran"><i class="fas fa-angle-double-left"></i> Kembali</a>
													<button type="submit" name="simpan" class="tombol-kehadiran"><i class="fas fa-plus"></i> Simpan</button>
												</td>
											</tr>
										<?php } else { ?>
											<tr>
												<td colspan="10" style="text-align: left;">
													Maaf, data dari bulan dan tahun yang dipilih sudah di proses! Silakan lakukan edit data!
												</td>
											</tr>
										<?php } ?>
									<?php } ?>
								</form>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php
			break;
		case "edit":
			if ($levelsaya == "Pegawai") {
				echo "<script>
                document.location.href = '../kehadiran-pegawai/'
              </script>";
			}
			if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
				$bulan = $_GET['bulan'];
				$tahun = $_GET['tahun'];
				$bulantahun = $bulan . $tahun;
			} else {
				echo "<script>
				document.location.href = '../kehadiran-pegawai/'
			</script>";
			}

			if (isset($_POST["update"])) {
				if (editkehadiran($_POST) > 0) {
					echo "<script>
								alert('Data berhasil diedit!')
								document.location.href = '../kehadiran-pegawai/?bulan=$bulan&tahun=$tahun'
							</script>";
				}
			}
		?>
			<header>
				<h1>Tambah Data Kehadiran Pegawai</h1>
			</header>
			<div class="row card">
				<div class="col-md-4">
					<div class="container-items">
						<img src="../img/vector/data-kehadiran.png" alt="Hero Image">
					</div>
				</div>
				<div class="col-md-8">
					<div class="container-items">
						<?php foreach ($name as $nm) { ?>
							<h2>Halo <?= $nm["namapanggilan"]; ?>!</h2>
						<?php } ?>
						<p>Page data kehadiran pegawai menyimpan data kehadiran pegawai di L-1485 <i>Company</i> per-bulan.</p>
					</div>
				</div>
			</div>
			<div class="row tabel-data-admin">
				<div class="row tahun-bulan">
					<div class="row myalert">
						<div class="col">
							<p>Bulan : <span><?= $bulan; ?></span>, Tahun : <span><?= $tahun; ?></span></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="table-container">
						<?php
						$query = mysqli_query($conn, "SELECT master_gaji.*, user.*, jabatan.nama_jabatan FROM master_gaji INNER JOIN user ON master_gaji.nip=user.nip INNER JOIN jabatan ON user.kode_jabatan=jabatan.kode_jabatan WHERE master_gaji.bulan = '$bulantahun' AND user.level='Pegawai' ORDER BY user.nama ASC");
						$jumlahPegawai = mysqli_num_rows($query);
						$no = 1;
						?>
						<table class="table">
							<thead>
								<tr>
									<th>No</th>
									<th>Foto</th>
									<th>NIP</th>
									<th>Nama Pegawai</th>
									<th>Jabatan</th>
									<th>Masuk</th>
									<th>Sakit</th>
									<th>Izin</th>
									<th>Alpha</th>
									<th>Lembur</th>
									<th>Potongan</th>
								</tr>
							</thead>
							<tbody>
								<form method="post" action="">
									<?php foreach ($query as $d) { ?>
										<tr>
											<td data-label="No"><?= $no; ?></td>
											<td data-label="Foto">
												<img src="../img/user/<?= $d['pic']; ?>">
											</td>
											<td data-label="NIP"><?= $d['nip']; ?></td>
											<td data-label="Nama" style="text-transform: capitalize;"><?= $d['nama']; ?></td>
											<td data-label="Jabatan"><?= $d['nama_jabatan']; ?></td>
											<td data-label="Masuk">
												<input type="number" name="masuk[]" id="masuk" value="<?= $d['masuk']; ?>" required>
											</td>
											<td data-label="Sakit">
												<input type="number" name="sakit[]" id="sakit" value="<?= $d['sakit']; ?>" required>
											</td>
											<td data-label="Izin">
												<input type="number" name="izin[]" id="izin" value="<?= $d['izin']; ?>" required>
											</td>
											<td data-label="Alpha">
												<input type="number" name="alpha[]" id="alpha" value="<?= $d['alpha']; ?>" required>
											</td>
											<td data-label="Lembur">
												<input type="number" name="lembur[]" id="lembur" value="<?= $d['lembur']; ?>" required>
											</td>
											<td data-label="Potongan">
												<input type="number" name="potongan[]" id="potongan" value="<?= $d['potongan']; ?>" required>
											</td>
										</tr>
										<input type="hidden" name="bulan[]" value="<?= $bulantahun; ?>">
										<input type="hidden" name="id[]" value="<?= $d['id']; ?>">
										<input type="hidden" name="nip[]" value="<?= $d['nip']; ?>">
										<?php $no++ ?>
									<?php } ?>
									<tr>
										<td colspan="10" style="text-align: left;">
											<a href="../kehadiran-pegawai/?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" type="submit" class="tombol-kehadiran"><i class="fas fa-angle-double-left"></i> Kembali</a>
											<button type="submit" name="update" class="tombol-kehadiran"><i class="fas fa-edit"></i> Update</button>
										</td>
									</tr>
								</form>
							</tbody>
						</table>
					</div>
				</div>
			</div>
	<?php break;
	}
	?>
</div>
<script src="../js/searchKehadiran.js"></script>
<?php include '../modules/footer.php'; ?>