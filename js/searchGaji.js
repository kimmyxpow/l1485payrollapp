// Ambil elemen2 yang dibutuhkan

var keyword = document.getElementById('keyword');
var levelsaya = document.getElementById('levelsaya');
var tahun = document.getElementById('tahun');
var bulan = document.getElementById('bulan');
var tombolCari = document.getElementById('tombol-cari');
var container = document.getElementById('table-container');

// Tambahkan event ketika keyword ditulis

keyword.addEventListener('keyup', function () {
  // Buat object ajax
  var xhr = new XMLHttpRequest();

  // Cek kesiapan ajax
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      container.innerHTML = xhr.responseText;
    }
  }

  // Eksekusi ajax
  xhr.open('GET', '../ajax/searchGaji.php?keyword=' + keyword.value + '&bulan=' + bulan.value + '&tahun=' + tahun.value + "&level=" + levelsaya.value, true);
  xhr.send();
});