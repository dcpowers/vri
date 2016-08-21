    <?php
    if (!empty($data)) {
        
        echo '<option value="">Select A User</option>';
        
        foreach ($data as $k => $v) {
            echo '<option value="' . $k . '">' . $v . '</option>';
        }
    } else {
        echo '<option value="">' . __('No Options Available') . '</option>';
    }
    ?>