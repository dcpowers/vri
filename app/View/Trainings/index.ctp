<?php
    #pr($trainings);
    #exit; 
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Trainings</h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> All Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <ul class="pagination pagination-sm">
                <li>
                    <?php
                    echo $this->Html->link(
                        'View By File Type',
                        array('controller'=>'Trainings', 'action'=>'index', 'fileType'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li>
                    <?php
                    echo $this->Html->link(
                        'View Required',
                        array('controller'=>'Trainings', 'action'=>'index', 'required'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li>
                    <?php
                    echo $this->Html->link(
                        'View By Account',
                        array('controller'=>'Trainings', 'action'=>'index', 'account'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li>
                    <?php
                    echo $this->Html->link(
                        'View By Departments',
                        array('controller'=>'Trainings', 'action'=>'index', 'department'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
            </ul>
        </div>
    </div>

    <table class="table table-striped" id="accountsTable">
        <thead>
            <tr class="tr-heading">
                <th class="col-md-3">
                    <?php echo $this->Paginator->sort('name', 'Name');?>  
                    <?php if ($this->Paginator->sortKey() == 'name'): ?>
                        <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                
                <th class="col-md-5">Description</th>
                
                <th class="col-md-1 text-center">
                    <?php echo $this->Paginator->sort('is_active', 'Status');?>  
                    <?php if ($this->Paginator->sortKey() == 'is_active'): ?>
                        <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                
                <th class="col-md-3 text-center">Files</th>
            </tr>
        </thead>
        
        <tbody>
            <?php
            #pr($trainings);
            foreach($trainings as $trn){
                $video = (!empty($trn['Training']['video'])) ? '<i class="fa fa-file-video-o fa-stack-1x"></i>' : '<i class="fa fa-file-video-o fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i>' ;
                $power_point = (!empty($trn['Training']['power_point'])) ? '<i class="fa fa-file-powerpoint-o fa-stack-1x"></i>' : '<i class="fa fa-file-powerpoint-o fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i>' ;
                $leader = (!empty($trn['Training']['leader_files'])) ? '<i class="fa fa-file-pdf-o fa-stack-1x"></i>' : '<i class="fa fa-file-pdf-o fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i>' ;
                
                $videoText = (!empty($trn['Training']['video'])) ? 'Has Video' : 'No Video' ;
                $powerText = (!empty($trn['Training']['power_point'])) ? 'Has Power Point' : 'No Power Point' ;
                $leaderText = (!empty($trn['Training']['leader_files'])) ? 'Has Leader Guide' : 'No Leader Guide' ;
                ?>
                <tr>
                    <td>
                        <?php 
                        echo $this->Html->link(
                            $trn['Training']['name'],
                            array('controller'=>'Trainings', 'action'=>'view', $trn['Training']['id']),
                            array('escape'=>false)
                        );
                        ?> 
                    </td>
                            
                    <td><?=$trn['Training']['description']?></td>
                            
                    <td class="text-center"><span class="<?=$trn['Status']['color']?>"><?=$trn['Status']['name']?></span></td>
                    
                    <td class="text-center">
                        <ul class="list-inline">
                            <li><span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="<?=$videoText?>"><?=$video?></span></li>
                            <li><span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="<?=$powerText?>"><?=$power_point?></span></li>
                            <li><span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="<?=$leaderText?>"><?=$leader?></span></li>
                        </ul>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php echo $this->element( 'paginate' );?>
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