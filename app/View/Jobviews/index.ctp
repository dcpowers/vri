<?php
    #pr($postings);
?>
<div class="container">
    <h1>Search Jobs</h1>
    <div class="widget widget-no-header">
        <div class="widget-content">
            <div class="row">                
                <div class="col-md-8">
                    <?php
                    foreach($postings as $job){
                        #pr($job);
                        $posted_date = date( APP_DATE_FORMAT,strtotime($job['JobPosting']['created']));
                        ?>
                        <div class="row">
                            <div class="col-md-7">
                                <h2>
                                    <?php
                                    echo $this->Html->link( 
                                        $job['Job']['name'],
                                        array( 
                                            'controller'=>'jobviews',
                                            'action'=>'view', 
                                            'member'=>false,
                                            $job['JobPosting']['id'],
                                            
                                        ), 
                                        array('escape'=>false, 'class'=>'btn-switch-activate') 
                                    );
                                    ?>
                                </h2>
                                <h5>
                                    <?php
                                    echo $this->Html->link( 
                                        $job['Job']['Group']['name'],
                                        array( 
                                            'controller'=>'jobviews',
                                            'action'=>'company', 
                                            'member'=>false,
                                            $job['Job']['Group']['id']
                                        ), 
                                        array('escape'=>false, 'class'=>'btn-switch-activate') 
                                    );
                                    ?>
                                </h5>
                                <?=$posted_date?>
                            </div>
                            
                            <div class="col-md-5">
                                <?=$job['Job']['Group']['city']?>, <?=$job['Job']['Group']['state']?>
                            </div>
                        </div>
                        <hr />
                        <?php
                    }
                    ?>
                        
                </div>
                
                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
    </div>
    
</div>