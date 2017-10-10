<?php
    //set breadcrumb trail Use this as an example
    #$this->Html->addCrumb('Users', array('controller' => 'users', 'action' => 'add', 'member'=>true));
    #pr($assignedTest);
    $this->Html->css('bootstrap-fileupload.min.css', '', array('block' => 'csslib') );
    $this->Html->script('bootstrap-fileupload.js', array('block' => 'scriptsBottom'));
    #pr($validationErrors);
    #pr($content);
    #exit;
    $group_ids = Set::extract( AuthComponent::user(), '/GroupMembership/group_id' );
?>
<style type="text/css">
.label-as-badge {
    border-radius: 5em;
}
</style>
<div class="container">
    <h1><?php echo ucfirst ( strtolower( AuthComponent::user('first_name') ) ) . ' ' . ucfirst ( strtolower( AuthComponent::user('last_name') ));?></h1>
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
        echo $this->Form->hidden( 'DetailUser.id', array( 'value' => $content[0]['DetailUser']['id'] ) ); 
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
                                    $image = (!empty($content[0]['DetailUser']['img'])) ? $name : 'noImage.png' ;
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
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label text-danger" for="username">E-Mail Address ( Username ):</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('username', array (
                                    'type' => 'email',
                                    'value' => $content[0]['User']['username'],
                                    'id'=>'email'
                                ));?>
                                <small>E-Mail address will be used to log into the system</small>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['User']['password_update']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['User']['password_update']) ? $validationErrors['User']['password_update'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label" for="phone">Password:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('password_update', array (
                                    'type' => 'password',
                                    'id'=>'password_update',
                                    'value'=>''
                                ));?>
                                <small class="text-info">Leave Blank/Empty if you do not want to update!</small><br />
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['User']['password_confirm_update']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['User']['password_confirm_update']) ? $validationErrors['User']['password_confirm_update'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label" for="phone">Password Repeat:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('password_confirm_update', array (
                                    'type' => 'password',
                                    'id'=>'password_confirm_update'
                                ));?>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['DetailUser']['phone']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['DetailUser']['phone']) ? $validationErrors['DetailUser']['phone'][0] : '';
                        ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="phone">Phone Number:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.phone', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['phone'],
                                    'id'=>'phone'
                                ));?>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
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
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['DetailUser']['zip']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['DetailUser']['zip']) ? $validationErrors['DetailUser']['zip'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label" for="zip">Zip:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.zip', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['zip'],
                                    'id'=>'zip'
                                ));?>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                   
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="county">County:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.county', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['county'],
                                ));?>
                            </div>
                        </div>
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['DetailUser']['mobile']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['DetailUser']['mobile']) ? $validationErrors['DetailUser']['mobile'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label" for="mobile">Mobile Phone:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.mobile', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['mobile'],
                                    'id'=>'mobile'
                                ));?>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                        unset($class,$error);
                        $class = !empty($validationErrors['DetailUser']['fax']) ? 'has-error has-feedback' : ''; 
                        $error = !empty($validationErrors['DetailUser']['fax']) ? $validationErrors['DetailUser']['fax'][0] : '';
                        ?>
                        <div class="form-group <?=$class?>">
                            <label class="col-sm-4 control-label" for="fax">Fax:</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->input('DetailUser.fax', array (
                                    'type' => 'text',
                                    'value' => $content[0]['DetailUser']['fax']
                                    
                                ));?>
                                <?php
                                if(!empty($error)){
                                    ?>
                                    <small class="text-danger"><?=$error?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget widget-no-header">
            <div class="widget-content">
                <div class="row">
                    <?php
                    if( in_array(3,$group_ids) || in_array(4,$group_ids) ){
                        ?>
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
                            <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="col-md-5 text-danger">Are You Interested in Further Education? </label>
                                            <div class="col-md-7">
                                                <?php 
                                                foreach($settings['relocate'] as $key=>$item){
                                                    if($key != 0){
                                                    ?>
                                                    <label class="control-inline fancy-radio">
                                                        <input type="radio" <?php if($content[0]['DetailUser']['education'] == $key){ echo 'checked'; } ?> name="data[DetailUser][education]" value="<?=$key?>">
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
                                        <label class="col-md-12">Join A Workforce Region: </label>
                                        <div class="col-md-12">
                                            <?php
                                            $selected = null;
                                            foreach($content[0]['GroupMembership'] as $group){
                                                if($group['group_id'] != 4){
                                                    echo $this->Form->hidden( 'GroupMembership.0.id', array( 'value' => $group['id'] ) ); 
                                                    $selected = $group['group_id'];
                                                }
                                            }
                                            
                                            
                                            echo $this->Form->input('GroupMembership.0.group_id', array(
                                                'type' => 'select',
                                                'empty'=>true, 
                                                'multiple' => false,
                                                'options' => $wfcList,
                                                //'class'=>'select2 select2-multiple select2-choices',
                                                'data-placeholder'=>'Choose an Workforce Region(s)',
                                                //'id'=>'e1',
                                                'selected'=>$selected
                                                
                                             ));
                                             ?>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    
                    if( in_array(3,$group_ids) || in_array(4,$group_ids) ){
                        ?>
                        <div class="col-md-8">
                        <?php
                    }else{
                        ?>
                        <div class="col-md-12">
                        <?php
                    }
                    ?>
                        <h2>EEOC Information</h2>
                        <hr />
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-4">Birthday: </label>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <?php
                                                $m = date('m');
                                                $d = date('d');
                                                $y = date('Y');
                                                
                                                $today = $m.'-'.$d.'-'.$y;
                                                
                                                $pieces = explode("-", $content[0]['DetailUser']['dob']);
                                                if($pieces[0] == '00' || empty($pieces[0])){
                                                    $b_today = null;
                                                }else{
                                                    $m = $pieces[1];
                                                    $d = $pieces[2];
                                                    $y = $pieces[0];
                                                    
                                                    $b_today = $m.'-'.$d.'-'.$y;
                                                }
                                            ?>
                                            <div class="input-group input-append date" id="dp3" data-date="<?=$today?>" data-date-format="mm-dd-yyyy" >
                                                <span class="add-on input-group-addon"><i class="icon ion-calendar"></i></span>
                                                <?php
                                                echo $this->Form->input('DetailUser.dob', array(
                                                    'type'=>'text',
                                                    'readonly'=>true,
                                                    //'data-provide'=>'datepicker',
                                                    'value'=>$b_today
                                                ));
                                                ?>
                                            </div>
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
                                    <label class="col-md-4">Ethnicy: </label>
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
        
        if( !in_array(3,$group_ids) && !in_array(4,$group_ids) ){
            echo $this->Form->end();
        }
        
        if( in_array(3,$group_ids) || in_array(4,$group_ids) ){
            ?>
            <div class="widget widget-no-header">
                <div class="widget-content">
                    <?php echo $this->Form->end(); ?>
                    <div class="col-md-12">
                        <h2>Job Information</h2>
                            <hr />
                            <p>Type in a key word to begin!</p>
                            <p>What jobs have you done in the past? What do you do now? Type in your job title(s) and pick the closest one from the list that appears. We'll put this on your digital resume.</p>
                            <?php 
                            echo $this->Form->create('Search', array(
                                'role'=>'form',
                                'class'=>'form-horizontal',
                                'inputDefaults' => array(
                                    'label' => false,
                                    'div' => false,
                                    'class'=>'form-control',
                                    'error'=>false
                                ),
                                'id'=>'searchForm'
                            ));
                            ?>
                            <div class="input-group">
                                <?php echo $this->Form->input('search', array (
                                    'type' => 'text',
                                    'id'=>'search',
                                    'placeholder'=>'Type in key word, then click search -->',
                                    'empty'=>'',
                                    
                                ));?>
                                <span class="input-group-btn">
                                    <?php
                                    echo $this->Html->link(
                                        '<button class="btn btn-success"><i class="icon ion-ios7-search-strong"></i>Search</button>',
                                        array('controller'=>'users', 'member'=>true, 'action'=>'search'),
                                        array('escape'=>false, 'id'=>'searchLink', 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'pull-right searchbutton')
                                    );
                                    ?>
                                </span>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div id="searchResults">
                                <ul class="list-unstyled">
                                    <?php
                                    
                                    foreach($content[0]['SocUser'] as $item){
                                        
                                        ?>
                                        <li>
                                            <?=$item['soc_code']?> - <?=$item['soc_title']?>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php
        }
        ?>  
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
<script language="JavaScript">
    
    jQuery(document).ready( function($) {
        $('#mySwitch').on('switch-change', function (e, data) {
            var $el = $(data.el)
            , value = data.value;
            
            console.log(e, $el, value);
        });
        
        $('#phone').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#phone").on('change', function() {
            
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $('#mobile').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#mobile").on('change', function() {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $('#fax').text(function(i, text) {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#fax").on('change', function() {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1)$2-$3");
            $(this).val(number)
        });
        
        $("#zip").on('change', function() {
            var number = $(this).val()
            number = number.replace(/\D/g,"");
            $(this).val(number)
        });
        
        
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $(document).ready(function() {
            var a = $('#searchLink').attr('href');
            
            $('#search').keyup(function(){
                var b = $('#search').val();
                //$('#searchLink').text(a + '/' + b);
                $('#searchLink').attr('href', a + '/' + b);
                
            });
        });
        
        $("#searchLink").on("click", function() {
            $("#search").val("")
        });
        
        $('#dp3').datepicker({
            autoclose: true,
            startView: 2
            
        });
        
        
        $("#e1").select2();
    
        
    });
</script>