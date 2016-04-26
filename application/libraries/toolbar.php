<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class toolbar {

    protected $_form;

    public function create_toolbar() {
        $this->_form = '<div class="btn-toolbar" style="margin: 0;">';
    }

    public function cGroupButton() {
        $this->_form .= '<div class="btn-group">';
    }

    /*
     * @param string $label
     * @param string $class
     * @param string $type
     * @param string $name
     * @param string $id
     * @param string $icon
     * @param string $dataoriginaltitle
     * @param string $datatoggle
     * @param string $dataplacement
     */
    public function addButton($label = "", $class = "btn", $type = "button", $name = "", $id = "", $icon = "", $dataoriginaltitle = "", $datatoggle = "", $dataplacement = "") {
        $this->_form .= '<button';

        if (isset($name) AND !empty($name)) {
            $this->_form .= ' name="' . $name . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($type) AND !empty($type)) {
            $this->_form .= ' type="' . $type . '"';
        } else {
            $this->_form .= ' type="button"';
        }

        if (isset($class) AND !empty($class)) {
            $this->_form .= ' class="' . $class . '"';
        } else {
            $this->_form .= ' class="btn"';
        }

        if (isset($id) AND !empty($id)) {
            $this->_form .= ' id="' . $id . '"';
        } else {
            $this->_form .= ' ';
        }

        if (isset($dataoriginaltitle) AND !empty($dataoriginaltitle)) {
            $this->_form .= ' data-original-title="' . $dataoriginaltitle . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($datatoggle) AND !empty($datatoggle)) {
            $this->_form .= ' data-toggle="' . $datatoggle . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($dataplacement) AND !empty($dataplacement)) {
            $this->_form .= ' data-placement="' . $dataplacement . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($icon) AND !empty($icon)) {
            $this->_form .= '><i class="' . $icon . '"></i>';
        } else {
            $this->_form .= '>';
        }

        if (isset($label) AND !empty($label)) {
            $this->_form .= ' ' . $label;
        } else {
            $this->_form .= '';
        }

        $this->_form .= '</button>';
    }

    /*
     * @param $label untuk menampilkan label
     * @param $class untuk set tag class 
     * @param $href untuk set tag href
     * @param $id untuk set tag id
     * @param $icon untuk set class pada tag <i> pada tag <a>
     * @param $dataoriginaltitle pesan dari tooltip
     * @param $datatoggle
     * @param $dataplacement arah dari tooltip "top, bottom, left, right"
     */

    public function addLink($label = "", $class = "btn", $href = "#", $id = "", $icon = "", $dataoriginaltitle = "", $datatoggle = "", $dataplacement = "") {
        $this->_form .= '<a';

        if (isset($class) AND !empty($class)) {
            $this->_form .= ' class="' . $class . '"';
        } else {
            $this->_form .= ' class="btn"';
        }

        if (isset($href) AND !empty($href)) {
            $this->_form .= ' href="' . $href . '"';
        } else {
            $this->_form .= ' href="#"';
        }

        if (isset($id) AND !empty($id)) {
            $this->_form .= ' id="' . $id . '"';
        } else {
            $this->_form .= ' ';
        }

        if (isset($dataoriginaltitle) AND !empty($dataoriginaltitle)) {
            $this->_form .= ' data-original-title="' . $dataoriginaltitle . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($datatoggle) AND !empty($datatoggle)) {
            $this->_form .= ' data-toggle="' . $datatoggle . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($dataplacement) AND !empty($dataplacement)) {
            $this->_form .= ' data-placement="' . $dataplacement . '"';
        } else {
            $this->_form .= '';
        }

        if (isset($icon) AND !empty($icon)) {
            $this->_form .= '><i class="' . $icon . '"></i>';
        } else {
            $this->_form .= '>';
        }

        if (isset($label) AND !empty($label)) {
            $this->_form .= ' ' . $label;
        } else {
            $this->_form .= '';
        }

        $this->_form .= '</a>';
    }

    public function eGroupButton() {
        $this->_form .= '</div>';
    }

    public function generate() {
        return $this->_form .= '</div>';
    }

}
