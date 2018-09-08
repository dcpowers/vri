<?php
    #pr($settings);
    #exit;
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Site Settings</h6>
            <h3 class="dashhead-title"><i class="fa fa-cogs fa-fw"></i> Site Settings</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/menu' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>
    
    <dl class="dl-horizontal">
		<dt>User Settings</dt>
	  	<dd>
	  		<?php
            echo $this->Html->link(
                'Employment Type',
                array('controller'=>'groups', 'member'=>true, 'action'=>'profile'),
                array(
                    'escape'=>false,
                    'data-toggle'=>'modal', 
                    'data-target'=>'#myModalBig',
                )
            );
            ?>
        </dd>
        <dd>
            <?php
            echo $this->Html->link(
                'Permissions',
                array('controller'=>'groups', 'member'=>true, 'action'=>'profile'),
                array(
                    'escape'=>false,
                    'data-toggle'=>'modal', 
                    'data-target'=>'#myModalBig',
                )
            );
            ?>
        </dd>
	</dl>
    
    <div class="clearfix"></div>
    <hr class="solidGray" />
    <dl class="dl-horizontal">
		<dt>Training Settings</dt>
	  	<dd>
	  		<?php
            echo $this->Html->link(
                'Category Settings',
                array('controller'=>'groups', 'member'=>true, 'action'=>'profile'),
                array(
                    'escape'=>false,
                    'data-toggle'=>'modal', 
                    'data-target'=>'#myModalBig',
                )
            );
            ?>
        </dd>
	</dl>
	
	<div class="clearfix"></div>
    <hr class="solidGray" />
    <dl class="dl-horizontal">
		<dt>Account Settings</dt>
	  	<dd>
	  		<?php
            echo $this->Html->link(
                'Department',
                array('controller'=>'groups', 'member'=>true, 'action'=>'profile'),
                array(
                    'escape'=>false,
                    'data-toggle'=>'modal', 
                    'data-target'=>'#myModalBig',
                )
            );
            ?>
        </dd>
	</dl>
	<div class="clearfix"></div>
    <hr class="solidGray" />
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $("#myModalBig").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $(".modal-wide").on("show.bs.modal", function() {
          var height = $(window).height() - 200;
          $(this).find(".modal-body").css("max-height", height);
        });
     });
</script>