<?php

session_start();
$_SESSION = [];
session_unset();
session_destroy();

setcookie('id', '', time() - 1);
setcookie('username', '', time() - 1);

header("Location: ../login/");
exit;
