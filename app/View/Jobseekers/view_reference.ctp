<?php 
    #$this->Html->css('jquery.raty.css', '', array('block' => 'csslib') );
    #$this->Html->script('jquery.raty.min', array('block' => 'scriptsBottom'));
?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$item['User']['fullname']?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="list-group">
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Reference Name:</label>
                                <p class="form-control-static"><?=$item['UserReference']['name']?></p>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="relation">Relation:</label>
                                <p class="form-control-static"><?=$item['UserReference']['relation']?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="phone">Phone:</label>
                                <p class="form-control-static"><?=$item['UserReference']['phone']?></p>
                            </div>
                        </div>
                        
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="email">E-Mail</label>
                                <p class="form-control-static"><?=$item['UserReference']['email']?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <?php
                for($i=0; $i<=3; $i++){
                    ?>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label class="control-label" for="email"><?=$item['ReferenceResponce'][$i]['question']?></label>
                            <div class="rating" data-score="<?=$item['ReferenceResponce'][$i]['answer']?>"></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <?php
                for($i=4; $i<=7; $i++){
                    ?>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label class="control-label" for="email"><?=$item['ReferenceResponce'][$i]['question']?></label>
                            <div class="rating" data-score="<?=$item['ReferenceResponce'][$i]['answer']?>"></div>
                        </div>
                    </div>
                    <?php
                }
            ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="email"><?=$item['ReferenceResponce'][8]['question']?></label>
                <p class="form-control-static"><?=$item['ReferenceResponce'][8]['answer']?></p>
            </div>
        </div>
    </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">
            <?php
            echo $this->Form->button(
                '<i class="fa fa-times"></i><span class="text">Close</span>', 
                array('class'=>'btn btn-default', 'data-dismiss'=>'modal')
            ); 
            ?>  
        </div>
    </div>
</div>

<script language="JavaScript">
    jQuery(window).ready( function($) {
        $('div[class^="rating"]').each(function(){
            $(this).raty({
                score: function() {
                    return $(this).attr('data-score');
                },
                'readOnly': true,
                'half': true,
                'halfShow' : true,
                'path': '/img/stars/',
            });
        });
    });
</script>    