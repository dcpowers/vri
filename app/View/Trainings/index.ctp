<?php
    #pr($trainings);
    #exit; 
?>
<div class="training index">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Account Training:</h6>
            <h3 class="dashhead-title"><i class="fa fa-book fa-fw"></i> Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Trainings/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Trainings/menu' );?>                
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php #echo $this->element( 'Trainings/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>
    
    <table class="table table-striped table-condensed table-hover" id="trainingTable">
        <thead>
            <tr>
                <th class="col-sm-4">Name</th>
                <th class="col-sm-4">Description</th>
                <th class="col-sm-2 text-center">Required</th>
                <th class="col-sm-2 text-center">Mandatory</th>
            </tr>
        </thead>
                                                        
        <tbody>
            <?php
            #pr($trainings);
            #exit;
            $c=0;
            foreach($trainings as $key=>$trn){
                $required = ($trn['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                $manditory = ($trn['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                ?>
                <tr>
                    <td><?=$trn['Training']['name']?></td>
                    <td><?=$trn['Training']['description']?></td>
                    <td class="text-center"><?=$required?></td>
                    <td class="text-center"><?=$manditory?></td>
                </tr>
                <?php
            }
            ?>                
        </tbody>
    </table>                                        
                                              
    <?php echo $this->element( 'paginate' );?>
</div>

<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>

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
        
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
        $(".chzn-select-noDeselect").chosen({
            allow_single_deselect: false
        });
     });
</script>