<div class="container">
    <h2 class="title"><i class="fa fa-users"></i><span class="text">Job Seekers</span></h2>
    <hr class="solidOrange" />
    <div class="row pull-right">
        <?php 
        echo $this->Html->link( 
            'View All Job Seekers',
            array('controller'=>'jobseekers', 'action'=>'all', 'member'=>true),
            array('escape' => false, 'class'=>'btn btn-primary btn-sm') ); 
        ?> 
    </div>
    <h3>My Case Load</h3>
    <hr />
    <table class="table table-striped" id="information">
        <thead>
		    <tr>
                <th><?php echo $this->Paginator->sort('first_name', 'Firstname');?>  </th>
			    <th><?php echo $this->Paginator->sort('last_name', 'Lastname');?>  </th>
                <th><?php echo $this->Paginator->sort('username', 'Username');?>  </th>
                <th>Career Counselor</th>
                <th>Program Manager</th>
			    <th>&nbsp;</th>
		    </tr>
	    </thead>
	    <tbody>						
            <?php 
            foreach($users as $user){
                $cc_name=null;
                $pg_name=null;
                                        
                foreach($user['AssignedJobseekers'] as $group){
                    if($group['model'] == 'cc'){
                        $cc_name = $group['WfcUser']['first_name'].' '.$group['WfcUser']['last_name'];
                    }
                                                
                    if($group['model'] == 'pg'){
                        $pg_name = $group['WfcUser']['first_name'].' '.$group['WfcUser']['last_name'];
                    }
                }
                ?>
                <tr>
                    <td><?php echo $user['User']['first_name'];?></td>
			        <td><?php echo $user['User']['last_name'];?></td>
                    <td><?php echo $user['User']['username'];?></td>
                    <td><?=$cc_name?></td>
                    <td><?=$pg_name?></td>
                    <td class="text-center">   
			            <?php 
                        echo $this->Html->link( "View",   
                            array(
                                'controller'=>'jobseekers', 
                                'member'=>true, 
                                'action'=>'view', 
                                $user['User']['id']
                            ), 
                            array(
                                'escape'=>false,
                                'class'=>'label label-primary label-xs'
                            ) 
                        );
					    ?>
			        </td>
                </tr>
                <?php
                if(!empty($cc_name)){ unset($cc_name, $cc_id); }
                if(!empty($pg_name)){ unset($pg_name, $pg_id); }
            }
            ?>
        </tbody>
    </table>
    
    <div class="footer">
        <?php
        echo $this->Paginator->numbers(array(
            'before' => '<ul class="pagination pagination-lg">',
            'separator' => '',
            'currentClass' => 'active',
            'currentTag' => 'a',
            'tag' => 'li',
            'after' => '</ul>'
        ));
        ?>
        <div class="pull-right">
            <?php echo $this->Paginator->counter(
                'Page {:page} of {:pages}, showing {:current} records out of {:count} total, 
                starting on record {:start}, ending on {:end}' ); 
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->