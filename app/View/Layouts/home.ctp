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
        <meta name="viewport" content="initial-scale = 1,maximum-scale = 1,user-scalable=no" />
        <?php echo $this->fetch('meta'); ?>
        
        <title><?php echo $title_for_layout; ?> - <?php echo Configure::read('App.SiteName'); ?></title>
        
        <!---- scriptsTop ---->
        <?php $this->start('scriptsTop'); ?>
            <?php echo $this->Html->script('libs/jquery-1.11.1.min'); ?>
            <?php echo $this->Html->script('libs/modernizr.custom'); ?>
            <?php echo $this->Html->script('jquery.flexslider-min'); ?>
            <?php echo $this->Html->script('jquery.lifestream.min'); ?>
        <?php $this->end('scriptsTop'); ?>
        <!---- /scriptsTop ---->
        
        <!---- scriptslib ---->
        <?php $this->start('script'); ?>
            
        <?php $this->end('script'); ?>
        <!---- /scriptslib ---->
        
        <!---- scriptsBottom ---->
        <?php $this->start('scriptsBottom'); ?>
            
            <?php echo $this->Html->script('libs/jquery.easing.min'); ?>
            <?php echo $this->Html->script('jquery.reject'); ?>
            <?php echo $this->Html->script('plugins/bootstrap.min'); ?>
            <?php echo $this->Html->script('plugins/smoothscroll'); ?>
            <?php echo $this->Html->script('plugins/utilities'); ?>
            <?php echo $this->Html->script('plugins/foundation.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.placeholder'); ?>
            <?php echo $this->Html->script('plugins/icheck.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.validate.min'); ?>
            <?php echo $this->Html->script('plugins/waypoints.min'); ?>
            <?php echo $this->Html->script('plugins/isotope.min'); ?>
            <?php echo $this->Html->script('plugins/masterslider.min'); ?>
            <?php echo $this->Html->script('plugins/owl.carousel.min'); ?>
            <?php echo $this->Html->script('plugins/lightGallery.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.stellar.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.parallax.min'); ?>
            <?php echo $this->Html->script('plugins/chart.min'); ?>
            <?php echo $this->Html->script('plugins/jquery-numerator'); ?>
            <?php echo $this->Html->script('plugins/jquery.countdown.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.easypiechart.min'); ?>
            <?php echo $this->Html->script('plugins/jquery.nouislider.min'); ?>
            <?php echo $this->Html->script('plugins/card.min'); ?>
            <?php echo $this->Html->script('jquery.raty.min'); ?>
            <?php echo $this->Html->script('scripts'); ?>
            
            <!--[if lt IE 9]>
                <?php echo $this->Html->script('plugins/respond'); ?>
            <![endif]-->
            
        <?php $this->end('scriptsBottom'); ?>
        <!---- /scriptsBottom ---->
        
        <!---- csslib ---->
        <?php $this->start('csslib'); ?>
            <!-- The styles -->
            <!--Master Slider Styles-->
            <?php echo $this->Html->css('jquery.raty.css'); ?>
            <?php echo $this->Html->css('masterslider/style/masterslider.css'); ?>
            <?php echo $this->Html->css('masterslider/skins/light-3/style.css'); ?>
            <?php echo $this->Html->css('jquery.reject.css'); ?>
            <?php echo $this->Html->css('flexslider.css'); ?>
            <?php echo $this->Html->css('lifestream.css'); ?>
            
            <!--Kedavra Stylesheet-->
            <?php echo $this->Html->css('styles.css'); ?>
            
            
            
        <?php $this->end('csslib'); ?>
        <!---- /csslib ---->
        
        <?php echo $this->fetch('csslib'); ?>
        <?php echo $this->fetch('scriptsTop'); ?>
        
        <!--Couple of added google fonts -->
        <link href="http://fonts.googleapis.com/css?family=Sonsie+One|Old+Standard+TT" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Cabin:400,600italic" rel="stylesheet" type="text/css">
        <meta name="google-site-verification" content="Y6694A7kDZ3tkNg6nkZ9hzgeeNSt7fh4FThjygxRJ0Q" />
        <!--Google Maps API-->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5DLwPPVAz88_k0yO2nmFe7T9k1urQs84"></script>
        
        
    </head>
    
    <body class="parallax animated fadeIn">
        <!--Off-Canvas-->
        <div class="off-canvas-wrap" data-offcanvas>
            <div class="inner-wrap">
                <div class="site-layout">
                    <?php echo $this->element('home_header'); ?>
                    <?php echo $this->element('home_navbar'); ?>
                    <noscript>
                        <div class="alert alert-block col-md-12">
                            <h4 class="alert-heading">Warning!</h4>
                            <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                        </div>
                    </noscript>
                    
                    <?php echo $this->element('breadcrumbs'); ?>
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->Session->flash('auth'); ?>
                        
                    <?php if($logged_in): ?>
                        <?php 
                        if (isset($this->request->params['admin']) ) {
                            $prefix = 'admin';
                        }else{
                            $prefix = 'member';
                        }
                        ?>
                    <?php endif; ?>
                    <!--<div data-spy="scroll" data-target="#target_nav" data-offset="125px">-->
                        <?php echo $this->fetch('content'); ?>
                    <!--</div>-->
                </div>
                <footer class="footer">
                </footer>
                <?php //echo $this->element('home_footer'); ?>
                <?php echo $this->fetch('script'); ?>
                <?php echo $this->fetch('scriptsBottom'); ?>
                <!-- Normal Model -->
                <div class="modal type-primary fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
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
                <!-- begin olark code -->
                <!--<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
                f[z]=function(){
                (a.s=a.s||[]).push(arguments)};var a=f[z]._={
                },q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
                f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
                0:+new Date};a.P=function(u){
                a.p[u]=new Date-a.p[0]};function s(){
                a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
                hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
                return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
                b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
                b.contentWindow[g].open()}catch(w){
                c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
                var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
                b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
                loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
                /* custom configuration goes here (www.olark.com/documentation) */
                olark.identify('4561-798-10-3325');/*]]>*/</script><noscript><a href="https://www.olark.com/site/4561-798-10-3325/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
                -->
                <!-- end olark code -->
                               
                <!-- Begin: Google Analytics -->
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })
                    (window,document,'script','//www.google-analytics.com/analytics.js','ga');
                    ga('create', 'UA-64515374-1', 'auto');
                    ga('send', 'pageview');
                </script>
                <!-- End: Google Analytics -->
                <!-- close the off-canvas menu -->
                <a class="exit-off-canvas"></a>
            </div>
        </div><!--Off-Canvas Close-->
        <script language="JavaScript">
            jQuery(window).load( function() {
                $.reject({
                    reject: { all: false }, // Reject all renderers for demo
                    imagePath: './img/', 
                    display: ['firefox','chrome','opera']
                });
            });
        </script>
    </body>                           
</html>