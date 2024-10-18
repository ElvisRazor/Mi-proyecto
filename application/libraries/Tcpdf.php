<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Ruta a la carpeta donde colocaste TCPDF

class Tcpdf_lib extends TCPDF {
    public function __construct() {
        parent::__construct();
    }
}
