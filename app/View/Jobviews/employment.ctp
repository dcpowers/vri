<?php
    #pr($postings[0]);
    #unset($postings[0]['JobQuestion']);
    #exit;
?>
<div class="container">
    <div class="row">                
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget widget-no-header">
                        <div class="widget-content text-center">
                            <?php
                            $name = '../groupFiles/'.$postings[0]['Job']['Group']['pin_number'].'/'.$postings[0]['Job']['Group']['logo'];
                            $image = (!empty($postings[0]['Job']['Group']['logo'])) ? $name : 'noImage.jpg' ;
                    
                            echo $this->Html->image(
                                $image,
                                array('class'=>'avatar', 'style'=>'width: 150px; height: 159px')
                            );
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="widget widget-no-header">
                        <div class="widget-content">
                            <h2>Job Details</h2>
                            <div class="row">
                                <?php
                                if($postings[0]['JobOffer']['status'] == 'New'){
                                    ?>
                                    <div class="col-xs-6">
                                        <?php
                                        echo $this->Html->link(
                                            'Accept Offer',
                                            array('controller'=>'jobviews', 'action'=>'acceptEmployment', $id, $postings[0]['JobOffer']['id'],'member'=>true),
                                            array('escape'=>false, 'class'=>'btn btn-success btn-block')
                                        );
                                        ?>
                                    </div>                                
                                    
                                    <div class="col-xs-6">
                                        <?php
                                        
                                        echo $this->Html->link(
                                            'Decline Offer',
                                            array('controller'=>'jobviews', 'action'=>'declineEmployment', $id, $postings[0]['JobOffer']['id'], 'member'=>true),
                                            array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-warning btn-block')
                                        );
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <hr />
                            <b>Location:</b><br />
                            <?=$postings[0]['Job']['Group']['city']?>, <?=$postings[0]['Job']['Group']['state']?><br />
                            <b>Posted:</b><br />
                            <?php echo date( APP_DATE_FORMAT,strtotime($postings[0]['JobPosting']['created'])); ?><br />
                            <b>Salary:</b><br />
                            <?=$postings[0]['Job']['salary_range']?><br />
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="widget widget-no-header">
                        <div class="widget-content">
                            <h2>About <?=$postings[0]['Job']['Group']['name']?> </h2>
                            <h3><?=$postings[0]['Job']['Group']['welcome_title']?></h3>
                            <?=$postings[0]['Job']['Group']['welcome_notes']?>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="widget widget-no-header">
                <div class="widget-content">
                    <h2><?=$postings[0]['Job']['name']?></h2>
                    <hr />
                    <?php echo nl2br($postings[0]['Job']['description']); ?>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal bootstrap-dialog type-primary fade size-normal in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
    });
</script> 