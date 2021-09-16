<?php
session_start();
require '../function/functions.php';

if (!isset($_SESSION["login"])) {
   header("Location: ../login/");
   exit;
}

?>

<?php include '../modules/nav.php'; ?>
<div class="container">
   <form method="" action="">
      <input type="hidden" value="">
   </form>
   <header>
      <h1>Form Kontak</h1>
   </header>
   <div class="row">
      <div class="col-lg-5 card-kebawah w110">
         <img src="../img/vector/undraw_Envelope_re_f5j4.png" alt="">
         <h2>Halo Admin</h2>
         <p>Page daftar pegawai ini menyimpan semua data pegawai yang ada di EXO Company ini beserta detail khususnya.</p>
      </div>
      <div class="col-lg-5 kanan w110">
         <div class="form-kontak">
            <form method="" action="">
               <h2>Silakan isi form dibawah.</h2>
               <div class="row">
                  <div class="col-md-6">
                     <div class="input-data">
                        <div class="input-form">
                           <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Anda" autocomplete="off" required>
                           <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="input-data">
                        <div class="input-form">
                           <input type="text" name="email" id="email" placeholder="Masukkan Email Anda" autocomplete="off" required>
                           <label for="email"><i class="fas fa-at"></i> Email</label>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row textarea-input">
                  <div class="col">
                     <div class="styled-input wide">
                        <textarea required></textarea>
                        <label><i class="far fa-comment-alt"></i>Message</label>
                        <span></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <button class="tombol">Kirim</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include '../modules/footer.php'; ?>