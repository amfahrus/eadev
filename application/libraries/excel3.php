<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/exportxls/exportdataxls.php";

class excel extends ExportDataExcel {

    public function __construct() {
        parent::__construct();
        $this->exportTo = "browser";
    }

}
