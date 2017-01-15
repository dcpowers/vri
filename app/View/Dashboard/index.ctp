<div class="dashboard index">
    <?php echo $this->element( 'dashboard/_safety' ); ?>

    <div class="row">
        <div class="col-md-3">
            <?php echo $this->element( 'dashboard/_profile' ); ?>
		</div>
        <div class="col-md-9">
			<?php echo $this->element( 'dashboard/_accident' ); ?>
            <?php echo $this->element( 'dashboard/_trainings' ); ?>
            <?php echo $this->element( 'dashboard/_improvements' ); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
    });
</script>