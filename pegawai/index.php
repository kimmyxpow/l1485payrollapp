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
			$golongan = mysqli_query($conn, "SELECT user.*, jabatan.nama_jabatan, golongan.nama_golongan FROM user INNER JOIN jabatan ON user.kode_jabatan = jabatan.kode_jabatan INNER JOIN golongan ON user.kode_golongan=golongan.kode_golongan WHERE user.level = 'pegawai' ORDER BY nama ASC");
			if (isset($_POST['hapus'])) {
				$nama = $_POST['nama'];
				if (hapususer($_POST) > 0) {
					if (hapuskehadiran($_SESSION['hapuskehadiran']) > 0) {
						echo "<script>
									alert ('User $nama berhasil dihapus!')
									document.location.href = '../pegawai/'
							</script>";
					} else {
						echo "<script>
									document.location.href = '../pegawai/'
							</script>";
					}
				} else {
					echo "<script>
                  alert ('User $nama gagal dihapus!')
                  document.location.href = '../pegawai/'
								</script>";
				}
			}

			$_SESSION['kembali'] = "pegawai";

			if (isset($_POST['edit'])) {
				$_SESSION['peg'] = htmlspecialchars($_POST["id"]);
				echo "<script>
								document.location.href = '../pegawai/?view=edit'
						</script>";
			}

			if (isset($_POST["cari"])) {
				$golongan = caripegawai($_POST["keyword"]);
			}

			if (isset($_POST['detail'])) {
				$_SESSION['detail'] = "pegawai";
				$u = $_POST['username'];
				$_SESSION['level'] = $_POST['level'];
				echo "<script>
                        document.location.href = '../detail/?username=$u'
                </script>";
			}

			// if (mysqli_num_rows($golongan) > 0) {
			foreach ($golongan as $d) {
				$count[] = $d['nip'];
			}
			if (mysqli_num_rows($golongan) > 0) {
				$jumlahData = count($count);
			} else {
				$jumlahData = 0;
			}
			// }
	?>
			<header>
				<h1>Data pegawai</h1>
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
						<p>Page data pegawai ini menyimpan semua data pegawai yang ada di EXO Company ini. Sampai saat ini terdapat <strong><?= $jumlahData; ?></strong> pegawai di L-1485.</p>
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
							<a href="../pegawai/?view=tambah" class="tombol">&plus; Tambah Data</a>
						</div>
					<?php } ?>
				</div>
				<div class="row">
					<div class="table-container" id="table-container">
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
								<?php foreach ($golongan as $d) { ?>
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
                document.location.href = '../pegawai/'
              </script>";
			}
			$kembali = $_SESSION['kembali'];
			if (isset($_POST["submit"])) {
				$nama = htmlspecialchars($_POST["nama"]);
				$cap = "'text-transform: capitalize;'";
				if (tambahuser($_POST) > 0) {
					echo "<script>
											alert ('User $nama berhasil ditambahkan!')
											document.location.href = '../pegawai/'
                  </script>";
				} else {
					echo "<script>
											alert ('User $nama gagal ditambahkan!')
											document.location.href = '../pegawai/'
                </script>";
				}
			}
		?>
			<header>
				<h1>Tambah data pegawai</h1>
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
						<p>Kamu bisa menambahkan data pegawai di page ini.</p>
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
		case 'edit':
			if ($levelsaya == "Pegawai") {
				echo "<script>
                document.location.href = '../pegawai/'
              </script>";
			}
			$kembali = $_SESSION['kembali'];
			$id  = $_SESSION["peg"];
			$inidata = query("SELECT * FROM user WHERE id = $id")[0];
			if (isset($_POST["submit"])) {
				if (edituser($_POST) > 0) {
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
									<input type="number" name="nip" id="nip" placeholder="Masukkan NIP Anda" value="<?= $inidata["nip"]; ?>" readonly>
									<label for="nip"><i class="far fa-id-card"></i> NIP (Tidak bisa diedit)</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<div class="input-form">
										<input type="date" name="date" id="date" placeholder="Masukkan date anda" value="<?= $inidata["tgl"]; ?>" autocomplete="off" required>
										<label for="date"><i class="fas fa-baby"></i> Tanggal Lahir</label>
									</div>
								</div>
								<div class="col-md">
									<div class="input-form">
										<input type="text" name="nama" value="<?= $inidata["nama"]; ?>" id="nama" placeholder="Masukkan nama anda" autocomplete="off" required>
										<label for="nama"><i class="fas fa-user"></i> Nama</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<div class="input-form">
										<input type="email" name="email" id="email" value="<?= $inidata["email"]; ?>" placeholder="Masukkan email anda" autocomplete="off" required>
										<label for="email"><i class="fas fa-at"></i> Email</label>
									</div>
								</div>
								<div class="col-md">
									<div class="input-form">
										<input type="number" name="no" id="no" value="<?= $inidata["no"]; ?>" placeholder="Masukkan no anda" autocomplete="off" required>
										<label for="no"><i class="fas fa-phone-alt"></i> Nomor Telepon</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<div class="select-form bawah">
										<select name="jab">
											<option value="Default">- Pilih Jabatan -</option>
											<?php $jabatan = query("SELECT * FROM jabatan WHERE NOT kode_jabatan = 'Belum Diatur' ORDER BY kode_jabatan ASC"); ?>
											<?php foreach ($jabatan as $jbt) { ?>

												<?php $selected = ($jbt['kode_jabatan'] == $inidata['kode_jabatan']) ? 'selected="selected"' : ""; ?>

												<option value="<?= $jbt['kode_jabatan']; ?>" <?= $selected; ?>><?= $jbt['kode_jabatan']; ?> - <?= $jbt['nama_jabatan'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md">
									<div class="select-form">
										<select name="gol" id="golongan">
											<option value="Default">- Pilih Golongan -</option>
											<?php $golongan = query("SELECT * FROM golongan WHERE NOT kode_golongan = 'Belum Diatur' ORDER BY kode_golongan ASC"); ?>
											<?php foreach ($golongan as $gol) { ?>

												<?php $selected = ($gol['kode_golongan'] == $inidata['kode_golongan']) ? 'selected="selected"' : ""; ?>

												<option value="<?= $gol['kode_golongan']; ?>" <?= $selected; ?>><?= $gol['kode_golongan']; ?> - <?= $gol['nama_golongan'] ?></option>
											<?php } ?>
										</select>
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
									<label for="jmlh"><i class="fas fa-child"></i> Jumlah Anak</label>
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
						<div class="input-data">
							<a href="../<?= $kembali; ?>" class="tombol-kembali"><i class="fas fa-angle-double-left"></i> Kembali</a>
							<button class="tombol" name="submit"><i class="fas fa-edit"></i> Edit</button>
							<p>Klik <a type="submit" class="link" href="../pegawai/?view=edit-user">disini</a> jika anda ingin mengubah username & password.</p>
						</div>
					</form>
				</div>
			</div>
		<?php
			break;
		case 'edit-user':
			if ($levelsaya == "Pegawai") {
				echo "<script>
                document.location.href = '../pegawai/'
              </script>";
			}
			$id  = $_SESSION["peg"];
			$kembali = $_SESSION['kembali'];
			$inidata = query("SELECT * FROM user WHERE id = $id")[0];
			if (isset($_POST["submit"])) {
				$_SESSION['username2'] = $_POST['username'];
				if (edituserdata2($_POST) > 0) {
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
									<label for="username"><i class="fas fa-user-tag"></i> Username Baru (username lama = <?= $inidata['username']; ?>)</label>
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
							<p>Klik <a class="link" href="../pegawai/?view=edit">disini</a> jika anda tidak ingin mengubah username & password.</p>
						</div>
					</form>
				</div>
			</div>
	<?php break;
	}
	?>
</div>
<script src="../js/search.js"></script>
<?php include '../modules/footer.php'; ?>