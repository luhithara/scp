<?php

header('Content-Type: text/plain; charset=utf-8');

$temp = sys_get_temp_dir();

echo "PHP VERSION: " . PHP_VERSION . PHP_EOL;
echo "PHP INI: " . (php_ini_loaded_file() ?: 'Tidak ditemukan') . PHP_EOL;
echo "UPLOAD TMP DIR: " . (ini_get('upload_tmp_dir') ?: '(kosong)') . PHP_EOL;
echo "SYS TEMP DIR: " . (ini_get('sys_temp_dir') ?: '(kosong)') . PHP_EOL;
echo "TEMP AKTIF: " . $temp . PHP_EOL;
echo "TEMP ADA: " . (is_dir($temp) ? 'YA' : 'TIDAK') . PHP_EOL;
echo "TEMP BISA DITULIS: " . (is_writable($temp) ? 'YA' : 'TIDAK') . PHP_EOL;
echo "UPLOAD MAX FILESIZE: " . ini_get('upload_max_filesize') . PHP_EOL;
echo "POST MAX SIZE: " . ini_get('post_max_size') . PHP_EOL;
echo "FILE UPLOADS: " . ini_get('file_uploads') . PHP_EOL;