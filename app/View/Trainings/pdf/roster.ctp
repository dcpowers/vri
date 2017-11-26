<h1><?=$trn?></h1>
	<h4>Date: _____________________________________________</h4>
	<table style="width:100%; border-spacing: 5px;">
    	<thead>
        	<tr>
            	<th style="width: 50%;">User</th>
                <th style="width: 50%;">Signature</th>
            </tr>
        </thead>

        <tbody>
        	<?php
            foreach($users as $v){
				?>
                <tr>
                	<td style="height: 50px; border-bottom: 1px #000000 solid"><?=$v?></td>
                    <td style="height: 50px; border-bottom: 1px #000000 solid"></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>