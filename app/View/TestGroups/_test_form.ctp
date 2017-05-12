<?php
    #$settings['scheduleType']['Single'] = 'Assessment';
    #$settings['scheduleType']['Group'] = 'Survey';
    $settings['scheduleType']['MultiplePeople'] = 'Evaluation';
?>
<div class="account index bg-white">
	<div class="dashhead">
    	<div class="dashhead-titles">
        	<h6 class="dashhead-subtitle">Evaluations</h6>
            <h3 class="dashhead-title"><i class="fa fa-clipboard fa-fw"></i>Creation Tool</h3>
        </div>
        <div class="dashhead-toolbar">
        	<?php echo $this->element( 'TestGroups/menu' );?>
        </div>
    </div>

	<table class="table table-striped" id="information">
        <thead>
            <tr>
                <th class="col-md-4">Name</th>
                <th class="col-md-3 text-center">Status</th>
                <th class="col-md-3 text-center">Test Type</th>
				<th class="col-md-2"></th>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach($tests as $test){
				$is_active = ($test['TestGroup']['is_active'] == 1) ? '<i class="fa fa-check-circle fa-lg text-success"></i>' : '<i class="fa fa-times-circle fa-lg text-danger"></i>' ;
                ?>
                <tr>
                    <td>
                        <?php
						echo $this->Html->link( $test['TestGroup']['name'],
							array('controller'=>'TestGroups', 'action'=>'index', $test['TestGroup']['id'])
						);
						?>
                    </td>
                    <td style="text-align:center">
                        <div class="wrap" id="<?=$test['TestGroup']['id']?>" field="is_active">
                            <span id="is_active<?=$test['TestGroup']['id']?>" class="is_active editable editable-click" style="display: inline;" data-type="select" data-pk="2" data-value="<?php echo $test['TestGroup']['is_active']; ?>" data-title="Status">
                                <?php echo $settings['options'][$test['TestGroup']['is_active']]; ?>
                            </span>
                        </div>
                    </td>

                    <td style="text-align:center">
                        <div class="wrap" id="<?=$test['TestGroup']['id']?>" field="schedule_type">
                            <span id="schedule_type<?=$test['TestGroup']['id']?>" class="schedule_type editable editable-click" style="display: inline;" data-type="select" data-pk="2" data-value="<?php echo $test['TestGroup']['schedule_type']; ?>" data-title="Schedule Type">
                                <?php echo $settings['scheduleType'][$test['TestGroup']['schedule_type']]; ?>
                            </span>
                        </div>
                    </td>
					<td style="text-align:center">
						<?php
						if(AuthComponent::user('Role.permission_level') >= 70){
							echo $this->Html->link(
								'<i class="fa fa-trash fa-lg"></i>',
								array('controller'=>'TestGroups', 'action'=>'delete', $test['TestGroup']['id']),
								array('escape'=>false)
							);
						}
						?>
					</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    jQuery(document).ready( function($) {
        $('.name').editable({
            type: 'text',
            name: 'name'
        });

        $('.credits').editable({
            source: [
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
                {value: '2', text: 'Inactive'},
            ],

        });

        $('.schedule_type').editable({
            source: <?php echo json_encode($settings['scheduleType']); ?>,
        });

        var edit_name_url = '<?php echo Router::url( array('controller'=>'tests', 'action'=>'inline_edit' ));?>';

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