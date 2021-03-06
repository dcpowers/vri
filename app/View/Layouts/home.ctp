<!DOCTYPE html>
<?php
    header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header("Pragma: no-cache"); // HTTP/1.0
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Content-Type: text/html; charset=UTF-8");
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
?>
<html lang="en">
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>

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
            <!-- datepicker -->
            <?php echo $this->Html->script('bootstrap-datetimepicker.min'); ?>
            <!-- Bootstrap WYSIHTML5 -->
            <?php echo $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min'); ?>
            <!-- Slimscroll -->
            <?php echo $this->Html->script('../plugins/slimScroll/jquery.slimscroll.min'); ?>
            <!-- FastClick -->
            <?php echo $this->Html->script('../plugins/fastclick/fastclick.min'); ?>

			<?php echo $this->Html->script('jquery.bootstrap-duallistbox.min' ); ?>
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
            <?php echo $this->Html->css('bootstrap-datetimepicker.css'); ?>
			<?php echo $this->Html->css('bootstrap-duallistbox.min' ); ?>
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
            <?php echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css');?>
            <?php #echo $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');?>
            <?php echo $this->Html->css('font-awesome.min.css');?>
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
    <body class="hold-transition layout-top-nav skin-red">
        <div class="wrapper bg-white">
            <?php echo $this->element('header'); ?>
            <?php #echo $this->element('menu'); ?>
            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
                        <?php echo $this->element('breadcrumbs'); ?>
                    </section>
                    <section class="content bg-white">
                        <?php echo $this->Flash->render(); ?>
                        <?php echo $this->fetch('content'); ?>
                    </section>
                </div>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    Footer stuff here
                </div>
                <strong><?php echo $this->element('copyright'); ?></strong>
            </footer>

            <?php echo $this->element('sidebar'); ?>

        </div>
        <!-- Normal Model -->
        <div class="modal bootstrap-dialog type-primary fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                </div> <!-- /.modal-content -->
            </div> <!-- /.modal-dialog -->
        </div> <!-- /.modal -->

        <!-- Big Model -->
        <div class="modal bootstrap-dialog type-primary fade modal-wide in" id="myModalBig" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                </div> <!-- /.modal-content -->
            </div> <!-- /.modal-dialog -->
        </div> <!-- /.modal -->

        <!-- small Model -->
        <div class="modal bootstrap-dialog type-primary fade in" id="myModalSm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                </div> <!-- /.modal-content -->
            </div> <!-- /.modal-dialog -->
        </div> <!-- /.modal -->

        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $("#myModal").on('hidden.bs.modal', function(){
                    $('div.modal-body').html('');
					$(this).data('bs.modal', null);
				});

                $("#myLgModal").on('hidden.bs.modal', function(){
					$('div.modal-body').html('');
                    $(this).data('bs.modal', null);
				});

                $("#mySmModal").on('hidden.bs.modal', function(){
                    $('div.modal-body').html('');
					$(this).data('bs.modal', null);
				});
            });
        </script>
        <?php echo $this->fetch('scriptsBottom'); ?>
    </body>
</html>