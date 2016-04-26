<?php
if (!isset($current_tab))
    $current_tab = '';
?>
<a href="<?= base_url(); ?>" class="tab <?php if ($current_tab == 'dashboard') echo 'sel'; ?>"><span class="label">Dashboard</span></a>
<?php
$tabs = $this->session->userdata('tabs');
if (!$tabs)
    $tabs = array();
foreach ($tabs as $tab) {
    ?>
    <a href="<?= site_url($tab['link']); ?>" class="tab <?php if ($current_tab == $tab['link']) echo 'sel'; ?>"><span class="label"><?= $tab['icon']; ?> <?= $tab['modules']; ?></span></a>
    <a href="<?= site_url('main/close_tab/' . $tab['link']); ?>" class="tab_close"></a>                                        
<?php } ?>		

