<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
        <!--<meta http-equiv="X-UA-Compatible" content="IE=8" >-->
        <?php echo $this->Html->meta('icon'); ?>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?php echo $this->fetch('meta'); ?>
        
        <title><?php echo $title_for_layout; ?> - <?php echo Configure::read('App.SiteName'); ?></title>
        
        <!---- scriptsTop ---->
        <?php $this->start('scriptsTop'); ?>
            <?php echo $this->Html->script('../plugins/jQuery/jQuery-2.1.4.min'); ?>
        <?php $this->end('scriptsTop'); ?>
        <!---- /scriptsTop ---->
        
        <!---- scriptslib ---->
        <?php $this->start('script'); ?>
            
        <?php $this->end('script'); ?>
        <!---- /scriptslib ---->
        
        <!---- scriptsBottom ---->
        <?php $this->start('scriptsBottom'); ?>
            <?php echo $this->Html->script('../bootstrap/js/bootstrap.min'); ?>
            <?php echo $this->Html->script('../plugins/iCheck/icheck.min'); ?>
            <?php echo $this->Html->script('chosen.jquery.min'); ?>
            <?php echo $this->Html->script('bootstrap-editable.min'); ?>
            
            <!-- Morris.js charts -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
            
            <?php echo $this->Html->script('../plugins/morris/morris.min'); ?>
            <!-- Sparkline -->
            <?php echo $this->Html->script('../plugins/sparkline/jquery.sparkline.min'); ?>
            <!-- jvectormap -->
            <?php echo $this->Html->script('../plugins/jvectormap/jquery-jvectormap-1.2.2.min'); ?>
            <?php echo $this->Html->script('../plugins/jvectormap/jquery-jvectormap-world-mill-en'); ?>
            <!-- jQuery Knob Chart -->
            <?php echo $this->Html->script('../plugins/knob/jquery.knob'); ?>
            <!-- daterangepicker -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
            <?php echo $this->Html->script('../plugins/daterangepicker/daterangepicker'); ?>
            <!-- datepicker -->
            <?php echo $this->Html->script('../plugins/datepicker/bootstrap-datepicker'); ?>
            <!-- Bootstrap WYSIHTML5 -->
            <?php echo $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min'); ?>
            <!-- Slimscroll -->
            <?php echo $this->Html->script('../plugins/slimScroll/jquery.slimscroll.min'); ?>
            <!-- FastClick -->
            <?php echo $this->Html->script('../plugins/fastclick/fastclick.min'); ?>
            <!-- AdminLTE App -->
            <?php echo $this->Html->script('../dist/js/app.min'); ?>
            <?php echo $this->Html->script('../dist/js/demo'); ?>
        <?php $this->end('scriptsBottom'); ?>
        <!---- /scriptsBottom ---->
        
        <!---- csslib ---->
        <?php $this->start('csslib'); ?>
            <!-- The styles -->
            <?php echo $this->Html->css('bootstrap.min.css'); ?>
            <?php echo $this->Html->css('AdminLTE.css'); ?>
            <?php echo $this->Html->css('bootstrap-chosen.css'); ?>
            <?php echo $this->Html->css('app.css'); ?>
            <?php echo $this->Html->css('bootstrap-editable.css'); ?>
            <?php echo $this->Html->css('../plugins/iCheck/square/blue.css'); ?>
            <?php echo $this->Html->css('../dist/css/skins/_all-skins.min.css'); ?>
            
            <!-- iCheck -->
            <?php echo $this->Html->css('../plugins/iCheck/flat/blue.css'); ?>
            <!-- Morris chart -->
            <?php echo $this->Html->css('../plugins/morris/morris.css'); ?>
            <!-- jvectormap -->
            <?php echo $this->Html->css('../plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>
            <!-- Date Picker -->
            <?php echo $this->Html->css('../plugins/datepicker/datepicker3.css'); ?>
            <!-- Daterange picker -->
            <?php echo $this->Html->css('../plugins/daterangepicker/daterangepicker-bs3.css'); ?>
            <!-- bootstrap wysihtml5 - text editor -->
            <?php echo $this->Html->css('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>
            
            
            <?php echo $this->Html->css('https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');?>
            <?php echo $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');?>
            <?php echo $this->Html->css('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');?>
        <?php $this->end('csslib'); ?>
        <!---- /csslib ---->
        
        <?php echo $this->fetch('csslib'); ?>
        <?php echo $this->fetch('scriptsTop'); ?>
        <?php echo $this->fetch('scripts'); ?>
        
        <!--Couple of added google fonts -->
        <link href="http://fonts.googleapis.com/css?family=Sonsie+One|Old+Standard+TT" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Cabin:400,600italic" rel="stylesheet" type="text/css">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-purple sidebar-mini wysihtml5-supported">
        <div class="wrapper">
            <?php echo $this->element('header'); ?>
            <?php echo $this->element('menu-left'); ?>
            
            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $this->element('breadcrumbs'); ?>        
                </section>
                <section class="content">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    Footer stuff here
                </div>
                <strong>Copyright Stuff Here!! All rights reserved.
            </footer>
            <?php echo $this->element('sidebar'); ?>
        </div>
        
        <?php echo $this->fetch('scriptsBottom'); ?>
    </body>
</html>