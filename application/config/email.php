<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587; // Puerto para TLS
$config['smtp_user'] = 'crenasasrl2@gmail.com'; // Tu correo electrónico
$config['smtp_pass'] = 'Cr3n4s4SRL2'; // La contraseña del correo electrónico
$config['mailtype'] = 'html';
$config['charset']  = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['smtp_crypto'] = 'tls'; // Usar TLS para Gmail
$config['newline'] = "\r\n"; // Importante para el correcto envío de correos
$config['crlf'] = "\r\n"; // También importante para el correcto envío de correos
