<?php
$dbconfig = parse_ini_file('../dbconfig.ini');
$dbconfig['host'] = getenv('IP');
$dbconfig['user'] = getenv('C9_USER');
?>
