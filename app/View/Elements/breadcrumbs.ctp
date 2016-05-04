<?php 
/**
* Breadcrumbs element
* 
* Set the breadcrumbs variable to pass to the view.
*  $this->set('breadcrumbs', array(
*   array( 'title'=>'Home','/url/to/page', ''),
*   array( 'title'=>'Calendar', array('controller'=>'calendars', 'action'=>'view', 23) ),
*   array( 'title'=>'My Events Calendar', '/a/string/based/url', array('title'=>'Foo', 'rel'=>'nofollow', 'style'=>'color:#AAA') )
* ));
* 
* @author Jonathan Cutrer (joncutrer@gmail.com)
*/
if ( empty($breadcrumbs_for_layout ) && !empty( $breadcrumbs ) && is_array( $breadcrumbs ) ) {
    $breadcrumbs_for_layout = $breadcrumbs;
}

$link_options = array(
    'class'=>'btn btn-default',
    'escape'=>false
);
            
if ( !empty($breadcrumbs_for_layout) ) { 
    ?>
    <div class="btn-group btn-breadcrumb clearfix">
        <?php 
        echo $this->Html->link(
            '<i class="fa fa fa-dashboard fa-fw"></i>', 
            array('plugin'=>false, 'controller'=>false, 'action'=>'index' ), 
            array('class'=>'btn btn-default ','escape'=>false ) 
        );
        
        foreach ( $breadcrumbs_for_layout as $key=>$crumb ) { 
            if(!empty( $crumb['options'] ) && !is_array( $crumb['options'])) { 
                throw new Exception('Invalid breadcrumb options.');
            }
            
            if(is_array( Configure::read('Breadcrumbs.option_defaults'))){
                #$link_options = array_merge( Configure::read('Breadcrumbs.option_defaults'), $crumb['options'] );
            }else{
                #$link_options = array();
            }
            
            echo $this->Html->link( 
                $crumb['title'], 
                $crumb['link'], 
                $link_options 
            );
        } 
        ?>
    </div><!-- /.breadcrumbs -->
    <?php 
} 
?>