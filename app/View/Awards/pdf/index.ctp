	<?php
	$dateObj   = DateTime::createFromFormat('!m', $month);
	$monthName = $dateObj->format('F'); // March
	
	?>
	<h1>Awards Payment</h1>
	<h4>Date: <?=$monthName?> <?=$year?></h4>
	<table style="width:100%; border-spacing: 5px;">
		<thead>
	    	<tr class="tr-heading">
                <th style="width: 40%;">User</th>
				<th style="width: 20%;">Amount</th>
				<th style="width: 40%;">Signature</th>
	        </tr>
	    </thead>

	    <tbody>
			<?php
			$c = 0;
			$edit = false;
			$dateObj   = DateTime::createFromFormat('!m', $month);
			$monthName = $dateObj->format('F'); // March

			$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
			$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
			$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));
			if(isset($results)){
				foreach($results as $r){
					#$amount = ($r['User']['pay_status'] == 1) ? '5.00' : '2.50' ;
					#$amount = ($r['User']['is_award'] == 0) ? 0 : $amount ;
					$amount = $this->Number->currency($r['User']['award_amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));
					$name = $r['User']['first_name'].' '.$r['User']['last_name'];
					?>
					<tr>
						
						<td style="height: 30px; border-bottom: 1px #000000 solid"><?=$name?></td>
						<td style="height: 30px; border-bottom: 1px #000000 solid"><?=$amount;?></td>
						<td style="height: 30px; border-bottom: 1px #000000 solid"></td>
					</tr>
					<?php
					$c++;
					unset($amount);
				}
			}
			?>
		</tbody>
	</table>