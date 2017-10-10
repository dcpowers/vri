<?php
    //pr($this->Session->read('Auth.User.Location.name'));
    #pr($users);
    #exit;
    
    $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', array('inline' => false));

?>
<style>
/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   speak for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('../img/loading51.gif') 
                50% 50% 
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}
</style>
<div id="replaceContent">
    <div class="container">
        <div class="row">
            <div class="users form">
                <h1>All Users</h1>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-2">
                            <?php
                                echo $this->Html->link(
                                    "Active", 
                                    '#', 
                                    array('rel'=>'/admin/users/index/1','class'=>'link-switch', 'escape'=>false)
                                ); ?> | <?php
                                
                                echo $this->Html->link(
                                    "Inactive", 
                                    '#', 
                                    array('rel'=>'/admin/users/index/0', 'class'=>'link-switch', 'escape'=>false)
                                ); ?> | <?php
                                
                                echo $this->Html->link(
                                    "All Users", 
                                    '#',
                                    array('rel'=>'/admin/users/index/3', 'class'=>'link-switch', 'escape'=>false)
                                );
                            ?>
                            </div>
                            <div class="col-md-6 col-md-offset-2">
                                <!--<?php echo $this->Form->create(); ?>
                                <?php echo $this->Form->input('name', array('class' => 'ui-autocomplete', 'id' => 'autocomplete'));?>
                                <?php echo $this->Form->end();?>-->
                            </div>
                            <div class="pull-right col-md-2">
                                <?php echo $this->Html->link( "Add A New User.",   array('action'=>'add'),array('escape' => false) ); ?> 
                            </div>
                        
                        </div>
                    </div>
                    
                    <table class="table table-striped" id="information">
                        <thead>
		                    <tr>
                                <th><?php echo $this->Paginator->sort('first_name', 'Firstname');?>  </th>
			                    <th><?php echo $this->Paginator->sort('last_name', 'Lastname');?>  </th>
                                <th><?php echo $this->Paginator->sort('username', 'Username');?>  </th>
			                    <th style="text-align:center">Activated</th>
			                    <th style="text-align:center">Status</th>
			                    <th style="text-align:center">Actions</th>
		                    </tr>
	                    </thead>
	                    <tbody>						
                            <?php 
                            foreach($users as $user){
                                if($user['User']['is_activated'] == 1){
                                    $a_icon = '<span class="badge badge-round element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                    $a_accessCode = 0;
                                }else{
                                    $a_icon = '<span class="badge badge-round badge-ghost"><i class="icon ion-close"></i></span>';
                                    $a_accessCode = 1;
                                }
                                
                                if($user['User']['is_active'] == 1){
                                    $icon = '<span class="badge badge-round element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                    $accessCode = 0;
                                }else{
                                    $icon = '<span class="badge badge-round badge-ghost"><i class="icon ion-close"></i></span>';
                                    $accessCode = 1;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $user['User']['first_name'];?></td>
			                        <td><?php echo $user['User']['last_name'];?></td>
                                    <td><?php echo $user['User']['username'];?></td>
                                    <td style="text-align:center">
                                        <?php
                                        echo $this->Html->link( 
                                            $a_icon, 
                                            array( 
                                                'controller'=>'users',
                                                'action'=>'activate', 
                                                'admin'=>true,
                                                $user['User']['id'], 
                                                $a_accessCode
                                            ), 
                                            array('escape'=>false, 'class'=>'btn-switch-activate') 
                                        );
                                        ?>
                                    </td>
                                    
                                    <td style="text-align:center">
                                        <?php
                                        echo $this->Html->link( 
                                            $icon, 
                                            array( 
                                                'controller'=>'users',
                                                'action'=>'status', 
                                                'admin'=>true,
                                                $user['User']['id'], 
                                                $accessCode
                                            ), 
                                            array('escape'=>false, 'class'=>'btn-switch-status') 
                                        );
                                        ?>
                                        
                                    </td>
                                    
                                    <td style="text-align:center">   
			                            <?php 
                                        echo $this->Html->link( "View",   array('controller'=>'users', 'admin'=>true, 'action'=>'view', $user['User']['id']) );
					                    ?>
			                        </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="panel-footer">
                        <?php 
                        echo $this->Paginator->numbers(array(
                            'before' => '<ul class="pagination pagination-lg">',
                            'separator' => '',
                            'currentClass' => 'active',
                            'currentTag' => 'a',
                            'tag' => 'li',
                            'after' => '</ul>'
                        ));
                        ?>
                        <div class="pull-right">
                            <?php echo $this->Paginator->counter(
                                'Page {:page} of {:pages}, showing {:current} records out of {:count} total, 
                                starting on record {:start}, ending on {:end}' ); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>				
        </div>
    </div>
</div>
<div class="modal"><!-- Place at bottom of page --></div>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }    
        });
     
        $('#autocomplete').autocomplete({
            source: <?php echo json_encode($userList); ?>,
        });
        
        $('.link-switch').on('click', function() {
            var url = $(this).attr('rel');
            $('table').hide();
            $('.panel-footer' ).hide();
        
            $.ajax({
                url: url,
                cache: false,
                type: 'GET',
                dataType: 'HTML',
                success: function (data) {
                    $('#replaceContent').html(data);
                    $('.panel-footer' ).fadeIn();
                }
            });
        });
        
        $('.btn-switch-status').on('click', function() {
            var row = $(this).parent().find('span');
            var url = $(this).attr('href');
            var pathArray = url.split( '/' );
        
            $.ajax( {
                type:'get',
                url: $(this).attr('href'),
                dataType: 'json',
                success: function(response) {
                    if(response.sucess == false) {
                        alert('There was an error');
                    }
                    
                    if(response.sucess == true) {
                        var newPath = '/member/users/status/' + pathArray[4] +'/';
                        
                        if(response.s == 1){
                            row.removeClass('element-bg-color-green').addClass('badge-ghost');
                            row.find('i').removeClass('ion-checkmark').addClass('ion-close');
                        }else{
                            row.removeClass('badge-ghost').addClass('element-bg-color-green');
                            row.find('i').removeClass('ion-close').addClass('ion-checkmark');
                        }
                        
                        row.closest('a').attr("href", newPath + response.s);
                        
                    }
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                }
            });
                
            return false;
        });
        
        $('.btn-switch-activate').on('click', function() {
            var row = $(this).parent().find('span');
            var url = $(this).attr('href');
            var pathArray = url.split( '/' );
        
            $.ajax( {
                type:'get',
                url: $(this).attr('href'),
                dataType: 'json',
                success: function(response) {
                    if(response.sucess == false) {
                        alert('There was an error');
                    }
                    
                    if(response.sucess == true) {
                        var newPath = '/member/users/activate/' + pathArray[4] +'/';
                        
                        if(response.s == 1){
                            row.removeClass('element-bg-color-green').addClass('badge-ghost');
                            row.find('i').removeClass('ion-checkmark').addClass('ion-close');
                        }else{
                            row.removeClass('badge-ghost').addClass('element-bg-color-green');
                            row.find('i').removeClass('ion-close').addClass('ion-checkmark');
                        }
                        
                        row.closest('a').attr("href", newPath + response.s);
                        
                    }
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                }
            });
                
            return false;
        });
    });
</script>            