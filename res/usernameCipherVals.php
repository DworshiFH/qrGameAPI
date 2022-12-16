<?php
$ciphering = "AES-256-CBC";
$iv_length = openssl_cipher_iv_length($ciphering);
$options   = 0;
$encryption_iv = 'REDACTED'; //Secret redacted, for security purposes
$encryption_key = "REDACTED"; //Secret redacted, for security purposes
?>