<div class="dashboard index">
    <?php echo $this->element( 'dashboard/_safety' ); ?>
    
    <div class="row">
        <div class="col-md-2">
            Profile Info
        </div>
        <div class="col-md-7">
            <?php echo $this->element( 'dashboard/_trainings' ); ?>
            <?php echo $this->element( 'dashboard/_improvements' ); ?>
        </div>
        <div class="col-md-3">
            
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