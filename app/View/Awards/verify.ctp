<?php
    #pr($accidents);
    #exit;
?>

<div class="account index bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Verify Awards: <?=$months[$month]?> <?=$years[$year]?></h6>
            <h3 class="dashhead-title"><i class="fa fa-trophy fa-fw"></i>Awards</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Awards/menu' );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Awards/status_filter', ['month'=>$month, 'year'=>$year, 'months'=>$months, 'years'=>$years] );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>
    <?php
    echo $this->Form->create('Awards', array(
    	'url'=>array('controller'=>'Awards', 'action'=>'process'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
        	'label' => false,
            'div' => false,
            #'between' => '<div class="input-group">',
            'class'=>'form-control',
            #'after' => '</div>',
            'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-block'))
        )
    ));
    ?>
	<table class="table table-striped" id="accountsTable">
		<thead>
	    	<tr class="tr-heading">
                <th class="col-md-1"></th>
				<th class="col-md-4">User</th>
				<th class="col-md-4">Verify Date</th>
	            <th class="col-md-3">Amount</th>
	        </tr>
	    </thead>

	    <tbody>
			<?php
			$c = 0;
			$dateObj   = DateTime::createFromFormat('!m', $month);
			$monthName = $dateObj->format('F'); // March

			$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
			$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
			$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));
			if(isset($results)){
				foreach($results as $r){
					$amount = ($r['User']['pay_status'] = 1) ? '5.00' : '2.50' ;
					$name = $r['User']['first_name'].' '.$r['User']['last_name'];

					echo $this->Form->hidden($c.'.verified_by', array('value'=>AuthComponent::user('id')));
					echo $this->Form->hidden($c.'.verified_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
            		echo $this->Form->hidden($c.'.date', array('value'=>$end));
					echo $this->Form->hidden($c.'.account_id', array('value'=>$r['User']['account_id']));
					echo $this->Form->hidden($c.'.department_id', array('value'=>$r['User']['dept_id']));
					echo $this->Form->hidden($c.'.award_type_id', array('value'=>1));
					echo $this->Form->hidden($c.'.user_id', array('value'=>$r['User']['id']));
					?>
					<tr>
						<td>
							<div class="form-group" >
                        		<label class="sr-only control-label">Eligible Bingo:</label>
                            	<div class="checkbox">
			                		<label> <?php echo $this->Form->checkbox($c.'.verify', array('checked'=>true)); ?></label>
								</div>
                        	</div>
						</td>
						<td><?=$name?></td>
						<td><?php echo date('F d, Y', strtotime('now')); ?></td>
						<td>
							<div class="form-group">
                            	<?php
                                echo $this->Form->input($c.'.amount', array (
                                	'type'=>'text',
                                    'value'=>$amount
                                ));
                                ?>
                            </div>
						</td>
					</tr>
					<?php
					$c++;
				}
			}
			?>
		</tbody>
	</table>
	<?php
    echo $this->Form->button('Verify', array(
    	'type'=>'submit',
        'class'=>'btn btn-primary pull-left'
    ));
    ?>
	<?php echo $this->Form->end(); ?>
</div>



<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
        	allow_single_deselect: false,
			width: '100%',
			disable_search: true
		});


     });
</script>