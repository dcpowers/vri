<?php
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/tag' );
?>

<div class="container">
    <h2 class="title clearfix">Screening Questions</h2>
    <hr class="solidOrange" />
    <div class="btn-group">
        <?php
        echo $this->Html->link(
            '<i class="fa fa-plus"></i> Create Screening Questions',
            array('controller'=>'jobQuestions', 'action'=>'add', 'member'=>true),
            array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
        );
        ?>
    </div>
    
    <table class="table table-hover table-condensed" style="margin-top: 20px;">
        <thead>
            <tr class="tr-heading">
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        
        <tbody>
            <?php 
            foreach($jobQuestions as $key => $item){
                ?>
                <tr>
                    <td><?=$item['JobQuestion']['name']?></td>
                    
                    <td>
                        <ul class="list-inline">
                            <li>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-pencil-square-o"></i>', 
                                    array('controller'=>'jobQuestions', 'action'=>'edit', $item['JobQuestion']['id'], 'member'=>true),
                                    array('escape'=>false)
                                );
                                ?>
                            </li>
                            
                            <li>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-trash"></i>', 
                                    array('controller'=>'jobQuestions', 'action'=>'confirm', $item['JobQuestion']['id'], 'member'=>true),
                                    array(
                                        'escape'=>false,
                                        'id'=>$key,
                                        'data-toggle'=>'tooltip',
                                        'data-placement'=>'top',
                                        'title'=>'Delete: '.$item['JobQuestion']['name'],
                                        'data-toggle'=>'modal', 
                                        'data-target'=>'#myModal',
                                    )
                                );
                                ?>
                            </li>
                        </ul>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModal2").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        /*
        var edit_name_url = '<?php echo Router::url( array('controller'=>'JobTalentpatterns', 'action'=>'inline_edit', 'member'=>true ));?>';
        
        $('.jobTalentPattern').editable({
            disabled: false,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $(document).on('click','.editable-submit',function(){
            var id = $(this).closest('.wrap').attr('id');
            var value = $('.input-sm').val();
            var field = $(this).closest('.wrap').attr('field');
            
            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: { JobTalentPattern: { id: id, field: field, value: value }},
                success: function(s){
                    if(s == 'status'){
                        $(z).html(y);
                    }
                    
                    if(s == 'error') {
                        alert('Error Processing your Request!');
                    }
                },
                
                error: function(e){
                    alert('Error Processing your Request!!');
                } 
            });
        });
        */
    });
</script>            