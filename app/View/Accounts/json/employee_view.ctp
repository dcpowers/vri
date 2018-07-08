	<div class="flextable">
        <div class="flextable-item">
            <?php
			echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add New Employee',
                array('controller'=>'Users', 'action'=>'add', $this->request->data['Account']['id'] ),
                array('escape'=>false, 'class'=>'btn btn-success', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
            );
            ?>
        </div>
        
        <div class="flextable-item">
        	<ul class="pagination pagination-sm">
	            <li class="<?=$aStatusClass?>">
	                <?php
	                echo $this->Html->link(
	                    'Active',
	                    '#',
	                    array('escape'=>false, 'class'=>'statusType', 'id'=>1)
	                );
	                ?>
	            </li>
	            
	            <li class="<?=$iStatusClass?>">
	                <?php
	                echo $this->Html->link(
	                    'Inactive',
	                    '#',
	                    array('escape'=>false, 'class'=>'statusType', 'id'=>2)
	                );
	                ?>
	            </li>
	            
	            <li class="<?=$allStatusClass?>">
	                <?php
	                echo $this->Html->link(
	                    'View All',
	                    '#',
	                    array('escape'=>false, 'class'=>'statusType', 'id'=>'all')
	                );
	                ?>
	            </li>
	        </ul>
        </div>
        
        <div class="flextable-item">
        	<ul class="pagination pagination-sm">
	            <li class="<?=$deptClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Department',
                        '#',
                        array('escape'=>false, 'class'=>'type', 'id'=>'department')
                    );
                    ?>
                </li>
                
                <li class="<?=$roleClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Role',
                        '#',
                        array('escape'=>false, 'class'=>'type', 'id'=>'role')
                    );
                    ?>
                </li>
                
                <li class="<?=$superClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Supervisor',
                        '#',
                        array('escape'=>false, 'class'=>'type', 'id'=>'supervisor')
                    );
                    ?>
                </li>
	        </ul>
        </div>
    </div>
    
	<div class="alert alertdisplay" role="alert" style="display: none"></div>
	<?php
	foreach($users as $department=>$employee){
	    ?>
	    <div class="hr-divider">
	        <h3 class="hr-divider-content hr-divider-heading">
	            <?=$department?>
	        </h3>
	    </div>
	    <table class="table table-striped table-condensed table-hover">
	        <thead>
	            <tr>
	                <th class="col-sm-2">Name</th>
	                <th class="col-sm-2">Rest Password</th>
	                <th class="col-sm-2">Username</th>
	                <th class="col-sm-2">E-Mail</th>
	                <th class="col-sm-1">Role</th>
	                <th class="col-sm-2">Supervisor</th>
	                <th class="col-sm-1 text-center">Status</th>
	            </tr>
	        </thead>
	                
	        <tbody>
	            <?php
	            foreach($employee as $user){
	                ?>
	                <tr>
	                    <td>
	                        <?php
	                        echo $this->Html->link(
	                            $user['first_name'].' '.$user['last_name'],
	                            array('controller'=>'Users', 'action'=>'acctView', $user['id']),
	                            array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
	                        );
	                        ?>
	                    </td>
	                    <td>
	                    	<?php
	                        echo $this->Html->link(
	                            'Reset Password',
	                            '#',
	                            array('escape'=>false, 'id'=>$user['id'], 'class'=>'reset')
	                        );
	                        ?>
	                    </td>        
	                    <td><?=$user['username']?></td>
	                            
	                    <td><?=$user['email']?></td>
	                            
	                    <td><?=$user['Role']['name']?></td>
	                    <td>
	                        <?php
	                        if(array_key_exists('first_name', $user['Supervisor'])){
	                            echo $user['Supervisor']['first_name'].' '.$user['Supervisor']['last_name'];
	                        }else{
	                            echo '--';
	                        }
	                        ?>
	                    </td>
	                    <td class="text-center">
	                        <span class="<?=$user['Status']['color']?> label-as-badge"><i class="fa <?=$user['Status']['icon']?>"></i></span>
	                    </td>
	                </tr>
	                <?php
	            }
	            ?>
	        </tbody>
	    </table>
	    <?php
	}
	?>

	<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Accounts', 'action' => 'employeeView', $account['Account']['id']));
    $reset = $this->Html->url(array('plugin'=>false, 'controller'=>'users', 'action' => 'resetPassword'));
	?>
	<script type="text/javascript">
	    jQuery(window).ready( function($) {
	        $(".chzn-select").chosen({
	            allow_single_deselect: true
	        });
	        
	        $('.statusType').on('click', function () {
	            $.ajax({
	                type: 'POST',
	                url:'<?=$url?>/' + $(this).attr("id") + '/<?=$viewBy?>' + '.json',
	                cache: false,
	                dataType: "html",
	                beforeSend: function(xhr) {
	                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	                    $('#LoadingDiv').show();
	                    $('#employeeList').empty();
	                },
	                complete: function(){
	                    $('#LoadingDiv').hide();
	                },
	                success: function(response) {
	                    $('#employeeList').html(response);
	                },
	                error: function (jqXHR, textStatus, errorThrown) {
	                    console.log(jqXHR);
	                    console.log(textStatus);
	                    console.log(errorThrown);
	                }
	                
	            });
	            
	            return false;
	            
	        });
	        
	        $('.type').on('click', function () {
	            $.ajax({
	                type: 'POST',
	                url:'<?=$url?>/' + '<?=$pageStatus?>/' + $(this).attr("id") + '.json',
	                cache: false,
	                dataType: "html",
	                beforeSend: function(xhr) {
	                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	                    $('#LoadingDiv').show();
	                    $('#employeeList').empty();
	                },
	                success: function(response) {
	                    $('#employeeList').html(response);
	                },
	                complete: function(){
	                    $('#LoadingDiv').hide();
	                },
	                error: function (jqXHR, textStatus, errorThrown) {
	                    console.log(jqXHR);
	                    console.log(textStatus);
	                    console.log(errorThrown);
	                }
	                
	            });
	            
	            return false;
	            
	        });
	        
	        $('.reset').click(function(e){
	        	
	            $.ajax({
	                type : 'POST',
	                url : '<?=$reset?>/' + $(this).attr("id") + '.json',
	                cache: false,
	                dataType: "html",
	                beforeSend: function(xhr){
	                    
	                },
	                success : function(response) {
	                	var response1=jQuery.parseJSON(response);
	                	if(response1.status=='success'){
	                		$('.alertdisplay').addClass('alert-success');
	                		$('.alertdisplay').show();
	                	} else {
	                		$('.alertdisplay').addClass('alert-danger');
							$('.alertdisplay').show();
						}
						
						$(".alertdisplay").html(response1.message);
	            	},
	                complete: function(){
	                    
	                },
	                error: function (jqXHR, textStatus, errorThrown) {
	                    console.log(jqXHR);
	                    console.log(textStatus);
	                    console.log(errorThrown);
	                }
	            });
	            
	        });
	    });
	</script>