<!-- static navbar -->
<nav class="navbar navbar-default navbar-static-bottom">
    <div class="container">
        <p class="text-muted"><?php auto_copyright('2016'); ?> Vanguard Resources. All rights reserved.</p>
    </div>
</nav>
<!-- end: nav bar -->

<?php 
function auto_copyright($year = 'auto'){ 
    if(intval($year) == 'auto'){ $year = date('Y'); }
    if(intval($year) == date('Y')){ echo '&copy; '.intval($year); }
    if(intval($year) < date('Y')){ echo '&copy; '.intval($year) . ' - ' . date('Y'); }
    if(intval($year) > date('Y')){ echo '&copy; '.date('Y'); }
} 
?>