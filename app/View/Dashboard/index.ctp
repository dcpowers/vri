<div class="dashboard index">
    <div class="row">
        <div class="col-md-2">
            <?php echo $this->element( 'dashboard/_profile' ); ?>
			<?php echo $this->element( 'dashboard/_links' ); ?>
		</div>
        <div class="col-md-10">
        	<?php echo $this->element( 'dashboard/_safety' ); ?>
    		<?php echo $this->element( 'dashboard/_accident' ); ?>
			<?php echo $this->element( 'dashboard/_trainings' ); ?>
			<?php echo $this->element( 'dashboard/_tests' ); ?>
			<?php echo $this->element( 'dashboard/_improvements' ); ?>
        	<?php echo $this->element( 'dashboard/_bingo' ); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
    jQuery(document).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $.ajax({
            type: 'POST',
            url: '/Trainings/userRequiredTraining/1.json',
            dataType: "html",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                $('#LoadingDiv').show();
            },
            success: function(response) {
                console.log(response);
                $('#trainingTable').html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function(){
                $('#LoadingDiv').hide();
            },
        });
    });
</script>