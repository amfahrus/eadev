<div class="content">
    <?php echo $error; ?>
    <?= form_open_multipart('mod_importdbf/test_action'); ?>
    <input type="file" name="userfile" size="20" />
    <br />
    <input type="submit" value="upload" />
    <?= form_close(); ?>
</div>
