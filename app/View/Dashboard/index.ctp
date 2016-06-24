<div class="dashboard index">
    <?php echo $this->element( 'dashboard/_safety' ); ?>
    
    <div class="row">
        <div class="col-lg-7">
            <?php echo $this->element( 'dashboard/_trainings' ); ?>
        </div>
        <div class="col-lg-5">
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