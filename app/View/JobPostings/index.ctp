
<div class="account index bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Job Postings List</h6>
            <h3 class="dashhead-title"><i class="fa fa-thumb-tack fa-fw"></i>Job Postings</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Jobs/job_posting_menu' );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Jobs/status_filter' );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>

    <table class="table table-hover table-condensed">
        <thead>
            <tr class="tr-heading">
                <th>Applicants</th>
                <th>Search</th>
                <th>Job Title</th>
                <th>Hiring Lead</th>
                <th>Opened On</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(empty($jobPosting[0]['JobPosting'])){
            }else{
                foreach($jobPosting as $item){
					#pr($item);
					#exit;
                    $jobList[$item['JobPosting']['id']] = $item['Job']['name'];
                    $now = new DateTime("now");
                    $old_time = new DateTime($item['JobPosting']['created']);

                    $accuracy = 1;
                    $difference = $now->diff($old_time);

                    $intervals = array('y' => 'year', 'm' => 'month', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');

                    $i = 0;
                    $result = '';
                    foreach ($intervals as $interval => $name) {
                        if ($difference->$interval > 1) {
                            $result .= $difference->$interval.' '. $intervals[$interval] . 's';
                            $i++;
                        } elseif ($difference->$interval == 1) {
                            $result .= $difference->$interval.' '. $intervals[$interval];
                            $i++;
                        }

                        if ($i == $accuracy) {
                            break;
                        }
                    }

                    $opened = date( APP_DATE_FORMAT, strtotime($item['JobPosting']['created']));

                    $number_applicants = count($item['ApplyJob']);
                    $new_count = 0;
                    $count_item = null;

                    foreach($item['ApplyJob'] as $object){
                        if($object['status'] == 0){
                            $new_count++;
                        }
                    }

                    if($new_count >= 1){
                        $count_item = '<br /><small><small class="text-success">'.$new_count. ' New</small></small>';
                    }

					#pr($item);
					#exit;
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo $this->Html->link(
                                '<i class="fa fa-users "></i>&nbsp;' . $number_applicants . $count_item,
                                array('controller'=>'JobPostings', 'action'=>'view', $item['JobPosting']['id']),
                                array('escape'=>false,'data-toggle'=>'tooltip','data-placement'=>'top','title'=>'View Applicants')
                            );
                            ?>
                        </td>

                        <td>
                            <span class="btn-group-xs" data-placement="top" data-toggle="tooltip" title="Search Job Seekers">
                                <?php
                                echo $this->Html->link(
                                	'<i class="fa fa-search "></i>',
                                    array('controller'=>'JobPostings', 'action'=>'search', $item['JobPosting']['id']),
                                    array('escape'=>false)
                                );

                                ?>
                            </span>
                        </td>

                        <td>
                            <?php
                            echo $this->Html->link(
                                $item['Job']['name'],
                                array('controller'=>'JobPostings', 'action'=>'view', $item['JobPosting']['id']),
                                array('escape'=>false,'data-toggle'=>'tooltip','data-placement'=>'top','title'=>'View Details')
                            );
                            ?>
                        </td>

                        <td><?=$item['User']['first_name']?></td>

                        <td><?=$opened?><br /><small><?=$result?> ago</small></td>

                        <td><?php echo $settings['job_status'][$item['JobPosting']['status']]; ?></td>

                        <td>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $("#myModal2").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $('#ex2').slider({
            formatter: function(value) {
                return 'Percent Match: ' + value + '%';
            }
        });
        /*
        var edit_name_url = '<?php echo Router::url( array('controller'=>'JobTalentpatterns', 'action'=>'inline_edit', 'member'=>true ));?>';

        $('.jobTalentPattern').editable({
            disabled: false,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });

        $(document).on('click','.editable-submit',function(){
            var id = $(this).closest('.wrap').attr('id');
            var value = $('.input-sm').val();
            var field = $(this).closest('.wrap').attr('field');

            $.ajax({
                url: edit_name_url,
                type: 'post',
                dataType: "json",
                data: { JobTalentPattern: { id: id, field: field, value: value }},
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
        */
    });
</script>