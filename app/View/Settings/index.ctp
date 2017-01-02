    <?php
    #pr($users);
    #exit;
    $var = ($currentLetter == 'All') ? 'false' : 'true' ;
    $in = ($currentLetter == 'All') ? null : 'in' ;
    ?>
	<style type="text/css">
		.left {
  			position: absolute;
  			top: 100px;
  			bottom: 0;
  			background-color: #EBEBEB;
		}

		.other {
  			position: absolute;
  			top: 100px;
  			bottom: 0;
  			background-color: #ffffff;
		}
	</style>

    <div class="account index bg-white">
        <div class="row">
			<div class="col-md-2 ">
				<h2><i class="fa fa-fw fa-cogs"></i>Settings</h2>
				<ul class="list-unstyled">
					<li>Accounts</li>
				</ul>
			</div>
			<div class="col-md-2 col-md-offset-2" style="border-right: 1px #ff0000 solid;"> here</div>
			<div class="col-md-8"></div>
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