<a href="<?= base_url(); ?>"><span>Dashboard</span></a>
<?php
$tabs = $this->session->userdata('tabs');
if (!$tabs)
    $tabs = array();

foreach ($tabs as $tab) {
    ?>
    <a href="<?= site_url($tab['link']); ?>"><span><?= $tab['icon']; ?> <?= $tab['modules']; ?></span></a>
<?php } ?>			
