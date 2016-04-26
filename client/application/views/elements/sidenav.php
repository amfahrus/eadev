<!-- Begin Side Navigation -->
<?php
/*if (isset($navs)) {
    
//    echo "<pre>";
//    print_r($navs);
    
    
    foreach ($navs as $parent) {
        echo "\n\t\t\t\t\t<a href='#' class='tognav'><span style='background-image: url(" . base_url() . "media/modules/" . $parent['row']['icon'] . ")'>" . $parent['row']['modules'] . "</span></a>";
        echo "\n\t\t\t\t\t<div class='togsub'>";
        foreach ($parent['child'] as $module) {
            $module_link = "#";
            $class = "class='tognav'";
            if ($module['row']['link'] != "#") {
                $module_link = $module['row']['link'];
                $class = "";
            }
            echo "\n\t\t\t\t\t\t<a href='" . base_url() . $module_link . "' $class><span style='background-image: url(" . base_url() . "media/modules/" . $module['row']['icon'] . ")'>" . $module['row']['modules'] . "</span></a>";
            if (count($module['child'])) {
                echo "\n\t\t\t\t\t<div class='togsub s2'>";
                foreach ($module['child'] as $submodule) {
                    $module_link = "#";
                    if ($submodule['row']['link'] != "#")
                        $module_link = $submodule['row']['link'];
                    echo "\n\t\t\t\t\t<a href='" . base_url() . $module_link . "'><span style='background-image: url(" . base_url() . "media/modules/" . $submodule['row']['icon'] . ")'>" . $submodule['row']['modules'] . "</span></a>";
                }
                echo "\n\t\t\t\t\t</div>\n";
            }
        }
        echo "\n\t\t\t\t\t</div>\n";
    }
}*/
?>

<div class="dtree">
    <script type="text/javascript">
        d = new dTree('d', root);
        d.add(0, -1, 'Main Menu');
<?php
if (isset($navs)) {
    foreach ($navs as $parent) {
        if ($parent["link"] == "#") {
            echo "d.add(" . $parent["id_modules"] . ", " . $parent["parent"] . ", '" . $parent["module_icon"] . " " . $parent["modules"] . "', '#','','','" . base_url() . "media/modules/" . $parent["module_icon"] . "');\n";
        } else {
            echo "d.add(" . $parent["id_modules"] . ", " . $parent["parent"] . ", '" . $parent["module_icon"] . " " . $parent["modules"] . "', '" . base_url() . $parent["link"] . "','','','" . base_url() . "media/modules/" . $parent["module_icon"] . "');\n";
        }
    }
}
?>
    document.write(d);
    </script>
</div>
<!-- End Side Navigation -->



