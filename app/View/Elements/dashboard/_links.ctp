<?php
    $data = $this->requestAction('/Links/dashboard');
    #pr($data);
	$count = count($data);

?>

<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Quick Links</h3>
        <div class="box-tools pull-right">
            <?php
            if(AuthComponent::user('Role.permission_level') >= 60){
                echo $this->Html->link(
                    '<i class="fa fa-wrench fa-fw"></i>',
                    array('controller'=>'Links', 'action'=>'index'),
                    array('escape'=>false)
                );

            }
            ?>
        </div>
    </div>
    <div class="box-body">
		<ul class="list-unstyled">
			<?php
    		#pr($requiredTraining );
    		foreach($data as $v){
				if(AuthComponent::user('Role.permission_level') >= $v['Link']['permission_level']){
	                echo '<li>';
					echo $this->Html->link(
		                $v['Link']['name'],
		                $v['Link']['url'],
		                array('escape'=>false, 'target'=>'_blank' )
		            );
					echo '</li>';
				}
			}
			?>
		</ul>
	</div>

    <div class="box-footer" style="border-bottom: 1px solid #C0C0C0;"></div>
</div>