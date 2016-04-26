<?php

$this->load->view('template/header');

if (isset($content))
    echo $content;

$this->load->view('template/footer');
?>
