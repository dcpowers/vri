<?php
    #pr($wfc);
    #exit;
    //pr($role_names);
    $this->Html->script('plugins/select2/select2.min.js', array('block' => 'scriptsBottom'));
    $this->Html->addCrumb('Groups', array('controller' => 'groups', 'action' => 'index', 'admin'=>true));   
?>

<div class="container">
    <div class="row">
        <h1>Groups</h1>
        <ul id="myTab" class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#home" role="tab" data-toggle="tab">Companies</a></li>
            <li><a href="#wfc" role="tab" data-toggle="tab">Workforce Regions</a></li>
        </ul>
        
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <?php 
                echo $this->Form->create('Group', array('action'=>'add','class'=>'form-horizontal'));
                echo $this->Form->hidden('parent_id', array('value'=>2));
                ?>
                <div class="col-xs-2 col-sm-3">
                    <?php
                    echo $this->Form->input( 'name', array(
                        'label'=>false, 
                        'div'=>false,
                        'value' => '',
                        'class'=>'form-control',
                        'placeholder' => 'Create New Company'
                    ));
                    ?>
                </div>
                <div class="col-sm-1">
                    <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                    <?php echo $this->Form->end();?>
                </div>
                <div class="col-sm-3">
                    <?php
                    echo $this->Form->input( 'company_id', array(
                        'options'=>$companyList,
                        'label'=>false, 
                        'div'=>false, 
                        'class'=>'select2 select2-arrow', 
                        'empty' => ' ',
                        'multiple'=>false
                    ));
                    ?>
                </div>
                <div class="clearfix"></div>
                
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <?php
                        echo $this->Html->tableHeaders(array(
                            array('Name' => array('class' => 'sortable')),
                            'Delete'
                        ));
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($companyList as $id=>$value){
                            $editurl = $this->Html->link($value, array('action'=>'byGroup', $id));
                            $deleteurl = $this->Html->link('Delete', array('action'=>'delete', $id));
                            ?>
                            <tr>
                                <td><?=$editurl?></td>
                                <td><?=$deleteurl?></td>
                            </tr>
                            <?php
                            
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade in" id="wfc">
                <?php 
                echo $this->Form->create('Group', array('action'=>'add','class'=>'form-horizontal'));
                echo $this->Form->hidden('parent_id', array('value'=>1));
                ?>
                <div class="col-xs-2 col-sm-3">
                    <?php
                    echo $this->Form->input( 'name', array(
                        'label'=>false, 
                        'div'=>false,
                        'value' => '',
                        'class'=>'input-medium form-control'
                    ));
                    ?>
                    
                </div>
                <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                <?php echo $this->Form->end();?>
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <?php
                        echo $this->Html->tableHeaders(array(
                            array('Name' => array('class' => 'sortable')),
                            'Delete'
                        ));
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        foreach($wfcList as $id=>$value){
                            $editurl = $this->Html->link($value, array('action'=>'byGroup', $id));
                            $deleteurl = $this->Html->link('Delete', array('action'=>'delete', $id));
                            
                            ?>
                            <tr>
                                <td><?=$editurl?></td>
                                <td><?=$deleteurl?></td>
                            </tr>
                            <?php
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal stuff is here -->

<div class="modal fade" id="AjaxModal" tabindex="-1" role="dialog" aria-labelledby="addGroups" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(window).ready( function($) {
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
    
    $('#company_id').on('change', function( ) {
        var department_id = $(this).val();
        var url = '<?php echo Router::url( array('controller'=>'groups', 'action'=>'byGroup' ) ); ?>/';
        location.href = url + department_id ;
    });
    
    $( "#company_id" ).select2( { placeholder: "Search for a Company" } );
    
    
});
</script>