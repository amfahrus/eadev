<?php

class searchform {

    protected $_StartScript = '<script type="text/javascript">';
    protected $_EndScript = '</script>';
    protected $_StartDocReady = '$(document).ready(function() {';
    protected $_EndDocReady = '});';
    protected $_SearchForm = '';
    protected $_MaxCounter = 10;
    protected $_MultiSearch = "true";
    protected $_DataModel;
    protected $_DefaultValue;

    public function setMaxCounter($MaxCounter) {
        $this->_MaxCounter = $MaxCounter;
        return $this;
    }

    public function setMultiSearch($MultiSearch) {
        $this->_MultiSearch = $MultiSearch;
        return $this;
    }

    public function setDataModel($DataModel = array()) {
        $this->_DataModel = 'dataModel:[';
        foreach ($DataModel as $value) {
            $this->_DataModel .= "\n\t\t\t".'{';

            foreach ($value as $key => $value2) {

                if ($key == "ops") {
                    $this->_DataModel .= "ops: [";
                    foreach ($value2 as $value3) {
                        $this->_DataModel .= '"' . $value3 . '", ';
                    }
                    $this->_DataModel .= "]";
                } else {
                    $this->_DataModel .= $key . ': "' . $value2 . '", ';
                }
            }
            $this->_DataModel .= '}, ';
        }
        $this->_DataModel .="\n\t\t\t". '],';
        return $this;
    }

    public function setDefaultValue($DefaultValue = array()) {
        $this->_DefaultValue = 'defaultValue:[';
        foreach ($DefaultValue as $value) {
            $this->_DefaultValue .= "\n\t\t\t".'{';
            foreach ($value as $key => $value2) {

                if ($key == "ops") {
                    $this->_DefaultValue .= "ops: [";
                    foreach ($value2 as $value3) {
                        $this->_DefaultValue .= '"' . $value3 . '", ';
                    }
                    $this->_DefaultValue .= "]";
                } else {
                    $this->_DefaultValue .= $key . ': "' . $value2 . '", ';
                }
            }
            $this->_DefaultValue .= '}, ';
        }
        $this->_DefaultValue .= "\n\t\t\t". ']';
        return $this;
    }

    public function genSearchForm() {
        $this->_SearchForm = '';
        $this->_SearchForm .= $this->_StartScript . "\n";
        $this->_SearchForm .= $this->_StartDocReady . "\n";
        $this->_SearchForm .= "\t" . '$(".form_search").IvanSearch({' . "\n";
        $this->_SearchForm .= "\t\t" . 'maxcounter : ' . $this->_MaxCounter . ' ,' . "\n";
        $this->_SearchForm .= "\t\t" . 'multisearch : ' . $this->_MultiSearch . ',' . "\n";
        $this->_SearchForm .= "\t\t" . $this->_DataModel . "\n";
        $this->_SearchForm .= "\t\t" . $this->_DefaultValue . "\n";
        $this->_SearchForm .= "\t" . '});' . "\n";
        $this->_SearchForm .= $this->_EndDocReady . "\n";
        $this->_SearchForm .= $this->_EndScript . "\n";

        return $this->_SearchForm;
    }

}
