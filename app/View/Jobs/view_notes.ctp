<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <pre><?=$info[0]['ApplyJob']['notes']?></pre>
        </div>
    </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
        </div>
    </div>
</div>