    <div class="page-header" style="border-bottom: 2px #FFAB00 solid;">
        <h1 style="color: #FFAB00"><i class="fa fa-clipboard"></i><span class="text"><?php echo __('Testing/Assessments');?></span></h1>
    </div>
    <?php 
    echo $this->Html->link( 
        '<i class="fa fa-plus"></i> Add A New Test',
        array('controller'=>'tests', 'action'=>'add', 'admin'=>true),
        array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs') ); 
    ?>
    
    <table class="table table-striped" id="information">
        <thead>
            <tr>
                <th>Name</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:center">Credits</th>
                <th>Group</th>
                <th>Schedule Type</th>
                <th>Function Name</th>
            </tr>
        </thead>
        
        <tbody>
            <?php 
            foreach($tests as $test){
                $is_active = ($test['Test']['is_active'] == 1) ? '<i class="fa fa-check-circle fa-lg text-success"></i>' : '<i class="fa fa-times-circle fa-lg text-danger"></i>' ;
                $group_name = (!empty($test['Group']['name'])) ? $test['Group']['name'] : NULL ;
                ?>
                <tr>
                    <td>
                        <?php echo $this->Html->link( $test['Test']['name'],   array('controller'=>'tests', 'admin'=>true, 'action'=>'index', $test['Test']['id']) );?>
                    </td>
                    <td style="text-align:center">
                        <div class="wrap" id="<?=$test['Test']['id']?>" field="is_active">
                            <span id="is_active<?=$test['Test']['id']?>" class="is_active editable editable-click" style="display: inline;" data-type="select" data-pk="2" data-value="<?php echo $test['Test']['is_active']; ?>" data-title="Status">
                                <?php echo $settings['options'][$test['Test']['is_active']]; ?>
                            </span>
                        </div>    
                    </td>
                    <td style="text-align:center">
                        <div class="wrap" id="<?=$test['Test']['id']?>" field="credits">
                            <span id="credits<?=$test['Test']['id']?>" class="credits editable editable-click" style="display: inline;" data-type="select" data-pk="2" data-value="<?php echo $test['Test']['credits']; ?>" data-title="Credits">
                                <?=$test['Test']['credits'] ?>
                            </span>
                        </div>
                        
                    </td>
                    
                    <td><?=$group_name?></td>
                    
                    <td>
                        <div class="wrap" id="<?=$test['Test']['id']?>" field="schedule_type">
                            <span id="schedule_type<?=$test['Test']['id']?>" class="schedule_type editable editable-click" style="display: inline;" data-type="select" data-pk="2" data-value="<?php echo $test['Test']['schedule_type']; ?>" data-title="Schedule Type">
                                <?php echo $settings['scheduleType'][$test['Test']['schedule_type']]; ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="wrap" id="<?=$test['Test']['id']?>" field="cal_function">
                            <span id="cal_function<?=$test['Test']['id']?>" class="cal_function editable editable-click" style="display: inline;"><?=$test['Test']['cal_function'] ?></span>
                        </div>
                    </td>
                </tr>
                <?php   
            }
            ?>
        </tbody>
    </table>
    
    
<script type="text/javascript">
    jQuery(document).ready( function($) {
        $('.cal_function').editable({
            type: 'text',
            name: 'cal_function'
        });
        
        $('.name').editable({
            type: 'text',
            name: 'name'
        });
    
        $('.credits').editable({                                    
            source: [
                {value: '0', text: '0'},
                {value: '1', text: '1'},
                {value: '2', text: '2'},
                {value: '3', text: '3'},
                {value: '4', text: '4'},
                {value: '5', text: '5'},
                {value: '6', text: '6'},
                {value: '7', text: '7'},
                {value: '8', text: '8'},
                {value: '9', text: '9'},
                {value: '10', text: '10'}
                
            ],
        });
        
        $('.is_active').editable({
            source: [
                {value: '1', text: 'Active'},
                {value: '0', text: 'Inactive'},
            ],
            
        });
        
        $('.schedule_type').editable({
            source: <?php echo json_encode($settings['scheduleType']); ?>,
        });
        
        var edit_name_url = '<?php echo Router::url( array('controller'=>'tests', 'action'=>'inline_edit', 'admin'=>true ));?>';
    
        $(document).on('click','.editable-submit',function(){
            var id = $(this).closest('.wrap').attr('id');
            var value = $('.input-sm').val();
            var field = $(this).closest('.wrap').attr('field');
            
            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: { Test: { id: id, field: field, value: value }},
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
    });
</script>