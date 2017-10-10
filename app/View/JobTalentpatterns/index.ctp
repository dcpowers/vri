<?php
    
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/tag' );
    
    $jobSearchList = array();
    if(!empty($jobPosting[0]['JobPosting'])){
        foreach($jobPosting as $item){
            $jobSearchList[$item['JobPosting']['id']] = $item['Job']['name'];
        }
    }
    
?>

<div class="container">
    <h2 class="title clearfix">Talent Patterns</h2>
    <hr class="solidOrange" />
    <div class="btn-group">
        <?php
        echo $this->Html->link(
            '<i class="fa fa-plus"></i> Create Talent Pattern',
            array('controller'=>'jobTalentpatterns', 'action'=>'add', 'member'=>true),
            array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
        );
        ?>
    </div>
    
    <table class="table table-hover table-condensed" style="margin-top: 20px;">
        <thead>
            <tr class="tr-heading">
                <th>Name</th>
                <th>Realistic</th>
                <th>Investigative</th>
                <th>Conventional</th>
                <th>Social</th>
                <th>Enterprising</th>
                <th>Artistic</th>
                <th>Competitor</th>
                <th>Communicator</th>
                <th>Cooperator</th>
                <th>Coordinator</th>
                <th></th>
            </tr>
        </thead>
        
        <tbody>
            <?php 
            foreach($jobTalentpattern as $key => $item){
                ?>
                <tr>
                    <td><?=$item['JobTalentpattern']['name']?></td>
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="realistic">
                            <!--<a href="#" class="jobTalentPattern" data-placeholder="Required" data-type="text" data-pk="<?=$item['JobTalentpattern']['id']?>" data-title="Talent Pattern"><?=$item['JobTalentpattern']['realistic']?>-->
                            <?=$item['JobTalentpattern']['realistic']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="investigative">
                            <?=$item['JobTalentpattern']['investigative']?>
                        </div>
                    </td>
                                        
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="conventional">
                            <?=$item['JobTalentpattern']['conventional']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="social">
                            <?=$item['JobTalentpattern']['social']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="enterprising">
                            <?=$item['JobTalentpattern']['enterprising']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="artistic">
                            <?=$item['JobTalentpattern']['artistic']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="competitor">
                            <?=$item['JobTalentpattern']['competitor']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="communicator">
                            <?=$item['JobTalentpattern']['communicator']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="cooperator">
                            <?=$item['JobTalentpattern']['cooperator']?>
                        </div>
                    </td>
                    
                    <td>
                        <div class="wrap" id="<?=$item['JobTalentpattern']['id']?>" field="coordinator">
                            <?=$item['JobTalentpattern']['coordinator']?>
                        </div>
                    </td>
                    
                    <td>
                        <ul class="list-inline">
                            <li>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-trash"></i>', 
                                    array('controller'=>'jobTalentpatterns', 'action'=>'confirm', $item['JobTalentpattern']['id'], 'member'=>true),
                                    array(
                                        'escape'=>false,
                                        'id'=>$key,
                                        'data-toggle'=>'tooltip',
                                        'data-placement'=>'top',
                                        'title'=>'Delete: '.$item['JobTalentpattern']['name'],
                                        'data-toggle'=>'modal', 
                                        'data-target'=>'#myModal',
                                    )
                                );
                                ?>
                            </li>
                        </ul>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModal2").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        /*
        var edit_name_url = '<?php echo Router::url( array('controller'=>'JobTalentpatterns', 'action'=>'inline_edit', 'member'=>true ));?>';
        
        $('.jobTalentPattern').editable({
            disabled: false,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $(document).on('click','.editable-submit',function(){
            var id = $(this).closest('.wrap').attr('id');
            var value = $('.input-sm').val();
            var field = $(this).closest('.wrap').attr('field');
            
            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: { JobTalentPattern: { id: id, field: field, value: value }},
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
        */
    });
</script>            