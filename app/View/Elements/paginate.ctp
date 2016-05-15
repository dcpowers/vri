<div class="paginate">
    <div class="summary pull-right">
        <?php 
        echo $this->Paginator->counter(
            'Page {:page} of {:pages}, showing {:current} records out of {:count} total, 
            starting on record {:start}, ending on {:end}' 
        ); 
        ?>
    </div>
    <div class="clearfix"></div>
    <ul class="pagination pull-right">
        <?php
        echo $this->Paginator->prev('<i class="fa fa-angle-double-left fa-fw"></i>&nbspPrevious',array(
            'class' => 'prev',
            'tag' => 'li',
            'currentTag' => 'a',
            'disabledTag' => 'a',
            'escape'=>false
        ));
                                             
        echo $this->Paginator->numbers(array(
            'before' => '',
            'separator' => '',
            'currentClass' => 'active',
            'currentTag' => 'a',
            'tag' => 'li',
            'after' => '',
            'modulus'=> 15
        ));
                    
        echo $this->Paginator->next('Next&nbsp<i class="fa fa-angle-double-right fa-fw"></i>',array(
            'class' => 'next',
            'tag' => 'li',
            'currentTag' => 'a',
            'disabledTag' => 'a',
            'escape'=>false
        )); 
        ?>
    </ul>
                    
    <div class="clearfix"></div>
</div>