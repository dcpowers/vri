<?php
    #pr($improvements);
    #exit; 
?>
<div class="improvement index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Suggestions Manager</h6>
            <h3 class="dashhead-title"><i class="fa fa-thumbs-up fa-fw"></i> All Suggestions</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
        </div>
    </div>
    <?php
    $nextPriority = count($improvements['accepted']) + 1;
    
    foreach($improvements as $title=>$improvementType){
        ?>
        <div class="hr-divider">
            <h3 class="hr-divider-content hr-divider-heading">
                <?=$title?>
            </h3>
        </div>
        <table class="table table-striped" id="accountsTable">
            <thead>
                <tr class="tr-heading">
                    <th class="col-md-6">Idea</th>
                    <th class="col-md-1">Accepted Date</th>
                    <th class="col-md-1">Completed Date</th>
                    <th class="col-md-1">Created By</th>
                    <th class="col-md-1">Created Date</th>
                    <th class="col-md-1">Priority</th>
                    <th class="col-md-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentCount = count($improvementType);
                foreach($improvementType as $data){
                    #pr($data);
                    #exit;
                    $buttons = array();
                    
                    $created = (empty($data['Improvement']['created_date'])) ? null : date('M d, Y', strtotime($data['Improvement']['created_date']));
                    $createdBy = (empty($data['CreatedBy']['id'])) ? null : $data['CreatedBy']['first_name'].' '. $data['CreatedBy']['last_name'];
                    
                    $approved = (empty($data['Improvement']['accepted_date'])) ? null : date('M d, Y', strtotime($data['Improvement']['accepted_date']));
                    $showApproveButton = (empty($data['Improvement']['accepted_date'])) ? true : false;
                    
                    $completed = (empty($data['Improvement']['completed_date'])) ? null : date('M d, Y',strtotime($data['Improvement']['completed_date']));
                    $showCompleteButton = (empty($data['Improvement']['completed_date']) && !empty($data['Improvement']['accepted_date'])) ? true : false;
                    
                    $priority = (empty($data['Improvement']['completed_date']) && !empty($data['Improvement']['accepted_date'])) ? true : false;
                    
                    if($showApproveButton == true && $data['Improvement']['is_active'] != 2){
                        $buttons[] = $this->Html->link(
                            '<i class="fa fa-thumbs-up fa-fw"></i>',
                            array('controller'=>'Improvements', 'action'=>'accept', $data['Improvement']['id'], $nextPriority),
                            array('escape'=>false, 'class'=>'btn btn-primary btn-xs')
                        );
                        
                        $buttons[] = $this->Html->link(
                            '<i class="fa fa-thumbs-down fa-fw"></i>',
                            array('controller'=>'Improvements', 'action'=>'reject', $data['Improvement']['id']),
                            array('escape'=>false, 'class'=>'btn btn-warning btn-xs')
                        );
                        
                    }
                    
                    if($showCompleteButton == true && $data['Improvement']['is_active'] != 2){
                        $buttons[] = $this->Html->link(
                            '<i class="fa fa-thumbs-up fa-fw"></i>',
                            array('controller'=>'Improvements', 'action'=>'completed', $data['Improvement']['id']),
                            array('escape'=>false, 'class'=>'btn btn-primary btn-xs')
                        );
                    }
                    
                    $buttons[] = $this->Html->link(
                        '<i class="fa fa-trash fa-fw"></i>',
                        array('controller'=>'Improvements', 'action'=>'delete', $data['Improvement']['id']),
                        array('escape'=>false, 'class'=>'btn btn-danger btn-xs'),
                        'Are You Sure You Want To Delete This?'
                    );
                    ?>
                    <tr>
                        <td><?=$data['Improvement']['idea']?></td>
                        <td><?=$approved?></td>
                        <td><?=$completed?></td>
                        <td><?=$createdBy?></td>
                        <td><?=$created?></td>
                        <td>
                            <?php
                            if($priority == true){
                                echo $data['Improvement']['priority'];
                            }
                            ?>
                        </td>
                        <td>
                            <ul class="list-inline pull-right">
                                <?php
                                foreach($buttons as $link){
                                    ?>
                                    <li><?=$link?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <?php
                    $nextPriority++;
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModalBig").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $(".modal-wide").on("show.bs.modal", function() {
          var height = $(window).height() - 200;
          $(this).find(".modal-body").css("max-height", height);
        });
     });
</script>