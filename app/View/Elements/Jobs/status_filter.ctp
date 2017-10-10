<ul class="list-inline">
            <li>
                <?php
                echo $this->Html->link(
                    '<i class="fa fa-search"></i> Search Job Seekers',
                    array('controller'=>'JobPostings', 'action'=>'search'),
                    array('escape'=>false)
                );
                ?>
            </li>
            <li>
                <?php
                echo $this->Html->link(
                	'<i class="fa fa-eye"></i> View Career\'s Dashboard <i class="fa fa-external-link"></i>',
                    array('controller'=>'Pages', 'action'=>'job_postings'),
                    array('escape'=>false, 'target'=>'_blank')
                );
                ?>
            </li>
            <li>
                <div class="dropdown">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-cogs"></i> Settings',
                        array('controller'=>'Groups', 'action'=>'index', '#'=>'jobOpeningsSettings'),
                        array('escape'=>false)
                    );
                    ?>

                    <?php
                    echo $this->Html->link(
                        '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>',
                        '#',
                        array('escape'=>false, 'data-toggle'=>'dropdown', 'class'=>'dropdown-toggle')
                    );
                    ?>
                    <ul class="dropdown-menu">
                        <li>
                            <?php
                            echo $this->Html->link(
                                'Job Titles',
                                array('controller'=>'Jobs', 'action'=>'index'),
                                array('escape'=>false)
                            );
                            ?>
                        </li>
                        <li>
                            <?php
                            echo $this->Html->link(
                                'Talent Patterns',
                                array('controller'=>'JobTalentpatterns', 'action'=>'index'),
                                array('escape'=>false)
                            );
                            ?>
                        </li>
                        <li>
                            <?php
                            echo $this->Html->link(
                                'Screening Questions',
                                array('controller'=>'JobQuestions', 'action'=>'index'),
                                array('escape'=>false)
                            );
                            ?>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>