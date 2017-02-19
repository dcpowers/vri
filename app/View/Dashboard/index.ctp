<div class="dashboard index">
    <?php echo $this->element( 'dashboard/_safety' ); ?>

    <div class="row">
        <div class="col-md-2">
            <?php echo $this->element( 'dashboard/_profile' ); ?>
			<?php echo $this->element( 'dashboard/_links' ); ?>
		</div>
        <div class="col-md-10">
			<?php echo $this->element( 'dashboard/_accident' ); ?>
			<?php echo $this->element( 'dashboard/_trainings' ); ?>
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
    });
</script>