
<?php 
echo $this->Form->create('Search', array(
    'url' => array('controller'=>'users', 'action'=>'search', 'member'=>true), 
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden( 'id', array( 'value' => $content['DetailUser']['id'] ) );
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?=$title?></h4>
</div>            <!-- /modal-header -->

<div class="modal-body select2-container">
    <p>Select the occupation you are interested in. You can select a many as you are interested in.</p>
        <?php
        echo $this->Form->input('soc', array(
            'type' => 'select', 
            'multiple' => true,
            'options' => $search,
            'class'=>'select2 select2-multiple select2-choices',
            'data-placeholder'=>'CLICK HERE to choose occupation(s),
            'id'=>'e1'
           
         ));
         
         
        //foreach($search['link'] as $link){
          //  pr($link);
            
        //}
        ?>
    
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <?php echo $this->Form->button('Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
    <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
</div>
<?php echo $this->Form->end();?>    

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#e1").select2();
    });
</script>
