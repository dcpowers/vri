<?php
    //set breadcrumb trail Use this as an example
    #$this->Html->addCrumb('Users', array('controller' => 'users', 'action' => 'add', 'member'=>true));
    #pr($assignedTest);
    $this->Html->css('bootstrap-fileupload.min.css', '', array('block' => 'csslib') );
    $this->Html->script('bootstrap-fileupload.js', array('block' => 'scriptsBottom'));
    
    #pr($validationErrors);

?>
<style type="text/css">
.label-as-badge {
    border-radius: 5em;
}
</style>
<div class="container">
    <h1>Welcome<small> <?php echo ucfirst ( strtolower( AuthComponent::user('first_name') ) ) . ' ' . ucfirst ( strtolower( AuthComponent::user('last_name') ));?>!</small></h1>
    <div class="content">
        <?php echo $this->Form->create('User', array(
            'type' => 'file',
            'url' => array('member'=>true, 'controller'=>'Users', 'action'=>'edit'), 
            'role'=>'form',
            'class'=>'form-horizontal',
            'inputDefaults' => array(
                'label' => false,
                'div' => false,
                'class'=>'form-control',
                'error'=>false
            )
        )); 
        ?>
        <div class="widget widget-no-header">
            <div class="widget-content">
                <h2>Profile Information</h2>
                <hr />
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <?php
                                    $name = '../'.AuthComponent::user('DetailUser.uploadDir').'/'.$content[0]['DetailUser']['img'];
                                    $image = (!empty($name)) ? $name : 'noImage.png' ;
                                    ?>
                                    <?php 
                                    echo $this->Html->image($image);
                                    ?>
                                    
                                </div>
                            
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                
                                <div>
                                    <span class="btn btn-file">
                                        <span class="text-danger"><small>( 2mb Limit )</small></span><br />
                                        <span class="fileupload-new">Select image</span>
                                        <span class="fileupload-exists">Change</span>
                                        <?php echo $this->Form->file('DetailUser.file'); ?>
                                    </span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-danger" for="phone">Firstname:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('first_name', array (
                                    'type' => 'text',
                                    'value' => $content[0]['User']['first_name'],
                                    'id'=>'first_name'
                                ));?>
                                <?php
                                $msg = (!empty($validationErrors['User']['first_name'])) ? $validationErrors['User']['first_name'][0] : null;
                                
                                if(!empty($msg)){
                                    ?>
                                    <small class="text-danger"><?=$msg?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-danger" for="phone">Lastname:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('last_name', array (
                                    'type' => 'text',
                                    'value' => $content[0]['User']['last_name'],
                                    'id'=>'last_name'
                                ));?>
                                
                                <?php
                                $msg = (!empty($validationErrors['User']['last_name'])) ? $validationErrors['User']['last_name'][0] : null;
                                
                                if(!empty($msg)){
                                    ?>
                                    <small class="text-danger"><?=$msg?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-danger" for="phone">E-Mail Address:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('username', array (
                                    'type' => 'email',
                                    'value' => $content[0]['User']['username'],
                                    'id'=>'email'
                                ));?>
                                <?php
                                $msg = (!empty($validationErrors['User']['username'])) ? $validationErrors['User']['username'][0] : null;
                                
                                if(!empty($msg)){
                                    ?>
                                    <small class="text-danger"><?=$msg?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="phone">Password:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('password_update', array (
                                    'type' => 'password',
                                    'id'=>'password_update'
                                ));?>
                                <small class="text-info">Leave Blank/Empty if you do not want to update!</small><br />
                                <?php
                                $msg = (!empty($validationErrors['User']['password_update'])) ? $validationErrors['User']['password_update'][0] : null;
                                
                                if(!empty($msg)){
                                    ?>
                                    <small class="text-danger"><?=$msg?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="phone">Password Repeat:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('password_confirm_update', array (
                                    'type' => 'password',
                                    'id'=>'password_confirm_update'
                                ));?>
                                <?php
                                $msg = (!empty($validationErrors['User']['password_confirm_update'])) ? $validationErrors['User']['password_confirm_update'][0] : null;
                                
                                if(!empty($msg)){
                                    ?>
                                    <small class="text-danger"><?=$msg?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="phone">Phone Number:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.phone', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['phone'],
                                    'id'=>'phone'
                                ));?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="address">Address:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.address', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['address']
                                ));?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="city">City:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.city', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['city']
                                    
                                ));?>
                            </div>
                        </div>
                        
                        <div class="form-group">                
                            <label class="col-sm-4 control-label" for="state">State:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.state', array (
                                    'type' => 'select',
                                    'options'=>$states,
                                    'selected'=>$content[0]['DetailUser']['state'],
                                    'empty' => ' ', 
                                    'placeholder'=>'Choose A State',
                                    
                                ));?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="zip">Zip:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.zip', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['zip']
                                    
                                ));?>
                            </div>
                        </div>
                   
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="county">County:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.county', array (
                                    'type' => 'text'
                                ));?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="mobile">Mobile Phone:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.mobile', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['mobile']
                                    
                                ));?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="fax">Fax:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.fax', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['fax']
                                    
                                ));?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-footer">
                <div class="widget-header-toolbar">
                    <input type="submit" class="btn btn-primary pull-right" value="Save">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php
        $group_ids = Set::extract( AuthComponent::user(), '/GroupMembership/group_id' );
        if( in_array(3,$group_ids) || in_array(4,$group_ids) ){
            ?>
            <div class="widget widget-no-header">
                <div class="widget-content">
                    <div class="col-md-4">
                        <h2>General Information</h2>
                        <hr />
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-5 text-danger">Job Search: </label>
                                    <div class="col-md-7">
                                        <?php 
                                        foreach($settings['job_status'] as $key=>$item){
                                            if($key >= 1){
                                                ?>
                                                <label class="control-inline fancy-radio">
                                                    <input type="radio" <?php if($content[0]['DetailUser']['job_status'] == $key){ echo 'checked'; } ?> name="data[DetailUser][job_status]" value="<?=$key?>">
                                                    <span><i></i><?=$item?></span>
                                                </label>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-5 text-danger">Willing to Relocate: </label>
                                    <div class="col-md-7">
                                        <?php 
                                        foreach($settings['relocate'] as $key=>$item){
                                            if($key >= 1){
                                                ?>
                                                <label class="control-inline fancy-radio">
                                                    <input type="radio" <?php if($content[0]['DetailUser']['is_relocate'] == $key){ echo 'checked'; } ?> name="data[DetailUser][is_relocate]" value="<?=$key?>">
                                                    <span><i></i><?=$item?></span>
                                                </label>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h2>SOC Information</h2>
                        <hr />
                        <p>Type in a key word to begin!</p>
                        <p>This is not working right now!!!! Leave the default value in there!!!</p>
                        <div class="input-group">
                            <?php echo $this->Form->input('soc', array (
                                'type' => 'text',
                                'id'=>'search',
                                'placeholder'=>'This is not working right now',
                                'value'=>'07-07-0111'
                            ));?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-search" type="button">Search</button>
                            </span>
                        </div>
                        <div id="searchResults"></div>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="widget-footer">
                    <div class="widget-header-toolbar">
                        <input type="submit" class="btn btn-primary pull-right" value="Save">
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="widget widget-no-header">
            <div class="widget-content">
                <h2>EEOC Information</h2>
                <hr />
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="col-md-4">Birthday: </label>
                            <div class="col-md-8">
                                <div class="row">
                                    <?php
                                    echo $this->Form->input('DetailUser.dob', array(
                                        'type'=>'date',
                                        'empty'=> array(
                                            'month'       => 'MONTH',
                                            'day'     => 'DAY',
                                            'year'      => 'YEAR'
                                        ),
                                        'dateFormat' => 'MDY',
                                        'minYear' => date('Y') - 70,
                                        'maxYear' => date('Y') - 15,
                                        'between' => '<div class="col-md-4">',
                                        'separator' => '</div><div class="col-md-4">',
                                        'after' => '</div>',
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="col-md-4">Gender: </label>
                            <div class="col-md-8">
                                <?php 
                                foreach($settings['gender'] as $key=>$item){
                                    if($key >= 1){
                                        ?>
                                        <label class="control-inline fancy-radio">
                                            <input type="radio" <?php if($content[0]['DetailUser']['gender'] == $key){ echo 'checked'; } ?> name="data[DetailUser][gender]" value="<?=$key?>">
                                            <span><i></i><?=$item?></span>
                                        </label>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                            
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="col-md-4">Ethinicy: </label>
                            <div class="col-md-8">
                                <?php 
                                foreach($settings['ethnicity'] as $key=>$race){
                                    if($key >= 1){
                                        ?>
                                        <label class="fancy-radio">
                                            <input type="radio" <?php if($content[0]['DetailUser']['race'] == $key){ echo 'checked'; } ?> name="data[DetailUser][race]" value="<?=$key?>">
                                            <span><i></i><?=$race?></span>
                                        </label>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-footer">
                <div class="widget-header-toolbar">
                    <input type="submit" class="btn btn-primary pull-right" value="Save">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>  
    </div>
</div>

<script language="JavaScript">
    
    jQuery(document).ready( function($) {
        $('#mySwitch').on('switch-change', function (e, data) {
            var $el = $(data.el)
            , value = data.value;
            
            console.log(e, $el, value);
        });
        
        $('#phone').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#phone").on('change', function() {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $('#mobile').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#mobile").on('change', function() {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $('#fax').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#fax").on('change', function() {
            var number = $(this).val()
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
    });
</script>