<h1><?php echo __('Classroom Details: <small>'. $data['Classroom']['name'].'</small>'); ?></h1>
	<h3>Instructor: <small><?=$data['Trainer']?></small></h3>
	<h4>Date: <small><?php echo date('F d, Y', strtotime($data['Classroom']['date'])); ?></small></h4>
	<table style="width:100%; border-spacing: 5px;">
    	<thead>
        	<tr>
            	<th style="width: 50%;">User</th>
                <th style="width: 50%;">Signature</th>
            </tr>
        </thead>

        <tbody>
        	<?php
            foreach($data['User'] as $v){
				?>
                <tr>
                	<td style="height: 50px; border-bottom: 1px #000000 solid"><?=$v['first_name']?> <?=$v['last_name']?></td>
                    <td style="height: 50px; border-bottom: 1px #000000 solid"></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>