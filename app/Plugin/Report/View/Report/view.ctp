<?php
    //Audit::log('Your message' ); Send info to audit log table
    //video player javascript and css
    $this->Html->script('Training.video.js', array('block' => 'scriptsTop'));
    $this->Html->css('Training.video-js.css', '', array('block' => 'css') );

    //Multiselect javascript and css

    //Multiselect css
    //$this->Html->css('Training.ui-lightness/jquery-ui-1.8.20.custom.css', '', array('block' => 'css') );
    $this->Html->css('Training.jquery-multiselect-2.0.css', '', array('block' => 'app-css') );

    //Multiselect javascript
    //$this->Html->script('Training.jquery-ui-1.8.20.custom.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('Training.jquery-multiselect-2.0.js', array('block' => 'scriptsBottom'));
    $this->Html->script('Training.locales/jquery-multiselect-2_en.js', array('block' => 'scriptsBottom'));
    $this->Html->script('http://jqueryui.com/themeroller/themeswitchertool/', array('block' => 'scriptsBottom'));




    //$this->Html->css('Training.bootstrap-lightbox.css', '', array('block' => 'css') );
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
?>
<script>
  videojs.options.flash.swf = "http://portal.goodyear.com/training/js/video-js.swf"
</script>

<div class="row-fluid two-column with-right-sidebar">
    <div class="span12">
        <h1>
            <?php echo 'TRN-' . str_pad( $train['Training']['id'], 4, 0, STR_PAD_LEFT )?> <?php echo $train['Training']['title'];?>
            <small>
                Rev# <?php echo $train['Training']['revision'];?>,
                Revised: <?php echo date( APP_DATE_FORMAT, strtotime( $train['Training']['revised_on'] ));?>
                <?php if (!empty( $train['Training']['author_id'])) { ?>,
                    Author: <?php //echo 'TODO Add Author';?>
                <?php } ?>
            </small>
        </h1>
        <div class="row-fluid">
            <div class="training view span9">
                <?php if ( !empty( $train['Training']['is_retired'] ) ) { ?>
                    <div class="alert alert-error"><strong>Note:</strong> This HSE Training was retired on <?php echo date( APP_DATE_FORMAT, strtotime( $train['Training']['retired_on'] ) ); ?></div>
                <?php } ?>

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#contents" data-toggle="tab">Content</a></li>
                        <li><a href="#associates" data-toggle="tab">Associates</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="contents">
                            <div class=" vjs-load-progress"> </div>
                            <?php
                            if(!empty($train['Training']['description'])) {
                                echo $train['Training']['description'];
                            } else if($train['Training']['is_photos'] == 0 && $train['Training']['is_video'] == 0 && empty($train['Training']['description'])) {
                                echo "No Content Found For This Training";
                            }

                            if($train['Training']['is_video'] == 1 && !empty($my_signoff['TrainingRecord']['is_inprogress']) && $my_signoff['TrainingRecord']['is_inprogress'] == '1' ) {
                                echo $this->element( 'video' );
                            } else if ($train['Training']['is_video'] == 1 && empty($my_signoff['TrainingRecord']['is_inprogress']) ) {
                                echo $this->Html->image('../../blog/wp-content/uploads/'.$train['TrainingFile'][0]['file_name'], array('alt' => $train['Training']['title'], 'width' => '640' , 'height' => '480'));
                            }

                            if($train['Training']['is_photos'] == 1 &&  $my_signoff['TrainingRecord']['is_inprogress'] == '1') {
                                echo $this->element( 'photos' );
                            } else if ($train['Training']['is_photos'] == 1 && empty($my_signoff['TrainingRecord']['is_inprogress']) ) {
                                echo $this->Html->image('../../blog/wp-content/uploads/'.$train['TrainingFile'][0]['file_name'], array('alt' => $train['Training']['title'], 'width' => '640' , 'height' => '480'));
                            }

                            if( (empty($my_signoff['TrainingRecord']['is_inprogress']) || $my_signoff['TrainingRecord']['is_inprogress'] != '1') && ($train['Training']['is_photos'] == 1 OR $train['Training']['is_video'] == 1 OR !empty($train['Training']['description']))) {
                                echo $this->Html->link(
                                    'Begin Training',
                                    array('controller'=> 'training_records', 'action'=>'begin', $train['Training']['id']),
                                    array('class'=>'btn btn-large btn-block btn-info','escape'=>false)
                                );
                            }
                            ?>

                        <?php if( !empty($my_signoff['TrainingRecord']['is_inprogress']) && $my_signoff['TrainingRecord']['is_inprogress'] == '1') {
                            if($train['Training']['is_quiz'] == '1') {
                                echo $this->Html->link(
                                    'I certify that I have viewed the above material and understand it\'s meaning.',
                                    array('controller'=> 'trainings', 'action'=>'quiz', $train['Training']['id'], $my_signoff['TrainingRecord']['id'] ),
                                    array('class'=>'btn btn-large btn-danger','escape'=>false),
                                    "I certify that I have viewed the above material and understand it's meaning."
                                );
                            } else {
                                echo $this->Html->link(
                                    'I certify that I have viewed the above material and understand it\'s meaning.',
                                    array('controller'=> 'training_records', 'action'=>'signoff', $my_signoff['TrainingRecord']['id'] ),
                                    array('class'=>'btn btn-large btn-danger','escape'=>false),
                                    "I certify that I have viewed the above material and understand it's meaning."
                                );
                            }
                        }?>

                        </div>

                        <div class="tab-pane hide" id="associates">
                            <div class="box">
                                <div class="box-header">
                                    <h2><i class="fa-icon-book"></i><span class="break"></span>Add Associates</h2>
                                </div>

                                <div class="box-content">
                                    <?php
                                    if ( in_array( 'SuperAdmin', $role_names ) OR in_array('Safety Supervisor', $role_names) OR in_array('Safety Instructor', $role_names) == AuthComponent::user('id') ) {
                                        echo $this->Form->create('TrainingRecord', array('action' => 'add'));
                                        echo $this->Form->input('Training_id',array( 'value' => $train['Training']['id']  , 'type' => 'hidden'));
                                    ?>
                                    <div class="row-fluid" style="margin-bottom: 10px;">
                                        <div class="row-fluid">
                                        <div class="span12">
                                            <div id="usage_simple" class="span12">
                                                <div class="example-container ui-helper-clearfix span12">
                                                    <select id="multiselect_simple" class="multiselect" multiple="multiple" name="data[TrainingRecord][Associates][]" style="height: 250px; width: 600px;">
                                                        <?php foreach($associates  as $emp_info ) { ?>
                                                            <option value="<?=$emp_info['Associate']['id']?>"><?php echo ucwords(strtolower($emp_info['Associate']['first_name'] .' '. $emp_info['Associate']['last_name'])); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row-fluid">
                                        <div class="span4">
                                            <?php
                                            echo $this->Form->input('completed', array( 'label' => 'Date Completed',
                                                'type'=>'date',
                                                'dateFormat'=> 'MDY',
                                                'class'=>'span4' ));
                                            ?>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <?php echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                                    </div>
                                    <?php echo $this->Form->end(); } ?>
                                </div>
                            </div>
                            <div class="box">
                                <div class="box-header">
                                    <h2><i class="fa-icon-book"></i><span class="break"></span>Current Associates</h2>
                                </div>

                                <div class="box-content">
                                    <table class="table table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th style="width:90px">User Id</th>
                                                <th>Associate</th>
                                                <th style="width:180px">Completed Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach( $train['TrainingRecord'] as $record_info ) { ?>
                                            <tr>
                                                <td><?php echo strtoupper($record_info['Associate']['userid']); ?></td>

                                                <td><?php echo ucwords(strtolower($record_info['Associate']['first_name'] .' '. $record_info['Associate']['last_name'])); ?> </td>

                                                <td>
                                                    <?php if($record_info['is_inprogress'] == '1') {
                                                        echo "In Progress";
                                                    } else {
                                                        echo date( APP_DATE_FORMAT, strtotime( $record_info['completed'] ));
                                                    } ?>
                                                </td>

                                                <td>
                                                    <?php if ( in_array( 'SuperAdmin', $role_names ) OR in_array('Safety Supervisor', $role_names) OR $train['Training']['author_id'] == AuthComponent::user('id') ) {
                                                        echo $this->Html->link(
                                                            ' <i class="icon-white icon-remove"></i>',
                                                            array('controller'=>'training_records', 'action'=>'delete', $record_info['id'], $train['Training']['id'] ),
                                                            array('title'=>__('Delete'),'class'=>'btn btn-mini btn-danger','escape'=>false),
                                                            sprintf('Delete Training Record?')
                                                        );
                                                    } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="submit">
                    <?php if ( in_array( 'SuperAdmin', $role_names ) OR in_array('Safety Supervisor', $role_names) OR $train['Training']['author_id'] == AuthComponent::user('id') ) {
                        echo $this->Html->link(__('Edit'), array('action'=>'edit', $train['Training']['id'] ), array('class'=>'btn') );
                    } ?>
                </div>
            </div>
            <?php echo $this->element( 'sidebar')?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready( $(function() {
        //navigator.userLanguage = 'fr';

        $('#multiselect_simple').multiselect({
            sortMethod: 'standard',
            moveEffect: 'blind',
            moveEffectOptions: {direction:'vertical'},
            moveEffectSpeed: 'fast'
        })

        .bind('change', function(evt, ui) {
            var value = ui.itemIndex > -1 ? ui.optionCache.get(ui.itemIndex).optionElement.attr('value') : null;
            $('#debug_simple').append( $('<div></div>').text('Multiselect change event! ' + (value ? 'value='+value+' was' : 'all items were') + ' ' + (ui.selected ? 'selected' : 'deselected')) );
        })

        .bind('multiselectsearch', function(evt, ui) {
            $('#debug_simple').append( $('<div></div>').text('Multiselect search event! searching for "' + ui.text + '"') );
        });

        $('#multiselect_disabled').multiselect();

        $('#multiselect_groups').multiselect();

        if ($.fn.themeswitcher) { $('#switcher')
            .css('padding-bottom', '8px')
            .before('<h4>Use the themeroller to dynamically change look and feel</h4>')
            .themeswitcher();
        }

        $('#btnRefresh_simple').click(function() {
            $('#multiselect_simple').multiselect('refresh');
        });

        $('#btnToggleOriginal_simple').click(function() {
            $('#multiselect_simple').css('float','right').toggle();
        });

        $('#selectLocale').change(function() {
            $('.multiselect').multiselect('locale', $(this).val() );
        });

        // build locale options
        for (var locale in $.uix.multiselect.i18n) {
            $('#selectLocale').append($('<option></option>').attr('value', locale).text(locale.length == 0 ? '(default)' : locale));
        }

        $('#selectLocale').val($('#multiselect').multiselect('locale'));
    }));
</script>








