<?php

// php-export-data by Eli Dickinson, http://github.com/elidickinson/php-export-data

/**
 * ExportData is the base class for exporters to specific file formats. See other
 * classes below.
 */
abstract class ExportData {

    protected $exportTo; // Set in constructor to one of 'browser', 'file', 'string'
    protected $stringData; // stringData so far, used if export string mode
    protected $tempFile; // handle to temp file (for export file mode)
    protected $tempFilename; // temp file name and path (for export file mode)
    
    protected $_title;
    public $filename; // file mode: the output file name; browser mode: file name for download; string mode: not used

    public function __construct($exportTo = "browser", $filename = "exportdata") {
        if (!in_array($exportTo, array('browser', 'file', 'string'))) {
            throw new Exception("$exportTo is not a valid ExportData export type");
        }
        $this->exportTo = $exportTo;
        $this->filename = $filename;
    }

    public function initialize() {

        switch ($this->exportTo) {
            case 'browser':
                $this->sendHttpHeaders();
                break;
            case 'string':
                $this->stringData = '';
                break;
            case 'file':
                $this->tempFilename = tempnam(sys_get_temp_dir(), 'exportdata');
                $this->tempFile = fopen($this->tempFilename, "w");
                break;
        }

        $this->write($this->generateHeader());
    }

    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    public function createSheet() {
        $this->title = $this->_title;
        $this->write($this->_createSheet());
    }

    public function closeSheet() {
        $this->write($this->_closeSheet());
    }

    public function addRow($row) {
        $this->write($this->generateRow($row));
    }

    public function finalize() {

        $this->write($this->generateFooter());

        switch ($this->exportTo) {
            case 'browser':
                flush();
                break;
            case 'string':
// do nothing
                break;
            case 'file':
// close temp file and move it to correct location
                fclose($this->tempFile);
                rename($this->tempFilename, $this->filename);
                break;
        }
    }

    public function getString() {
        return $this->stringData;
    }

    abstract public function sendHttpHeaders();

    protected function write($data) {
        switch ($this->exportTo) {
            case 'browser':
                echo $data;
                break;
            case 'string':
                $this->stringData .= $data;
                break;
            case 'file':
                fwrite($this->tempFile, $data);
                break;
        }
    }

    protected function generateHeader() {
        
    }

    protected function generateFooter() {
        
    }

    protected function _createSheet() {
        
    }

    protected function _closeSheet() {
        
    }

    abstract protected function generateRow($row);
}