<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html">Vanguard Resources</a>
    </div><!-- /.login-logo -->
    
    <div class="login-box-body">
        <p class="login-box-msg">One System</p>
        
        <?php echo $this->Form->create('User'); ?>
        
        <div class="form-group has-feedback">
            <?php 
            echo $this->Form->input('username', array(
                'type'=>'text',
                'class'=>'form-control',
                'placeholder'=>'Username',
                'label'=>false,
                'div'=>false
            )); 
            ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        
        <div class="form-group has-feedback">
            <?php 
            echo $this->Form->input('password', array(
                'type'=>'password',
                'class'=>'form-control',
                'placeholder'=>'Password',
                'label'=>false,
                'div'=>false
            )); 
            ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox"> Remember Me
                    </label>
                </div>
            </div><!-- /.col -->
            
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
        </div>
        
        <?php echo $this->Form->end(); ?>

        
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php
    
?>
<script type="text/javascript">
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>