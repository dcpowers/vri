<?php
    
    #$role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
    #$user = AuthComponent::user();
    #echo '<h1>Company</h1>';
    #pr($data);
    #pr($departmentList);
    #pr($locationList);
    #exit;
    //pr($role_names);   
?>

<div class="container">
    <div class="row">
        <?php echo $this->Html->link('Back To List', array('controller'=>'Groups', 'action'=>'index', 'member'=>true)); ?>
        <h1>
            <?php echo $data[0]['Group']['name']; ?>
            <small>Supervisor: 
                <?php echo $data[0]['Supervisor']['sur_name']; ?>
                <?php echo $data[0]['Supervisor']['first_name']; ?>
                <?php echo $data[0]['Supervisor']['last_name']; ?>
            </small>
        </h1>
        <?php 
        echo $this->Form->create(null, array(
            'url' => array('controller' => 'GroupMemberships', 'action' => 'edit', $data[0]['Group']['id'])
        ));
        ?>
        <ul class="list-group">
            <?php
                //Unassigned Members
                foreach($data[0]['Members'] as $umember){
                    $ufullName = $umember['sur_name'].' '.$umember['first_name'].' '.$umember['last_name'];
                    ?>
                    <li class="list-group-item"><?php echo $ufullName; ?>
                        [ ID:<?php echo $umember['id']; ?> ]
                        [ C:<?php echo $umember['company_id']; ?> ]
                        [ L:<?php echo $umember['location_id']; ?> ]
                        [ D:<?php echo $umember['department_id']; ?> ]
                         
                        <?php
                        if($data[0]['Supervisor']['id'] != $umember['id']){
                            echo $this->Form->input( $umember['id'], array(
                                'options'=>$departmentList,
                                'label'=>false, 
                                'div'=>false, 
                                'class'=>'chzn-select', 
                                'empty' => ' ',
                                'data-placeholder' => 'Select A Department'
                            ));
                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
            
        </ul>
        <?php echo $this->Form->end(__('Save')); ?>
        <ul class="list-group">
            <?php
            //Unassigned Members
            #pr($data);
            #exit;
            
            foreach($data[0]['children'] as $child){
                ?>
                <li class="list-group-item">
                    <?php echo $child['Group']['name']; ?>
                    [ <?php echo $child['Group']['id']; ?> ]
                    <?php
                    if(!empty($child['children'])){
                        ?>
                        <ul class="list-group">
                            <?php
                            foreach($child['children'] as $dept){
                                ?>
                                <li class="list-group-item">
                                    <?php echo $dept['Group']['name']; ?>
                                    [ <?php echo $dept['Group']['id']; ?> ]
                                    
                                    <?php
                                    if(!empty($dept['Members'])){
                                        ?>
                                        <ul class="list-group">
                                            <?php
                                            foreach($dept['Members'] as $member){
                                                $fullName = $member['sur_name'].' '.$member['first_name'].' '.$member['last_name'];
                                                ?>
                                                <li class="list-group-item"><?php echo $fullName; ?>
                                                    [ ID:<?php echo $member['id']; ?> ]
                                                    [ C:<?php echo $member['company_id']; ?> ]
                                                    [ L:<?php echo $member['location_id']; ?> ]
                                                    [ D:<?php echo $member['department_id']; ?> ]
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
            
            #$editurl = $this->Html->link('Edit', array('action'=>'edit', $key));
            #$upurl = $this->Html->link('Up', array('action'=>'moveup', $key, '1'));
            #$downurl = $this->Html->link('Down', array('action'=>'movedown', $key, '1'));
            #$deleteurl = $this->Html->link('Delete', array('action'=>'delete', $key));
            #echo " [ $editurl | $upurl | $downurl | $deleteurl ] $value <br />";
        
            
        ?>
    </div>
</div>
<script language="JavaScript">
    
        $(".chzn-select").chosen({
            width: "90%"
        });
        
</script>