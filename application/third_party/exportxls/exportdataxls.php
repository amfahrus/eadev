<?php

require_once "exportdata.php";

class ExportDataExcel extends ExportData {
    const XmlHeader = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
    const XmlFooter = "</Workbook>";

    public $encoding = 'UTF-8'; // encoding type to specify in file.
// Note that you're on your own for making sure your data is actually encoded to this encoding
    public $title = 'Sheet1'; // title for Worksheet
    public $_rowStyle;

    function generateHeader() {

// workbook header
        $output = stripslashes(sprintf(self::XmlHeader, $this->encoding)) . "\n";

// Set up styles
        $output .= "<Styles>\n";
        $output .= "<Style ss:ID=\"sDT\"><NumberFormat ss:Format=\"Short Date\"/></Style>\n";
        $output .= "<Style ss:ID=\"s116\">";
        $output .= "<Borders>";
        $output .= "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "</Borders>";
        $output .= "<Interior ss:Color=\"#DBE5F1\" ss:Pattern=\"Solid\"/>";
        $output .= "</Style>";
        $output .= "<Style ss:ID=\"s117\">";
        $output .= "<Borders>";
        $output .= "<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "<Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>";
        $output .= "</Borders>";
        $output .= "<Interior ss:Color=\"#F2F2F2\" ss:Pattern=\"Solid\"/>";
        $output .= "</Style>";
        $output .= "</Styles>\n";

        return $output;
    }

    public function _createSheet() {
        $output .= sprintf("<Worksheet ss:Name=\"%s\">\n <Table>\n", htmlentities($this->title));
        return $output;
    }

    public function _closeSheet() {
        $output .= " </Table>\n</Worksheet>\n";
        return $output;
    }

    function generateFooter() {
        $output = '';
        $output .= self::XmlFooter;
        return $output;
    }

    function generateRow($row) {
        $output = '';
        $output .= " <Row>\n";

        $no = 1;
        foreach ($row as $k => $v) {
            $cek = $no % 2;
            if ($cek == 0) {
                $this->_rowStyle = "s116";
            } else {
                $this->_rowStyle = "s117";
            }
            $output .= $this->generateCell($v);
            $no++;
        }
        $output .= " </Row>\n";
        return $output;
    }

    private function generateCell($item) {
        $output = '';
        $style = '';

        if (preg_match("/^-?\d+(?:[.,]\d+)?$/", $item) && (strlen($item) < 15)) {
            $type = 'Number';
        } elseif (preg_match("/^(\d{1,2}|\d{4})[\/\-]\d{1,2}[\/\-](\d{1,2}|\d{4})([^\d].+)?$/", $item) &&
                ($timestamp = strtotime($item)) &&
                ($timestamp > 0) &&
                ($timestamp < strtotime('+500 years'))) {
            $type = 'DateTime';
            $item = strftime("%Y-%m-%dT%H:%M:%S", $timestamp);
            $style = 'sDT'; // defined in header; tells excel to format date for display
        } else {
            $type = 'String';
        }

        $item = str_replace('&#039;', '&apos;', htmlspecialchars($item, ENT_QUOTES));
        $output .= " ";
        $output .= $this->_rowStyle ? "<Cell ss:StyleID=\"" . $this->_rowStyle . "\">" : "<Cell>";
        $output .= sprintf("<Data ss:Type=\"%s\">%s</Data>", $type, $item);
        $output .= "</Cell>\n";

        return $output;
    }

    function sendHttpHeaders() {
        header("Content-Type: application/vnd.ms-excel; charset=" . $this->encoding);
        header("Content-Disposition: inline; filename=\"" . basename($this->filename) . "\"");
    }

}