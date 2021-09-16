<?php

session_start();

if (!isset($_SESSION["login"])) {
  header("Location: login/");
  exit;
} else {
  header("Location: home/");
  exit;
}
