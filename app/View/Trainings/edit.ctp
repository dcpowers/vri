<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'edit', $this->request->data['Training']['id']),
        'role'=>'form',
		'type'=>'file',
        #'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

	echo $this->Form->hidden('id', array('value'=>$this->request->data['Training']['id']));
?>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit Training: <small>'.$this->request->data['Training']['name'].'</small>'); ?></h2>
</div>

<div class="modal-body">
	<div class="form-group">
            	<label for="name" class="control-label">Name:</label>
                <?php
				echo $this->Form->input('name', array (
					'type'=>'text'
				));
				?>
    </div>

	<div class="form-group">
    	<label for="name" class="control-label">Description:</label>
        <?php
		echo $this->Form->input('description', array (
			'type'=>'textarea'
		));
		?>
    </div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="name" class="control-label">Status:</label><br />
				<label class="radio-inline">
					<?php
					echo $this->Form->radio('is_active',
						array (1=>'Active', 0=>'Inactive'),
						array('value'=>$this->request->data['Training']['is_active'], 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
					);
					?>
				</label>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="name" class="control-label">Make Avaiable To Others:</label><br />
				<label class="radio-inline">
					<?php
					echo $this->Form->radio('is_public',
						array (1=>'Make Public', 0=>'Make Private'),
						array('value'=>$this->request->data['Training']['is_public'], 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
					);
					?>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
    	<label for="name" class="control-label">Files:</label>
        <?php
		echo $this->Form->input('files.', array(
			'type' => 'file',
			'multiple'=>true
		));
		?>
		<small>Videos MUST BE in MP4 format!</small>
    </div>
	<div id="files">
	    <?php
		if(!empty($this->request->data['TrainingFile'])){
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
	                foreach($this->request->data['TrainingFile'] as $file){

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
	</div>
</div>
<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

    echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    );
    ?>
</div>
<?php echo $this->Form->end();?>
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