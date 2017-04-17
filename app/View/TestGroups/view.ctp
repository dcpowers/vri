<?php
    $this->Html->addCrumb('Tests', array('controller' => 'tests', 'action' => 'index', 'admin'=>true));   
    $this->Html->addCrumb('View', array('controller' => 'tests', 'action' => 'view', $data[0]['Test']['id'], 'admin'=>true));   
    
    $this->Html->script('plugins/bootstrap-editable/jquery.mockjax.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/moment/moment.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-datepicker/bootstrap-datepicker.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-editable/bootstrap-editable.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/typeahead/typeahead.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/typeahead/typeaheadjs.1.5.1.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/select2/select2.min.js', array('block' => 'scriptsBottom'));
    //$this->Html->script('plugins/bootstrap-editable/address.custom.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-editable/demo-mock.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/select2/select2.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('queen-elements.js', array('block' => 'scriptsBottom'));
?>
<style type="text/css">
    .row-padded {
        padding-bottom: 10px;
        border-bottom: 1px #E4E4E4 solid;
}
</style>
<h1 class="title" id="<?=$data[0]['Test']['id']?>">
    <?=$data[0]['Test']['name']?>
</h1>
<div class="container">
    <div class="row">
        <h1>Groups</h1>
        
        <div class="widget">
            <div class="widget-header clearfix">
                <h3><i class="icon ion-ios7-browsers-outline"></i> <span>Assessments/Tests</span></h3>
            </div>
            
            <div class="widget-content">
                <?php
                foreach($data as $id=>$test){
                    ?>
                    <div class="row row-padded">
                        <div class="col-md-12" style="padding-left: -5px;">
                            <?php
                            $delete_icon = '<span class="badge element-bg-color-blue"><i class="icon ion-android-trash"></i></span>';
                                
                            if($test['Test']['is_active'] == 1){
                                $icon = '<span class="badge element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                $accessCode = 0;
                            }else{
                                $icon = '<span class="badge element-bg-color-red"><i class="icon ion-close"></i></span>';
                                $action = '';
                                $accessCode = 1;
                            }
                            ?>
                            <div class="col-md-10">
                                <?php
                                echo $this->Html->link(
                                    $test['Test']['name'], 
                                        array(
                                            'action'=>'view', 
                                            $test['Test']['id']
                                        )
                                    );
                                ?>
                            </div>
                            <div class="col-md-1">
                                <?php
                                echo $this->Html->link( 
                                    $icon, 
                                    array( 
                                        'controller'=>'tests',
                                        'action'=>'status', 
                                        $test['Test']['id'], 
                                        $accessCode
                                    ), 
                                    array('escape'=>false, 'class'=>'btn-switch') 
                                );
                                ?>
                            </div>
                            <div class="col-md-1">
                                <?php
                                echo $this->Html->link(
                                    $delete_icon,
                                    '#',
                                    array(
                                        'escape'=>false,
                                        'class'=>'confirm_delete',
                                        'id'=>$test['Test']['id']
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach($test['children'] as $category){
                        ?>
                        <div class="row row-padded">
                            <div class="col-md-12">
                                <?php
                                $delete_icon = '<span class="badge element-bg-color-blue"><i class="icon ion-android-trash"></i></span>';
                                    
                                if($category['Test']['is_active'] == 1){
                                    $icon = '<span class="badge element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                    $accessCode = 0;
                                }else{
                                    $icon = '<span class="badge element-bg-color-red"><i class="icon ion-close"></i></span>';
                                    $action = '';
                                    $accessCode = 1;
                                }
                                ?>
                                <div class="col-md-10" style="padding-left: 35px;">
                                    <?php
                                    echo $this->Html->link(
                                        $category['Test']['name'], 
                                            array(
                                                'action'=>'view', 
                                                $category['Test']['id']
                                            )
                                        );
                                    ?>
                                </div>
                                <div class="col-md-1">
                                    <?php
                                    echo $this->Html->link( 
                                        $icon, 
                                        array( 
                                            'controller'=>'tests',
                                            'action'=>'status', 
                                            $category['Test']['id'], 
                                            $accessCode
                                        ), 
                                        array('escape'=>false, 'class'=>'btn-switch') 
                                    );
                                    ?>
                                </div>
                                <div class="col-md-1">
                                    <?php
                                    echo $this->Html->link(
                                        $delete_icon,
                                        '#',
                                        array(
                                            'escape'=>false,
                                            'class'=>'confirm_delete',
                                            'id'=>$category['Test']['id']
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        foreach($category['children'] as $subCategory){
                            if(empty($subCategory['children'])) { $is_question = 1; }else{ $is_question = 0; }
                            ?>
                            <div class="row row-padded">
                                <div class="col-md-12">
                                    <?php
                                    $delete_icon = '<span class="badge element-bg-color-blue"><i class="icon ion-android-trash"></i></span>';
                                        
                                    if($subCategory['Test']['is_active'] == 1){
                                        $icon = '<span class="badge element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                        $accessCode = 0;
                                    }else{
                                        $icon = '<span class="badge element-bg-color-red"><i class="icon ion-close"></i></span>';
                                        $action = '';
                                        $accessCode = 1;
                                    }
                                    ?>
                                    <div class="col-md-10" style="padding-left: 50px;">
                                        <?php
                                        echo $this->Html->link(
                                            $subCategory['Test']['name'], 
                                                array(
                                                    'action'=>'view', 
                                                    $subCategory['Test']['id']
                                                )
                                            );
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php
                                        echo $this->Html->link( 
                                            $icon, 
                                            array( 
                                                'controller'=>'tests',
                                                'action'=>'status', 
                                                $subCategory['Test']['id'], 
                                                $accessCode
                                            ), 
                                            array('escape'=>false, 'class'=>'btn-switch') 
                                        );
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php
                                        echo $this->Html->link(
                                            $delete_icon,
                                            '#',
                                            array(
                                                'escape'=>false,
                                                'class'=>'confirm_delete',
                                                'id'=>$subCategory['Test']['id']
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            foreach($subCategory['children'] as $subSubCategory){
                                ?>
                                <div class="row row-padded">
                                    <div class="col-md-12">
                                        <?php
                                        $delete_icon = '<span class="badge element-bg-color-blue"><i class="icon ion-android-trash"></i></span>';
                                            
                                        if($subSubCategory['Test']['is_active'] == 1){
                                            $icon = '<span class="badge element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                            $accessCode = 0;
                                        }else{
                                            $icon = '<span class="badge element-bg-color-red"><i class="icon ion-close"></i></span>';
                                            $action = '';
                                            $accessCode = 1;
                                        }
                                        ?>
                                        <div class="col-md-10" style="padding-left: 65px;">
                                            <?php
                                            echo $this->Html->link(
                                                $subSubCategory['Test']['name'], 
                                                    array(
                                                        'action'=>'view', 
                                                        $subSubCategory['Test']['id']
                                                    )
                                                );
                                            ?>
                                        </div>
                                        <div class="col-md-1">
                                            <?php
                                            echo $this->Html->link( 
                                                $icon, 
                                                array( 
                                                    'controller'=>'tests',
                                                    'action'=>'status', 
                                                    $subSubCategory['Test']['id'], 
                                                    $accessCode
                                                ), 
                                                array('escape'=>false, 'class'=>'btn-switch') 
                                            );
                                            ?>
                                        </div>
                                        <div class="col-md-1">
                                            <?php
                                            echo $this->Html->link(
                                                $delete_icon,
                                                '#',
                                                array(
                                                    'escape'=>false,
                                                    'class'=>'confirm_delete',
                                                    'id'=>$subSubCategory['Test']['id']
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if(!empty($subSubCategory['children'])){
                                    foreach($subSubCategory['children'] as $last){
                                        ?>
                                        <div class="row row-padded">
                                            <div class="col-md-12">
                                                <?php
                                                $delete_icon = '<span class="badge element-bg-color-blue"><i class="icon ion-android-trash"></i></span>';
                                                    
                                                if($last['Test']['is_active'] == 1){
                                                    $icon = '<span class="badge element-bg-color-green"><i class="icon ion-checkmark"></i></span>';
                                                    $accessCode = 0;
                                                }else{
                                                    $icon = '<span class="badge element-bg-color-red"><i class="icon ion-close"></i></span>';
                                                    $action = '';
                                                    $accessCode = 1;
                                                }
                                                ?>
                                                <div class="col-md-10" style="padding-left: 80px;">
                                                    <?php
                                                    echo $this->Html->link(
                                                        $last['Test']['name'], 
                                                            array(
                                                                'action'=>'view', 
                                                                $last['Test']['id']
                                                            )
                                                        );
                                                    ?>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    echo $this->Html->link( 
                                                        $icon, 
                                                        array( 
                                                            'controller'=>'tests',
                                                            'action'=>'status', 
                                                            $last['Test']['id'], 
                                                            $accessCode
                                                        ), 
                                                        array('escape'=>false, 'class'=>'btn-switch') 
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    echo $this->Html->link(
                                                        $delete_icon,
                                                        '#',
                                                        array(
                                                            'escape'=>false,
                                                            'class'=>'confirm_delete',
                                                            'id'=>$last['Test']['id']
                                                        )
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
            <div class="widget-footer text-right"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        var edit_name_url = '<?php echo Router::url( array('controller'=>'groups', 'action'=>'edit', 'admin'=>true ));?>';
        var access_name_url = '<?php echo Router::url( array('controller'=>'applications', 'action'=>'access', 'admin'=>true ));?>';
        
        $.fn.editable.defaults.mode = 'popup';
        $('.groupName').editable({
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $(document).on('click','.editable-submit',function(){
            var x = $(this).closest('.wrap').attr('id');
            var n = $('.input-sm').val();
            var s = $('.input-large').val();
            var newId = '#' + x;
            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: { Group: { id: x, name: n, supervisor_id: s }},
                success: function(s){
                    if(s == 'status'){
                        $(z).html(y);
                    }
                    
                    if(s == 'error') {
                        alert('Error Processing your Request!');
                    }
                },
                
                error: function(e){
                    alert('Error Processing your Request!!');
                }
            });
        });
        
        $('.btn-switch').on('click', function() {
            var row = $(this).parents('div');
            var url = $(this).attr('href');
            var pathArray = url.split( '/' );
            var newPath = "/" + pathArray[1] + "/" + pathArray[2] + "/" + pathArray[3] + "/" + pathArray[4] + "/" + pathArray[5] + "/" + pathArray[6] + "/";
            var updateClass = $(this).parents('span').attr('class');
            var newClass = '.' + updateClass + '_' + pathArray[8];
            $.ajax( {
                type:'get',
                url: $(this).attr('href'),
                dataType: 'json',
                success: function(response) {
                    
                    if(response.sucess == false) {
                        alert('There was an error');
                    }
                    
                    if(response.sucess == true) {
                        if(response.msg == 1){
                            $( newClass ).removeClass('badge-ghost').addClass('element-bg-color-green');
                            $( newClass ).closest('a').attr("href", newPath + response.enable + '/' + pathArray[8]);
                            
                            if(updateClass == 'comp'){
                                row.find('i').removeClass('ion-close').addClass('ion-checkmark');
                            }
                        }
                        
                        if(response.msg == 2){
                            $( newClass ).removeClass('element-bg-color-green').addClass('badge-ghost');
                            $( newClass ).closest('a').attr("href", newPath + response.enable + '/' + pathArray[8]);
                            
                            if(updateClass == 'comp'){
                                row.find('i').removeClass('ion-checkmark').addClass('ion-close');
                            }
                        }
                    }
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                }
            });
            
            return false;
        });
        
        if($('.confirm_delete').length) {
            // add click handler
            $('.confirm_delete').click(function(){
                // ask for confirmation
                var result = confirm('Are you sure you want to delete this?');
                
                // show loading image
                $('.ajax_loader').show();
                $('#flashMessage').fadeOut();
                
                // get parent row
                var row = $(this).parents('div');
                var p = $(this).closest('.confirm_delete').attr('id');
                alert(p);
                // do ajax request
                if(result) {
                    $.ajax({
                        type:"post",
                        url:'/admin/tests/delete/' + p,
                        dataType: "json",
                        success:function(response){
                            // hide loading image
                            $('.ajax_loader').hide();
                            if(response.sucess == false) {
                                alert('There was an error');
                            }
                        
                            // hide table row on success
                            if(response.sucess == true) {
                                row.fadeOut();
                            }
                        }
                    });
                }
            return false;
            });
        }
    });
</script>
