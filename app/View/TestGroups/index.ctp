<?php
    #pr($data);
    #exit;

?>

<div class="container">
    <ul class="list-inline" >
        <li class="pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-arrow-left fa-fw fa-lg text-success"></i><span class="text text-success">Back To Testing</span>',
                array('controller'=>'Tests', 'action'=>'index'),
                array('escape'=>false, 'class'=>'btn btn-default btn-xs pull-right')
            );
            ?>
        </li>
    </ul>
    <h2 class="title"><i class="fa fa-clipboard"></i><span class="text"><?php echo __('Creation Tool:');?><small>Assessments/Surveys/Evaluations</small></h2>
    <hr class="solidOrange" />

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4" id="menu">
            <?=RecursiveCategories($data) ?>
        </div>

        <div class="col-md-8 alert" role="alert" id='message' style="display: none;"></div>
        <div class="col-md-8" id="editContent" style="display: none;">
            <div class="menuBar pull-right">
                <div class="btn-toolbar" role="group" aria-label="Menu Bar">
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Add Category',
                        array('controller'=>'TestGroups', 'action'=>'addSub', 'member'=>true),
                        array('escape' => false, 'id'=>'addCatButton', 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs','style'=>'display:none;'));
                    ?>
                    </div>
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Add Question',
                        array('controller'=>'TestGroups', 'action'=>'addSub', 'member'=>true),
                        array('escape' => false, 'id'=>'addQButton', 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs','style'=>'display:none;'));
                    ?>
                    </div>
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Add Answers',
                        array('controller'=>'TestGroups', 'action'=>'addSub', 'member'=>true),
                        array('escape' => false, 'id'=>'addAButton', 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs','style'=>'display:none;'));
                    ?>
                    </div>
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-arrow-circle-o-down"></i> Move down',
                        array('controller'=>'TestGroups', 'action'=>'moveDown', 'member'=>true),
                        array('escape' => false, 'id'=>'moveDownButton', 'class'=>'btn btn-primary btn-xs movedown','style'=>'display:none;') );
                    ?>
                    </div>
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-arrow-circle-o-up"></i> Move up',
                        array('controller'=>'TestGroups', 'action'=>'moveup', 'member'=>true),
                        array('escape' => false, 'id'=>'moveUpButton', 'class'=>'btn btn-primary btn-xs moveup', 'style'=>'display:none;','style'=>'display:none;' ));
                    ?>
                    </div>
                    <div class="btn-group" role="group" aria-label="Menu Bar">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-trash"></i> Delete',
                        array('controller'=>'TestGroups', 'action'=>'delete', 'member'=>true),
                        array('escape' => false, 'id'=>'deleteButton', 'class'=>'btn btn-danger btn-xs','style'=>'display:none;'),
                        "Are you Sure You Want To Delete?"
                    );
                    ?>
                    </div>

                </div>
            </div>

            <div class="clearfix"></div>
            <?php
            echo $this->Form->create('Job', array(
                //'url' => array('controller'=>'jobs', 'action'=>'add', 'admin'=>true),
                'role'=>'form',
                //'class'=>'form-horizontal',
                'inputDefaults' => array(
                    'label' => false,
                    'div' => false,
                    'class'=>'form-control',
                    'error'=>false
                )
            ));

            echo $this->Form->input('id', array (
                'type'=>'hidden',
                'id'=>'id'
            ));

            echo $this->Form->input('category_type', array (
                'type'=>'hidden',
                'id'=>'category_type'
            ));
            ?>
            <div class="form-group">
                <label class="control-label" for="name">Title/Name:</label>
                <?php echo $this->Form->input('name', array (
                    'type'=>'text',
                    'placeholder' => 'Name',
                    'id'=>'name',
                    'class'=>'form-control'
                ));?>
                <label class="error" for="name" id="name_error">This field is required.</label>
            </div>

            <div class="descriptiveClass" style="display: none;">
                <div class="form-group">
                    <label class="control-label" for="name">Description:</label>
                    <?php echo $this->Form->input('description', array (
                        'type'=>'textarea',
                        'placeholder' => 'Description',
                        'id'=>'description',
                        'class'=>'form-control'
                    ));?>
                </div>

                <div class="form-group">
                    <label class="control-label" for="name">Report:</label>
                    <?php echo $this->Form->input('report_introduction', array (
                        'type'=>'textarea',
                        'placeholder' => 'Report Introduction',
                        'id'=>'report_introduction',
                        'class'=>'form-control'
                    ));?>
                </div>
            </div>

            <div class="answerClass" style="display: none;">
                <div class="form-group">
                    <label class="control-label" for="name">Answer Value:</label>
                    <?php echo $this->Form->input('description', array (
                        'type'=>'text',
                        'placeholder' => 'Answer Value',
                        'id'=>'answerValue',
                        'class'=>'form-control'
                    ));?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary button btn-sm')); ?>
            </div>

            <?php echo $this->Form->end();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $('#menu').easytree();
        $("span").find('a').addClass("name");

        $('.name').click(function() {
            var url = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'details', 'member'=>true ));?>';
            var addUrl = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'addSub', 'member'=>true ));?>';
            var moveDownUrl = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'movedown', 'member'=>true ));?>';
            var moveUpUrl = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'moveup', 'member'=>true ));?>';
            var deleteUrl = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'delete', 'member'=>true ));?>';

            var id = $(this).parent().parent().attr('id');
            var targeturl = url + '?id=' + id;

            $.ajax({
                type: 'get',
                url: targeturl,
                success: function(response) {
                    if (response) {

                        $('#message').hide();
                        $('#editContent').show();

                        var res = $.parseJSON(response);
                        console.log(res.TestGroup);

                        $("#id").val(res.TestGroup.id);
                        $("#name").val(res.TestGroup.name);

                        $("#category_type").val(res.TestGroup.category_type);

                        if ($.inArray(res.TestGroup.category_type, ['1', '2']) >= 0) {
                            $('.descriptiveClass').show();
                            $("#description").val(res.TestGroup.description);
                            $("#report_introduction").val(res.TestGroup.report_introduction);
                        }else if (!$.inArray(res.TestGroup.category_type, ['1', '2']) >= 0) {
                            $('.descriptiveClass').hide();
                        }

                        if ($.inArray(res.TestGroup.category_type, ['4', '5']) >= 0) {
                            $('.answerClass').show();
                            $("#answerValue").val(res.TestGroup.description);
                        }else if (!$.inArray(res.TestGroup.category_type, ['4', '5']) >= 0) {
                            $('.answerClass').hide();
                        }

                        $('#moveDownButton').hide();
                        $('#moveUpButton').hide();
                        $('#addCatButton').hide();
                        $('#addAButton').hide();
                        $('#addQButton').hide();
                        $('#deleteButton').hide();

                        if ($.inArray(res.TestGroup.category_type, ['1']) >= 0) {
                            $('#moveDownButton').show();
                            $('#addCatButton').show();
                            $('#deleteButton').show();
                        }

                        if ($.inArray(res.TestGroup.category_type, ['2']) >= 0) {
                            $('#moveDownButton').show();
                            $('#moveUpButton').show();
                            $('#addCatButton').show();
                            $('#addQButton').show();
                            $('#deleteButton').show();
                        }

                        if ($.inArray(res.TestGroup.category_type, ['3']) >= 0) {
                            $('#moveDownButton').show();
                            $('#moveUpButton').show();
                            $('#addAButton').show();
                            $('#deleteButton').show();
                        }

                        if ($.inArray(res.TestGroup.category_type, ['4', '5']) >= 0) {
                            $('#moveDownButton').show();
                            $('#moveUpButton').show();
                            $('#deleteButton').show();
                        }

                        $("#moveDownButton").attr("href", moveDownUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id);
                        $("#addCatButton").attr("href", addUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id + "/2");
                        $("#addQButton").attr("href", addUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id + "/3");
                        $("#addAButton").attr("href", addUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id + "/4");
                        $("#moveUpButton").attr("href", moveUpUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id);

                        $("#deleteButton").attr("href", deleteUrl + "/" + res.TestGroup.id + "/" + res.TestGroup.Test_id);
                    }


                },

            });
        });
        $('.error').hide();
        $(".button").click(function() {
            var edit_name_url = '<?php echo Router::url( array('controller'=>'TestGroups', 'action'=>'update', 'member'=>true ));?>';

            var id = $("input#id").val();
            var name = $("input#name").val();
            var category_type = $("input#category_type").val();

            if (name == "") {
                $("label#name_error").show();
                $("input#name").focus();
                return false;
            }

            if (category_type == 4) {
                var description = $("#answerValue").val();
                var introduction = $("#answerValue").val();
            }else{
                var description = $("#description").val();
                var report_introduction = $("#report_introduction").val();
            }

            if (category_type == "") {
                $("label#category_type_error").show();
                $("input#category_type").focus();
                return false;
            }

            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: {
                    Test: {
                        id: id,
                        name: name,
                        description: description,
                        introduction: introduction,
                        category_type: category_type,
                        report_introduction: report_introduction
                    }
                },

                success: function(s) {
                    $("#" + id + "").children().children("a").text(name);

                    $('#editContent').fadeOut( 800 );
                    $('#moveDownButton').fadeOut( 800 );
                    $('#moveUpButton').fadeOut( 800 );
                    $('#addButton').fadeOut( 800 );
                    $('#deleteButton').fadeOut( 800 );

                    $('#message').addClass("alert-success")
                    $('#message').html("<h2>Update Successful!</h2>")
                    $('#message').fadeIn(400)
                },
                error: function(e){
                    console.log(e.responseText),
                    alert('Error Processing your Request!!');
                }

            });
        });

        $("#moveDownButton").click(function() {
            $('#editContent').fadeOut( 800 );
            $('#moveDownButton').fadeOut( 800 );
            $('#moveUpButton').fadeOut( 800 );
            $('#addButton').fadeOut( 800 );
            $('#deleteButton').fadeOut( 800 );
        });

        $("#moveUpButton").click(function() {
            $('#editContent').fadeOut( 800 );
            $('#moveDownButton').fadeOut( 800 );
            $('#moveUpButton').fadeOut( 800 );
            $('#addButton').fadeOut( 800 );
            $('#deleteButton').fadeOut( 800 );
        });




    });
</script>

<?php
function RecursiveCategories($array) {
    if (count($array)) {
        ?>
        <ul style="display:block;">
        <?php
        foreach ($array as $key=>$vals) {
            if (count($vals['children'])) {
                $class="isFolder isExpanded";
            }else{
                $class="isExpanded";
            }

            ?>
            <li id="<?=$vals['TestGroup']['id']?>" class="<?=$class?>">
                <a href="#" class="name" id="<?=$vals['TestGroup']['id']?>"><?=$vals['TestGroup']['name']?></a>

                <?php
                if (count($vals['children'])) {
                    RecursiveCategories($vals['children']);
                }
                ?>

            </li>
            <?php
        }
        ?>
        </ul>
        <?php
    }
}

?>
