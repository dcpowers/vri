<?php
    //Audit::log('Your message' ); Send info to audit log table
    $this->Html->script('Training.video.js', array('block' => 'scriptsTop'));
    //$this->Html->script('Training.bootstrap-lightbox.js', array('block' => 'scriptsTop'));
    
    $this->Html->css('Training.video-js.css', '', array('block' => 'css') );
    //$this->Html->css('Training.bootstrap-lightbox.css', '', array('block' => 'css') );
if ( empty($class) ) { $class = ''; }
if ( empty($style) ) { $style = ''; }
    
?>
<div class="row two-column with-right-sidebar">
    <div class="obts view span12">
        <h2>
            <?php echo 'TRN-' . str_pad( $train['Training']['id'], 4, 0, STR_PAD_LEFT )?> <?php echo $train['Training']['title'];?>
            <small>
                Rev# <?php echo $train['Training']['revision'];?>,
                Revised: <?php echo date( APP_DATE_FORMAT, strtotime( $train['Training']['revised_on'] ));?>
                <?php if (!empty( $train['Training']['author_id'])) { ?>,
                    Author: <?php //echo 'TODO Add Author';?>
                <?php } ?>
            </small>
        </h2>
        <?php if ( !empty( $train['Training']['is_retired'] ) ) { ?>
            <div class="alert alert-error"><strong>Note:</strong> This HSE Training was retired on <?php echo date( APP_DATE_FORMAT, strtotime( $train['Training']['retired_on'] ) ); ?></div>
        <?php } ?>
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#report" data-toggle="tab">Associates</a></li>
                <li><a href="#quiz" data-toggle="tab">Content/Quiz</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="report">
                    
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th style="width:75px">id</th>
                                <th>Associate</th>
                                <th>Completed Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                
                            <?php foreach( $train['TrainingRecord'] as $record_info ) { ?>
                            <tr>
                                <td>
                                    <?php echo $record_info['id']; ?>
                                </td>
                                
                                <td>
                                    <?php echo ucwords(strtolower($record_info['Associate']['first_name'] .' '. $record_info['Associate']['last_name'])); ?>
                                </td>
                                
                                <td>
                                    <?php  
                                    if(IS_NULL($record_info['completed']))
                                    {
                                        echo "In Progress";
                                    }
                                    else
                                    {
                                        echo date( APP_DATE_FORMAT, strtotime( $record_info['completed'] )); 
                                    }    
                                    ?>
                                    
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="tab-pane hide" id="quiz">
                    
                    <?php echo $this->Form->create('TrainingQuizzes'); ?>  
                    
                    <?php foreach( $train['TrainingQuizzes'] as $record_info ) { ?>
                    
                        <fieldset class="InspectionAreaFieldset InspectionArea<?php echo $train['Training']['id'];?> <?php echo $class; ?>" style="<?php echo $style;?>">
                            <legend>
                                <div class="areaEditButtons">
                                    <?php echo $this->Html->link( 
                                        '<i class="icon-plus"></i> Add Item', 
                                        array( 'controller'=>'inspection_items', 'action'=>'add', $train['Training']['id'] ),
                                        array('class'=>'addItemButton btn btn-mini', 'escape'=>false, 'rel'=>$train['Training']['id'] ) 
                                    ); ?>
                                
                                    <?php echo $this->Html->link( '<i class="icon-edit"></i>', array('controller'=>'inspection_areas', 'action'=>'edit', $train['Training']['id'] ) , array('class'=>'editAreaButton btn btn-mini', 'escape'=>false ) ); ?>
                                
                                    <?php echo $this->Html->link( 
                                        '<i class="icon-minus-sign icon-white"></i>', 
                                        array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                        array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                    ); ?>
                            
                                    <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                    <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                </div>
                                <?php echo $record_info['question'] ?>
                             </legend>
                            <!--
                            <?php echo $this->Form->input('InspectionArea.'.$area['id'].'.id',  array('value'=> $area['id'], 'type'=>'hidden' )); ?>
                            <?php echo $this->Form->input('InspectionArea.'.$area['id'].'.name', array('value'=> $area['name'], 'type'=>'hidden' )); ?>
                            <?php echo $this->Form->input('InspectionArea.'.$area['id'].'.group_id', array('value'=> $area['group_id'], 'type'=>'hidden' )); ?>
                            <?php echo $this->Form->input('InspectionArea.'.$area['id'].'.order', array('value'=> $area['order'], 'type'=>'hidden' )); ?>
                            -->    
                            <table class="InspectionAreaItems table table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td><?php echo $record_info['answer_a'] ?></td>
                                        <td class="itemEditButtons" style="width: 95px;">
                                                <?php echo $this->Html->link( 
                                                    '<i class="icon-minus-sign icon-white"></i>', 
                                                    array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                                    array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                                ); ?>
                            
                                                <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                                <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $record_info['answer_b'] ?></td>
                                        <td class="itemEditButtons" style="width: 95px;">
                                                <?php echo $this->Html->link( 
                                                    '<i class="icon-minus-sign icon-white"></i>', 
                                                    array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                                    array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                                ); ?>
                            
                                                <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                                <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                            
                                        </td>
                                    </tr>
                                    
                                    <?php if(!empty($record_info['answer_c'])) { ?>
                                        <tr>
                                        <td><?php echo $record_info['answer_c'] ?></td>
                                        <td class="itemEditButtons" style="width: 95px;">
                                                <?php echo $this->Html->link( 
                                                    '<i class="icon-minus-sign icon-white"></i>', 
                                                    array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                                    array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                                ); ?>
                            
                                                <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                                <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    
                                    <?php if(!empty($record_info['answer_d'])) { ?>
                                        <tr>
                                        <td><?php echo $record_info['answer_d'] ?></td>
                                        <td class="itemEditButtons" style="width: 95px;">
                                                <?php echo $this->Html->link( 
                                                    '<i class="icon-minus-sign icon-white"></i>', 
                                                    array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                                    array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                                ); ?>
                            
                                                <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                                <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    
                                    <?php if(!empty($record_info['answer_e'])) { ?>
                                        <tr>
                                        <td><?php echo $record_info['answer_e'] ?></td>
                                        <td class="itemEditButtons" style="width: 95px;">
                                                <?php echo $this->Html->link( 
                                                    '<i class="icon-minus-sign icon-white"></i>', 
                                                    array('controller'=>'inspection_areas', 'action'=>'delete', $train['Training']['id'] ), 
                                                    array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                                ); ?>
                            
                                                <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
        
                                                <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </fieldset>
                    <?php } ?>
                    <div class="submit">
                        <?php echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                        <?php echo $this->Html->link( __('Cancel'), array('controller'=>'trainings', 'action'=>'index' ), array('class'=>'btn')  ); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <div class="submit">
            <?php 
            //Only show edit button to SuperAdmin
            //TODO Add additional AuthRoles 'OBT Admin', 'OBT Editor', and test if associate is obt author
            $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
            if ( in_array( 'SuperAdmin', $role_names ) OR in_array('Safety Supervisor', $role_names)
                OR $train['Training']['author_id'] == AuthComponent::user('id') ) {   
                echo $this->Html->link(__('Edit'), array('action'=>'edit', $train['Training']['id'] ), array('class'=>'btn') );
            } 
            ?>
        </div>
    </div>

    <!--<?php echo $this->element( 'sidebar')?>-->
</div>
<script language="JavaScript">
jQuery(document).ready( function($) {
    
    $('#RosterQ').focus();
    
    $('#RosterQ').on( 'keyup', function() {
        var query_input;
        query_input = $(this);
        q = query_input.val();

        delay(function(){
            $(this).addClass('waiting');
            visitor_search( q );
        }, 450 );
        
    });
    
    $('#SearchButton').on( 'click', function() {
        visitor_search( q );
        return false;            
    });
    
    $('#RosterForm').on( 'submit', function() {
        $('#SearchButton').trigger('click');
        return false;
    });


    
    if ( $('#RosterQ').val().length > 2 ) {
        $('#RosterQ').trigger('keyup');
    }
    
    //Cancel Click on Sort column headers
    $('th a').live('click', function ( event ) {

        return false;
    });

});

function visitor_search( q ) {
    $('#RosterQ').addClass('waiting');
    $('.results').load( '/safetrain/training/trainingss/roster/q:' + q, function() {
        $('#RosterQ').removeClass('waiting');
    });
}


    $('div.modal').modal({show:false});
    
    //Show the Add AreaModal
    $('a.addAreaButton').click( function() {
        $('#AjaxModal').load( $(this).attr('href') );
        $('#AjaxModal').modal('show');
        return false;
    });

    //Show the Edit AreaModal
    $('.btn-area-edit').live('click', function( ) {
        $('#AjaxModal').load( $(this).attr('href') );
        $('#AjaxModal').modal('show');
        return false;     
    });
    
    //Show the Add ItemModal
    $('a.addItemButton').live( 'click', function() {

        areaEl = $(this).closest('.InspectionAreaFieldset');  
        lastAreaItemEl = areaEl.find('.InspectionAreaItem:last');  

        $.ajax({
            url: '/safetrain/inspections/inspection_items/add',
            type: "post",
            dataType: "json",
            data: { InspectionItem: { inspection_area_id: $(this).attr('rel') }},
            success: function( objResponse ) {
                $.get( '/safetrain/inspections/inspection_items/view_form_item/' +  objResponse.data.id , function(data){ 
                    $(data).insertAfter( lastAreaItemEl );
                    areaEl.find('.InspectionAreaItem.empty-item').remove();
                });
            },
            error: function( objResponse ) {
                alert('Error Creating Item');
            }
        
        });
          
        //'#AjaxModal').load( $(this).attr('href') );
        //'#AjaxModal').modal('show');
        return false;     

    });

    $('#InspectionAreaAddForm').live('submit', function() {

        lastAreaOrder = $('.InspectionAreaFieldset:last input[id^="InspectionArea"][id$="Order"]').val();
        lastAreaOrder++;    
        $('#InspectionAreaOrder').val( lastAreaOrder );
        
        $.ajax({
            url: '/safetrain/inspections/inspection_areas/add',
            type: "post",
            dataType: "json",
            async: false,
            data: $( this ).serialize(),
            success: function( objResponse ) {
                $.get( '/safetrain/inspections/inspection_areas/view_form_area/' +  objResponse.data.id , function(data){ 
                    $(data).insertAfter( ".InspectionAreaFieldset:last" );
                });
            },
            error: function( objResponse ) {
                alert('Error Creating Area');
            }
        
        });
        
        $('#AjaxModal').modal('hide');
        $(this).reset();
        return false;
        
    });
    

    $('#InspectionItemAddForm').live('submit', function() {

        lastItemOrder = $('.InspectionAreaItem:last input[id^="InspectionItem"][id$="Order"]').val();
        lastItemOrder++;    
        $('#InspectionItemOrder').val( lastItemOrder );

        $.ajax({
            url: '/safetrain/inspections/inspection_items/add',
            type: "post",
            dataType: "json",
            data: $( this ).serialize(),
            success: function( objResponse ) {
                $.get( '/safetrain/inspections/inspection_items/view_form_item/' +  objResponse.data.id , function(data){ 
                    $(data).insertAfter( ".InspectionAreaItem:last" );
                });
            },
            error: function( objResponse ) {
                alert('Error Creating Item');
            }
        
        });
        
        $('#AjaxModal').modal('hide');
        $(this).reset();
        return false;
        
    });
         
        
    // Confirm Delete Area and then call delete via Ajax    
    $('.btn-area-delete').live('click', function( ) {
        ans = confirm('Are your sure you want to delete this area?');
        if ( ans === true ) {
            
            //ajax call to delete record
            $.ajax({url:$(this).attr('href'), type: 'post'});
            $( this ).closest('.InspectionAreaFieldset ').remove();
        }
        return false;
    });

    // Confirm Delete Item and then call delete via Ajax
    $('.btn-item-delete').live('click', function( ) {
        ans = confirm('Are your sure you want to delete this item?');
        if ( ans === true ) {
            
            //ajax call to delete record
            $.ajax({url:$(this).attr('href'), type: 'post'});
            $( this ).closest('.InspectionAreaItem ').remove();
        }
        return false;
    });
        
        
        
    //Moveup Area Clicked
    $('.btn-area-moveup').live('click', function( ) {
        area = $( this ).closest('.InspectionAreaFieldset');
        areaBefore = $(this).closest('.InspectionAreaFieldset').prev('.InspectionAreaFieldset');
        area.insertBefore( areaBefore  );        
        
        areaOrder = area.find('input[id$="Order"]').val();
        areaBeforeOrder = areaBefore.find('input[id$="Order"]').val();
        
        //console.log( {areaOrder: areaOrder, areaBeforeOrder: areaBeforeOrder } );
        
        area.find('input[id$="Order"]').val( areaBeforeOrder );
        areaBefore.find('input[id$="Order"]').val( areaOrder );
        
        return false;
    });
    
    //Movedown Area Clicked
    $('.btn-area-movedown').live('click', function( ) {
        
        area = $( this ).closest('.InspectionAreaFieldset');
        areaAfter = $(this).closest('.InspectionAreaFieldset').next('.InspectionAreaFieldset');
        area.insertAfter( areaAfter );
        
        areaOrder = area.find('input[id$="Order"]').val();
        areaAfterOrder = areaAfter.find('input[id$="Order"]').val();
        
        //console.log( {areaOrder: areaOrder, areaAfterOrder: areaAfterOrder } );
        
        area.find('input[id$="Order"]').val( areaAfterOrder );        
        areaAfter.find('input[id$="Order"]').val( areaOrder );
        
        return false;
    });
    
    
    //Moveup Area Clicked
    $('.btn-item-moveup').live('click', function( ) {
        el = $( this ).closest('.InspectionAreaItem');
        elBefore = $(this).closest('.InspectionAreaItem').prev('.InspectionAreaItem');
        el.insertBefore( elBefore  );        
        
        itemOrder = el.find('input[id$="Order"]').val();
        itemBeforeOrder = elBefore.find('input[id$="Order"]').val();
        
        el.find('input[id$="Order"]').val( itemBeforeOrder );
        elBefore.find('input[id$="Order"]').val( itemOrder );
        
        return false;
    });
    
    //Movedown Area Clicked
    $('.btn-item-movedown').live('click', function( ) {
        
        el = $( this ).closest('.InspectionAreaItem');
        elAfter = $(this).closest('.InspectionAreaItem').next('.InspectionAreaItem');
        el.insertAfter( elAfter );
        
        itemOrder = el.find('input[id$="Order"]').val();
        itemAfterOrder = elAfter.find('input[id$="Order"]').val();
        
        
        el.find('input[id$="Order"]').val( itemAfterOrder );        
        elAfter.find('input[id$="Order"]').val( itemOrder );
        
        return false;
    });
</script>
