
<div class="container">
    <h2 class="title"><i class="fa fa-list-ul"></i> <span class="text">Job Titles</span></h2>
    <hr class="solidOrange" />

    <div class="pull-left" style="padding: 10px 0px;">
        <div data-placement="top" data-toggle="tooltip" title="Add a New Job Title">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-plus"></i> Add Job Title',
                array('controller'=>'jobs', 'action'=>'add', 'member'=>true),
                array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
            );
            ?>
        </div>
    </div>

    <table class="table table-hover table-condensed">
        <thead>
            <tr class="tr-heading">
                <th style="width: 20%;">Job Title</th>
                <th style="width: 50%;">Description</th>
                <th style="width: 20%;">SOC Code</th>
                <th style="width: 10%;"></th>
            </tr>
        </thead>
        <tbody>
            <?php
			#pr($content);
            foreach($content as $key=>$job){

                ?>
                <tr>
                    <td><?=$job['JobTitle']['name']?></td>
                    <td><?=$job['JobTitle']['description']?></td>
                    <td><?=$job['JobTitle']['soc_code']?></td>
                    <td>
                        <ul class="list-inline">
                            <li>
                                <span class="btn-group-xs" data-placement="top" data-toggle="tooltip" title="Edit: <?=$job['name']?>">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-pencil-square-o"></i>',
                                        array('controller'=>'jobs', 'action'=>'edit', $job['JobTitle']['id']),
                                        array('escape'=>false)
                                    );
                                    ?>
                                </span>
                            </li>
                            <li>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-trash"></i>',
                                    array('controller'=>'jobs', 'action'=>'confirm', $job['JobTitle']['id'], 'jobTitle'),
                                    array(
                                        'escape'=>false,
                                        'id'=>$key,
                                        'data-toggle'=>'tooltip',
                                        'data-placement'=>'top',
                                        'title'=>'Delete: '.$job['JobTitle']['name'],
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#myModal',
                                    )
                                );
                                ?>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php
            }
        ?>
        </tbody>
    </table>

</div>
<!-- Normal Model -->
<div class="modal bootstrap-dialog type-primary fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
<?php echo $this->Form->end();?>
<script language="JavaScript">
    jQuery(document).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
    });
</script>