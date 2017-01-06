    <?php
    #pr($users);
    #exit;
	$this->Html->css( 'simple-sidebar.css', '', array('block' => 'csslib' ) );  //TOP
    $var = ($currentLetter == 'All') ? 'false' : 'true' ;
    $in = ($currentLetter == 'All') ? null : 'in' ;
    ?>


    <div class="account index bg-white">

		<div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Start Bootstrap
                    </a>
                </li>
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li>
                    <a href="#">Shortcuts</a>
                </li>
                <li>
                    <a href="#">Overview</a>
                </li>
                <li>
                    <a href="#">Events</a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Services</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
			<div class="clearfix">&nbsp;</div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Simple Sidebar</h1>
                        <p>This template has a responsive menu toggling system. The menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will appear/disappear. On small screens, the page content will be pushed off canvas.</p>
                        <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>.</p>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    </div>

    <script type="text/javascript">
        jQuery(window).ready( function($) {
            $("#myModal").on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            $("#myModalBig").on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            $(".modal-wide").on("show.bs.modal", function() {
              var height = $(window).height() - 200;
              $(this).find(".modal-body").css("max-height", height);
            });
         });
    </script>