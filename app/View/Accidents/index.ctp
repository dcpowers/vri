<?php
    #pr($accidents);
    #exit;
?>

<div class="account index bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Accidents</h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i><?=$title?> Accidents</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Accidents/menu' );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Accidents/status_filter' );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>
	<?php
    foreach($accidents as $title=>$v){
		?>
		<div class="hr-divider">
        	<h3 class="hr-divider-content hr-divider-heading"><?=$title?></h3>
        </div>
	    <table class="table table-striped" id="accountsTable">
	        <thead>
	            <tr class="tr-heading">
	                <th class="text-center col-md-1">
                		<?php echo $this->Paginator->sort('Accident.id', 'Id');?>
	                    <?php if ($this->Paginator->sortKey() == 'Accident.id'): ?>
                    		<i class='fa fa-sort-numeric-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
	                    <?php else: ?>
                    		<i class='fa fa-sort'></i>
	                    <?php endif; ?>
	                </th>

	                <th class="col-md-2">
                		<?php echo $this->Paginator->sort('Accident.first_name', 'Name');?>
	                    <?php if ($this->Paginator->sortKey() == 'Accident.first_name'): ?>
                    		<i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
	                    <?php else: ?>
                    		<i class='fa fa-sort'></i>
	                    <?php endif; ?>
	                </th>

	                <th class="col-md-2">
                		<?php echo $this->Paginator->sort('Department.name', 'Department');?>
	                    <?php if ($this->Paginator->sortKey() == 'Department.name'): ?>
                    		<i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
	                    <?php else: ?>
                    		<i class='fa fa-sort'></i>
	                    <?php endif; ?>
	                </th>

					<th class="col-md-2">
                		<?php echo $this->Paginator->sort('Accident.date', 'Date');?>
	                    <?php if ($this->Paginator->sortKey() == 'Accident.date'): ?>
                    		<i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
	                    <?php else: ?>
                    		<i class='fa fa-sort'></i>
	                    <?php endif; ?>
	                </th>

					<th class="col-md-3">Description</th>

					<th class="col-md-2"></th>

	            </tr>
	        </thead>

	        <tbody>
	            <?php
	            foreach($v as $a){
					$class= ($a['Accident']['is_active'] == 2) ? 'danger' : null ;
					$date = date('F d, Y', strtotime($a['Accident']['date']));
	                ?>
	                <tr class="<?=$class?>">
						<td class="text-center"><?=$a['Accident']['id']?></td>
	                    <td>
	                        <?php
	                        echo $this->Html->link(
	                            $a['Accident']['first_name'].' '.$a['Accident']['last_name'],
	                            array('controller'=>'Accidents', 'action'=>'view', $a['Accident']['id']),
	                            array('escape'=>false)
	                        );
	                        ?>
	                    </td>

	                    <td><?=$a['Dept']['name']?></td>

	                    <td><?=$date?></td>
	                    <td><?=$a['Accident']['description']?></td>
                        <td>
							<ul class="list-inline">
								<li>
									<?php
									if($a['Accident']['is_active'] == 1){
										echo $this->Html->link(
				                            '<i class="fa fa-fw fa-unlock"></i>',
				                            array('controller'=>'Accidents', 'action'=>'close', $a['Accident']['id'], 2),
				                            array('escape'=>false)
				                        );
									}else{
										echo $this->Html->link(
				                            '<i class="fa fa-fw fa-lock"></i>',
				                            array('controller'=>'Accidents', 'action'=>'open', $a['Accident']['id'], 1),
				                            array('escape'=>false)
				                        );
									}
                                    ?>
								</li>
								<?php
								if(AuthComponent::user('Role.permission_level') >= 70){
									?>
									<li>
										<?php
										echo $this->Html->link(
				                            '<i class="fa fa-fw fa-trash"></i>',
				                            array('controller'=>'Accidents', 'action'=>'delete', $a['Accident']['id'], 1),
				                            array('escape'=>false),
											array('Are You Sure You Want To Delete This Accident?')
				                        );
										?>
									</li>
									<?php
								}
								?>
							</ul>
						</td>
	                </tr>
	                <?php
	            }
	            ?>
	        </tbody>
	    </table>
		<?php
	}
	?>
    <?php echo $this->element( 'paginate' );?>
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