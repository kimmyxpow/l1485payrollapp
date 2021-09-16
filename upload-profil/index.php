<?php

session_start();
require '../function/functions.php';

if (isset($_POST['submit'])) {
  if (foto($_POST) > 0) {
    $_SESSION['login'] = true;
    echo "<script>document.location.href = '../home/'</script>";
  } else {
    echo mysqli_error($conn);
  }
}

?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>L-1485 | Upload Foto</title>
  <link rel="icon" href="../img/logo/logoOnly.png">
  <link rel="stylesheet" type="text/css" href="main.css">
  <!-- <link rel="stylesheet" href="../../upload-profil/main.css"> -->
  <script>
    (function(e, t, n) {
      var r = e.querySelectorAll("html")[0];
      r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
    })(document, window, 0);
  </script>
</head>

<body>
  <div class="container">
    <header class="codrops-header">
      <h1>Upload Foto</h1>
    </header>
    <div class="content">
      <form method="post" action="" enctype="multipart/form-data">
        <div class="box">
          <input type="file" name="cover" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
          <label for="file-5">
            <figure>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
              </svg>
            </figure>
            <span>Choose a file&hellip;</span>
          </label>
        </div>
        <!-- <?= var_dump($_SESSION['jir']); ?> -->
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </div>
  <script src="script.js"></script>
</body>

</html>