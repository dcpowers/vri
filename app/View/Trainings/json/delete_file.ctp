<?php
		if(!empty($trainingFiles)){
    		?>
			<table class="table table-striped table-condensed" id="trainingTable">
        		<thead>
            		<tr class="tr-heading">
                		<th>File</th>
	                    <th>File Type</th>
	                    <th>File Size</th>
	                    <th></th>
	                </tr>
	            </thead>

	            <tbody>
            		<?php
	                foreach($trainingFiles as $file){

                		$filePath = filesize(WWW_ROOT .'/files/'.$this->request->data['Training']['id'].'/'.$file['file']);
	                    $fileSize = human_filesize($filePath);

						?>
	                    <tr>
                    		<td><?=$file['human_name']?></td>
	                        <td><?=$file['file_type']?></td>
	                        <td><?=$fileSize?></td>
	                        <td>
								<?php
								echo $this->Html->link(
			                    	'<i class="fa fa-trash text-warning fa-2x" aria-hidden="true"></i>',
			                        '#',
			                        array('class'=>'trnRecord', 'data-value'=>$file['id'], 'data-file'=>$file['file'], 'escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'left', 'title'=>'Delete File')
			                    );
								?>

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
<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>
<?php
$trnFile = $this->Html->url(array('plugin'=>false, 'controller'=>'Trainings', 'action' => 'deleteFile'));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $('.trnRecord').on('click', function () {
			var Id = $(this).attr("data-value");

			$.ajax({
            	type: 'post',
                url: '<?=$trnFile?>/' + Id + '/<?=$this->request->data['Training']['id']?>/.json',
                dataType: "html",
                beforeSend: function(xhr) {
                	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                },
                success: function(response) {
                	console.log(response);
                    $('#files').html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                	console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function(){
                	$('#overlay').remove();
                },
            });
		});
    });
</script>