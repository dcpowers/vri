<?php
    $this->request->data = $this->requestAction('/Users/profile/');
    #pr($this->request->data);

?>

<div class="profile dashboard">
    <div class="row">
		<div class="col-sm-6 col-md-12">
			<div class="thumbnail" style="margin-bottom: 0px;">
				<?php
				clearstatcache();
                $image = (file_exists('img/profiles/'.$this->request->data['User']['id'].'.png')) ? '/img/profiles/'.$this->request->data['User']['id'].'.png' : '/img/profiles/noImage.png' ;
                echo $this->Html->image($image, array('class'=>'img-circle img-responsive thumbnail', 'alt'=>$current_user['last_name'], 'style'=>'margin-bottom: 0px;'));
			    ?>

				<div class="caption">
					<h3>
						<?php
		            	echo $this->Html->link(
		                	$this->request->data['User']['first_name'].' '.$this->request->data['User']['last_name'],
		                	array('controller'=>'Users', 'action'=>'profile', 'info'),
		                	array('escape'=>false)
		            	);
                        ?>
					</h3>
					<dl>
				    	<dt>Status:</dt>
				        <dd><?=$this->request->data['Status']['name']?></dd>
				    </dl>

					<dl>
				    	<dt>Account:</dt>
				        <dd><?= $this->request->data['AccountUser'][0]['Account']['name']; ?></dd>
				    </dl>

					<dl>
				    	<dt>Department(s):</dt>
				        <dd>
				        	<ul class="list-unstyled">
				            	<?php
				                foreach($this->request->data['DepartmentUser'] as $dept){
				                	?>
				                    <li><?=$dept['Department']['name']?></li>
				                    <?php
				                }
				                ?>
				            </ul>
				        </dd>
				    </dl>

					<dl>
				    	<dt>Supervisor:</dt>
				        <dd><?=$this->request->data['Supervisor']['first_name']?> <?=$this->request->data['Supervisor']['last_name']?></dd>
				    </dl>
			    </div>
			</div>
		</div>
	</div>
</div>
