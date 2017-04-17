<?php
function RecursiveCategories($array) {

    if (count($array)) {
            echo "\n<ul>\n";
        foreach ($array as $vals) {

                    echo "<li id=\"".$vals['Test']['id']."\">";
                    
                    echo "<a href='/member/tests/moveup/". $vals['Test']['id']."'>".$vals['Test']['name']."</a>";
                    
                    if (count($vals['children'])) {
                            RecursiveCategories($vals['children']);
                    }
                    echo "</li>\n";
        }
            echo "</ul>\n";
    }
} ?>

<?= RecursiveCategories($categories) ?>