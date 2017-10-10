<?php
    $addClassPu  = NULL;
    $addTextPu  = NULL;
        
    $addClassPcu = NULL; 
    $addTextPcu = NULL;
    
    if(isset($errors)){
        if(isset($errors['password_update'])) {
            $addClassPu = 'has-error';
            $addTextPu = $errors['password_update'][0];
        }
                
        if(isset($errors['password_confirm_update'])) {
            $addClassPcu = 'has-error';
            $addTextPcu = $errors['password_confirm_update'][0];
        }
    }
?>
<div class="container">
    <div class="row">
        <div class="users form">
            <?php echo $this->Form->create('User', array(
                'class' => 'form-horizontal', 
                'role' => 'form', 
                'style'=>'padding: 15px 1px;',
                'url' => array('controller' => 'users', 'action' => 'reset')
            )); ?>
            
            <?php echo $this->Form->hidden('token', array('value'=> $token)); ?>
            
            <div class="form-group <?=$addClassPu?> ">
                <label for="password_update" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <?php echo $this->Form->input('password', array (
                        'name' => 'data[User][password_update]',
                        'class' => 'form-control',
                        'type' => 'password',
                        'placeholder' => 'Password',
                        'label' => false
                    ));?>
                </div>
                <span class="help-block col-sm-offset-2 col-sm-10" for="lastname"><?=$addTextPu?></span>
            </div>
                    
            <div class="form-group <?=$addClassPcu?>">
                <label class="col-sm-2 control-label" for="password_confirm_update">Confirm Password</label>
                <div class="col-sm-10">
                    <?php echo $this->Form->input('password_confirm', array (
                        'name' => 'data[User][password_confirm_update]',
                        'class' => 'form-control',
                        'type' => 'password',
                        'placeholder' => 'Password Again',
                        'label' => false
                    ));?>
                </div>
                <span class="help-block col-sm-offset-2 col-sm-10" for="lastname"><?=$addTextPcu?></span>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default btn-primary">Save</button>
                </div>
            </div>
            
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>