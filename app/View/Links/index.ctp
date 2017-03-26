<?php
	$roles[0] = 'Everyone';
?>
<div class="link index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Quick Link Manager</h6>
            <h3 class="dashhead-title"><i class="fa fa-link fa-fw"></i> All Links</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
			<?php echo $this->element( 'Links/menu' );?>
        </div>
    </div>

	<table class="table table-striped" id="accountsTable">
    	<thead>
        	<tr class="tr-heading">
            	<th class="col-md-4">Name</th>
                <th class="col-md-2">Status</th>
                <th class="col-md-3 text-center">Permission Level</th>
				<th class="col-md-3"></th>
            </tr>
        </thead>

		<tbody>
        	<?php
            foreach($links as $data){
            	#pr($roles);
                #exit;
				#pr($data['Link']['permission_level']);
                ?>
                <tr>
                	<td>
						<?php
						echo $this->Html->link(
		                	$data['Link']['name'],
		                    array('controller'=>'Links', 'action'=>'view', $data['Link']['id']),
		                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
		                );
						?>
					</td>
                	<td><span class="<?=$data['Status']['color']?>"><?=$data['Status']['name']?></span></td>
                    <td class="text-center"><?=$roles[$data['Link']['permission_level']]?></td>
					<td>
						<ul class="list-inline">
							<li>
								<?php
								echo $this->Html->link(
		                        	'<i class="fa fa-link fa-fw"></i>',
		                            $data['Link']['url'],
		                            array('escape'=>false, 'target'=>'_blank', 'class'=>'btn btn-info btn-sm')
		                        );
								?>
							</li>
							<li>
								<?php
								echo $this->Html->link(
		                        	'<i class="fa fa-trash fa-fw"></i>',
		                            array('controller'=>'Links', 'action'=>'delete', $data['Link']['id']),
		                            array('escape'=>false, 'class'=>'btn btn-danger btn-sm'),
									'Are You Sure You Want To Delete This Link?'
		                        );
								?>
							</li>
						</ul>
					</td>
				</tr>
                <?php
            }
            ?>
        </tbody>
    </table>
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