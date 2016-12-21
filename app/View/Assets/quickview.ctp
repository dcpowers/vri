<div class="modal-header modal-header-primary">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Asset Information: '); ?></h2>
</div>
<div class="modal-body">


    <div class="row">
        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Asset:</dt>
                <dd><?=$asset['Asset']['asset']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Tag:</dt>
                <dd><?=$asset['Asset']['tag_number']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Status:</dt>
                <dd><?=$asset['Status']['name']?></dd>
            </dl>
        </div>
    </div>

    <div class="row" style="clear: both;">
        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Asset Type:</dt>
                <dd><?=$asset['AssetType']['name']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Manufacturer:</dt>
                <dd><?=$asset['Manufacturer']['name']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Vendor:</dt>
                <dd><?=$asset['Vendor']['name']?></dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Modal:</dt>
                <dd><?=$asset['Asset']['model']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Version:</dt>
                <dd><?=$asset['Asset']['version']?></dd>
            </dl>
        </div>

        <div class="col-md-4">
            <dl class="dl-horizontal">
                <dt>Purchase Date:</dt>
                <dd><?=$asset['Asset']['purchase_date']?></dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <dl class="dl-horizontal">
                <dt>Account:</dt>
                <dd><?=$asset['Account']['name']?></dd>
            </dl>
        </div>

        <div class="col-md-8">
            <dl class="dl-horizontal">
                <dt>Assigned To:</dt>
                <dd><?=$asset['AssignedTo']['first_name']?> <?=$asset['AssignedTo']['last_name']?></dd>
            </dl>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="btn btn-default pull-left" data-dismiss="modal">Close</a>
</div>
<script type="text/javascript">
    jQuery(window).ready( function($) {
    });
</script>