<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h2>Add New</h2>
</div>

<div class="modal-body">
    <?php 
    echo $this->Form->create('Test', array(
        'url' => array('controller'=>'tests', 'action'=>'addSub', 'admin'=>true), 
        'role'=>'form',
        //'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
    
    
    echo $this->Form->input('test_id', array (
        'type'=>'hidden',
        'id'=>'test_id',
        'value'=>$test_id
    ));
    echo $this->Form->input('category_type', array (
            'type'=>'hidden',
            'id'=>'category_type',
            'value'=>$category_type
        ));
    ?>
    
    <?php
    if($category_type != 4){
        echo $this->Form->input('parent_id', array (
            'type'=>'hidden',
            'id'=>'parent_id',
            'value'=>$parent_id
        ));
        echo $this->Form->input('category_type', array (
            'type'=>'hidden',
            'id'=>'category_type',
            'value'=>$category_type
        ));
        ?>
        <div class="form-group">
            <label class="control-label" for="name">Title/Name:</label>
            <?php 
            echo $this->Form->input('name', array (
                'type'=>'text',
                'placeholder' => 'Name',
                'id'=>'name',
                'class'=>'form-control'
                ));
            ?>
        </div>
        <?php
    }
    ?>
    
    <?php
    if($category_type == 2){
        ?>
        <div class="form-group">
            <label class="control-label" for="name">Description:</label>
            <?php 
            echo $this->Form->input('description', array (
                'type'=>'textarea',
                'placeholder' => 'Description',
                'id'=>'description',
                'class'=>'form-control'
            ));
            ?>
        </div>
                    
        <div class="form-group">
            <label class="control-label" for="name">Introduction:</label>
            <?php 
            echo $this->Form->input('introduction', array (
                'type'=>'textarea',
                'placeholder' => 'Introduction',
                'id'=>'introduction',
                'class'=>'form-control'
            ));
            ?>
        </div>
        <?php
    }
    
    if($category_type == 4){
        for($i=0;$i<=4;$i++){
            echo $this->Form->input($i.'.Test.parent_id', array (
                'type'=>'hidden',
                'id'=>'parent_id',
                'value'=>$parent_id
            ));
            echo $this->Form->input($i.'.Test.category_type', array (
                'type'=>'hidden',
                'id'=>'category_type',
                'value'=>$category_type
            ));
            ?>
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="control-label" for="name">Answer:</label>
                        <?php 
                        echo $this->Form->input($i.'.Test.name', array (
                            'type'=>'text',
                            'placeholder' => 'Answer',
                            'id'=>'name',
                            'class'=>'form-control'
                        ));
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="name">Answer:</label>
                        <?php 
                        echo $this->Form->input($i.'.Test.description', array (
                            'type'=>'text',
                            'placeholder' => 'Answer Value',
                            'id'=>'answerValue',
                            'class'=>'form-control'
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    
    ?>
    
    
            
    <div class="submit">
        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        <?php echo $this->Html->link( __('Cancel'), '#', array('class'=>'btn', 'data-dismiss'=>'modal')  ); ?>
    </div>
    <?php echo $this->Form->end(); ?>
    
</div>
            
<div class="modal-footer">
                            
</div>

<script language="JavaScript">
    jQuery(document).ready( function($) {
        
    });
</script>
