    <?php
    if (!empty($data)) {
        
        foreach ($data as $k => $v) {
            echo '<option value="' . $k . '" selected="selected">' . $v . '</option>';
        }
    } else {
        echo '<option value="">' . __('No Options Available') . '</option>';
    }
    ?>