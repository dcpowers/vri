<!-- start: AlertBox -->
<div class="text-center alert alert-dismissible <?php echo h($params['class']) ?> role="alert">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <?php echo h($message); ?>
</div>

<!-- end: AlertBox -->