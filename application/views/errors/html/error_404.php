<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 Page Not Found</title>

</head>
<body>
<?php
$mes = array('code' => 404, 'message' => 'not found');
echo json_encode($mes);

?>
</body>
</html>