<section class="page-block">
    <div class="container">
        <h1><?php echo __('Select A Department');?></h1>
        <div class="row-fluid">
            <div class="obt_reports report department span9">
                <?php echo $this->Form->input( 'group_id', array('options'=>$groups, 'label'=>false) ); ?>
            </div>
        </div>
    </div>
</section>
<script>

jQuery(document).ready( function($) {
    $('#group_id').on('change', function( ) {
        var group_id = $(this).val();
        var url = '<?php echo Router::url( array('controller'=>'BlindTests', 'action'=>'department' ) ); ?>';
        window.location.href = url + '/' + group_id + '/' + <?=$id?>;

    });
    
});
</script>