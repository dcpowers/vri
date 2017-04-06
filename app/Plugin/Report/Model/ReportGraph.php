<?php
App::uses('ReportAppModel', 'Report.Model');

/**
 * Training Model
 *
 */

class ReportGraph extends ReportAppModel {
    
    public $displayField = 'title';
    public $table = false;
    
    public function horizontal_bar_chart($data=null){
        $height = 167;

        $im       = imagecreate(723,167);
        $white       = imagecolorallocate($im, 255, 255, 255);
        $black       = imagecolorallocate($im, 0, 0, 0);
        $red       = imagecolorallocate($im, 255, 0, 0);
        $orange   = imagecolorallocate($im, 206, 99, 0);
        $orangeBG = imagecolorallocate($im, 255, 204, 0);
        $red      = imagecolorallocate($im, 239, 0, 0);
        $ltred      = imagecolorallocate($im, 202, 0, 0);
        $dkred       = imagecolorallocate($im, 165, 0, 0);

        $Color5   = imagecolorallocate($im, 0, 0, 239);
        $line       = imagecolorallocate($im, 83, 102, 168);
        $dkColor5 = imagecolorallocate($im, 0, 0, 165);
        $ltColor5 = imagecolorallocate($im, 0, 0, 202);

        $gray      = imagecolorallocate($im, 192, 192, 192);
        $ltgray      = imagecolorallocate($im, 230, 230, 230);

        imageline ( $im, 25, 15, 515, 15, $black);

        //y position numbers
        imagestring ( $im , 3, 20, 0, "0", $black);
        imagestring ( $im , 3, 110, 0, "20%", $black);
        imagestring ( $im , 3, 210, 0, "40%", $black);
        imagestring ( $im , 3, 310, 0, "60%", $black);
        imagestring ( $im , 3, 410, 0, "80%", $black);
        imagestring ( $im , 3, 510, 0, "100%", $black);

        $leftbg = array(
              0  => 15,                      // x1
              1  => 25,                       // y1
              2  => 25,                   // x2
              3  => 15,                         // y2
              4  => 25,                   // x3
              5  => $height - 30,            // y3
              6  => 15,                         // x4
              7  => $height - 20,            // y4
        );

        $bottom = array(
              0  => 25,                     // x1
              1  => $height - 30,         // y1
              2  => 515,                    // x2
              3  => $height - 30,          // y2
              4  => 505,                    // x3
              5  => $height - 20,            // y3
              6  => 15,                         // x4
              7  => $height - 20,            // y4
        );

        imagefilledpolygon($im, $leftbg, 4, $orange );
        imagepolygon($im, $leftbg, 4, $black );

        imagepolygon($im, $bottom, 4, $black );

        $lineheight = 10;

        $c = 0;

        $color = array ("89B566","949AA2","409CD3","EC814F","68B36E","71717D","71C7C1","CF629C","9E8ABF","C44056","405B80","BFDDED","6A5183","40959D","8A4056","408F68","828CB0","FFCE40","A7C5AA","FFDD92","A5B9C4","D6D68C","C5BFED","89B566","D6F09E","D6AAC1","5498D3");
        $ltcolor = array ("85CD44","A5B2C1","0091E1","F25A15","48CC50","8888A8","50D9CF","DF3694","9C7DD4","D70026","004BAA","B2DEF3","692DAC","00B2BD","B10039","00B458","778CCA","FFD154","A8D8AB","FFDE9E","A6C7D7","E3E274","BAB2F3","85CD44","D2F583","E3A1C4","208BE1");
        $dkcolor = array ("629C33","707883","007BC4","E65714","369A3D","424252","42B4AC","BF2E7B","7D63A9","B0001E","002455","A9D1E7","38175A","00727C","63001E","006A35","586695","FFBD00","8AB28D","FFD16E","87A2B0","C8C866","B2A9E7","629C33","C8EB7D","C88DAC","1B75C4");

        while (list($key, $origVal) = each($data))
        {
            $R = hexdec(substr($color[$c], 0, 2));
            $G = hexdec(substr($color[$c], 2, 2));
            $B = hexdec(substr($color[$c], 4, 2));

            $ltR = hexdec(substr($ltcolor[$c], 0, 2));
            $ltG = hexdec(substr($ltcolor[$c], 2, 2));
            $ltB = hexdec(substr($ltcolor[$c], 4, 2));

            $dkR = hexdec(substr($dkcolor[$c], 0, 2));
            $dkG = hexdec(substr($dkcolor[$c], 2, 2));
            $dkB = hexdec(substr($dkcolor[$c], 4, 2));

            $thiscolor = imagecolorallocate($im,$R,$G,$B);
            $ltthiscolor = imagecolorallocate($im,$ltR,$ltG,$ltB);
            $dkthiscolor = imagecolorallocate($im,$dkR,$dkG,$dkB);

            $val = Round($origVal * 5);

            $CatName = "$key";

            $top = array(
                  0  => 15,                                // x1
                  1  => $lineheight + 16,            // y1
                  2  => 25,                           // x2
                  3  => $lineheight + 6,               // y2
                  4  => $val + 25,                       // x3
                  5  => $lineheight + 6,              // y3
                  6  => $val + 15,                      // x4
                  7  => $lineheight + 16,              // y4
            );

            // set up array of points for polygon blue bar
            $front = array(
                0  => $val + 15,                       // x1
                  1  => $lineheight + 16,           // y1
                  2  => $val + 25,                    // x2
                  3  => $lineheight + 6,             // y2
                  4  => $val + 25,                    // x3
                  5  => $lineheight + 26,              // y3
                  6  => $val + 15,                     // x4
                  7  => $lineheight + 36,              // y4
            );

            //blue bar
            imagefilledpolygon($im, $top, 4, $dkthiscolor );
            imagepolygon($im, $top, 4, $black );

            //raw score
            imagefilledrectangle ( $im, 15, $lineheight + 16, $val + 15, $lineheight + 36, $thiscolor);
            imagerectangle ( $im, 15, $lineheight + 16, $val + 15, $lineheight + 36, $black);

            imagefilledpolygon($im, $front, 4, $ltthiscolor );
            imagepolygon($im, $front, 4, $black );

            $string = $origVal;

            imagestring ( $im , 3, $val - 15 , $lineheight + 20, "$string", $white);

            imagestring ( $im , 3, 20, $lineheight + 20, "$CatName", $white);

            $lineheight = $lineheight + 30;

            $c++;
        }

        $dashed=array($black, $black, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT );

        ImageSetStyle($im, $dashed);

        //3d y position ticks
        imageline ( $im, 115, 15, 115, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 215, 15, 215, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 315, 15, 315, $height - 30, IMG_COLOR_STYLED);
         imageline ( $im, 415, 15, 415, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 515, 15, 515, $height - 30, $black);

        imageline ( $im, 115, $height - 30, 105, $height - 20, $black);
        imageline ( $im, 215, $height - 30, 205, $height - 20, $black);
        imageline ( $im, 315, $height - 30, 305, $height - 20, $black);
         imageline ( $im, 415, $height - 30, 405, $height - 20, $black);
        imageline ( $im, 515, $height - 30, 505, $height - 20, $black);

        //y position numbers
        imagestring ( $im , 3, 10, $height - 17, "0", $black);
        imagestring ( $im , 3, 100, $height - 17, "1", $black);
        imagestring ( $im , 3, 200, $height - 17, "2", $black);
        imagestring ( $im , 3, 300, $height - 17, "3", $black);
        imagestring ( $im , 3, 400, $height - 17, "4", $black);
        imagestring ( $im , 3, 500, $height - 17, "5", $black);

        imagepng($im,"img/disc.png");
        imagedestroy($im);
    
    }
    
    public function verticle_bar_chart($data=null, $name=null, $folder=null){
        #$data = array('A'=>50, 'B'=>40, 'C'=>30, 'D'=>20);
        
        foreach($data as $key=>$value){
            $values[]=$value;
            $legend[] = $key;
        }
        
        //sort($values);
        $total = count($values);
        $data_sum = array_sum($values);
        
        $image = imagecreate(800,350);
        // allocate some colors
        $white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
        
        //Blue
        $color[0] = imagecolorallocate($image, 51, 102, 204);
        $dkcolor[0] = imagecolorallocate($image, 38, 77, 153);
        //Orange
        $color[1] = imagecolorallocate($image, 220, 57, 18);
        $dkcolor[1] = imagecolorallocate($image, 165, 43, 14);
        //green
        $color[2] = imagecolorallocate($image, 16, 150, 24);
        $dkcolor[2] = imagecolorallocate($image, 12, 113, 18);
        //yellow
        $color[3] = imagecolorallocate($image, 255, 153, 0);
        $dkcolor[3] = imagecolorallocate($image, 191, 115, 0);
        //purple
        $color[4] = imagecolorallocate($image, 153, 0, 153);
        $dkcolor[4] = imagecolorallocate($image, 115, 0, 115);
        //ltblue
        $color[5] = imagecolorallocate($image, 0, 153, 198);
        $dkcolor[5] = imagecolorallocate($image, 0, 115, 149);
        //pink
        $color[6] = imagecolorallocate($image, 221, 68, 119);
        $dkcolor[6] = imagecolorallocate($image, 167, 51, 89);
        //ltgreen
        $color[7] = imagecolorallocate($image, 102, 170, 0);
        $dkcolor[7] = imagecolorallocate($image, 77, 128, 0);
        //red
        $color[8] = imagecolorallocate($image, 184, 46, 46);
        $dkcolor[8] = imagecolorallocate($image, 138, 35, 35);

        $text = 100;
        for($i=20;$i<=320;$i+=60){
            imageline ( $image, 80, $i, 700, $i, $gray);
            
            imagestring ( $image, 2, 50, $i - 5, ''.$text.'', $black);
            $text -= 20;
        }
        
        $w = 600 / $total - 20;
        $start = 100;
        
        for($i=0;$i<$total;$i++){
            $h = $values[$i] * 3;
            $end = $start + $w;
            
            imagefilledrectangle ( $image, $start, 320, $end, 320 - $h, $color[$i]);
            imagestring ( $image, 2, $start + 10, 320 - $h - 15, $legend[$i], $black);
            
            $start = $end + 20;
        }
        
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        
        imagepng($image, $uploadfile);
        imagedestroy($image);
        
        return $uploadfile;
    
    }
    
    public function pie_chart($data=null, $name=null, $folder=null){
        
        foreach($data as $key=>$value){
            $values[]=$value;
            $legend[] = $key;
        }
        
        //sort($values);
        $total = count($values);
        $data_sum = array_sum($values);
        
        // create image
        $image = imagecreate(800, 250);

        // allocate some colors
        $white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        $black    = imagecolorallocate($image, 0, 0, 0);
        $gray     = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
        
        //Blue
        $color[0] = imagecolorallocate($image, 51, 102, 204);
        $dkcolor[0] = imagecolorallocate($image, 38, 77, 153);
        //Orange
        $color[1] = imagecolorallocate($image, 220, 57, 18);
        $dkcolor[1] = imagecolorallocate($image, 165, 43, 14);
        //green
        $color[2] = imagecolorallocate($image, 16, 150, 24);
        $dkcolor[2] = imagecolorallocate($image, 12, 113, 18);
        //yellow
        $color[3] = imagecolorallocate($image, 255, 153, 0);
        $dkcolor[3] = imagecolorallocate($image, 191, 115, 0);
        //purple
        $color[4] = imagecolorallocate($image, 153, 0, 153);
        $dkcolor[4] = imagecolorallocate($image, 115, 0, 115);
        //ltblue
        $color[5] = imagecolorallocate($image, 0, 153, 198);
        $dkcolor[5] = imagecolorallocate($image, 0, 115, 149);
        //pink
        $color[6] = imagecolorallocate($image, 221, 68, 119);
        $dkcolor[6] = imagecolorallocate($image, 167, 51, 89);
        //ltgreen
        $color[7] = imagecolorallocate($image, 102, 170, 0);
        $dkcolor[7] = imagecolorallocate($image, 77, 128, 0);
        //red
        $color[8] = imagecolorallocate($image, 184, 46, 46);
        $dkcolor[8] = imagecolorallocate($image, 138, 35, 35);
        
        $start_angle[0] = 0;
        
        for($i=0; $i < $total; $i++){
            
            $values[$i] = ($values[$i] == 0) ? '.5' : $values[$i];
            
            $angle[$i] = (($values[$i] / $data_sum) * 360);
            
            $end_angle[$i] = $angle[$i] + $start_angle[$i];
            
            $start_angle[$i + 1] = $end_angle[$i];
        }
        
        // make the 3D effect
        for ($j = 125; $j > 100; $j--) {
            for($i=0; $i < $total; $i++){
                imagefilledarc($image, 200, $j, 320, 140, $start_angle[$i], $end_angle[$i], $dkcolor[$i], IMG_ARC_PIE);
            }
        }

        for($i=0; $i < $total; $i++){
            imagefilledarc($image, 200, 100, 320, 140, $start_angle[$i], $end_angle[$i], $color[$i], IMG_ARC_PIE);
        }
        
        $start = 40;
        $end = 60;
        $text = 45;
        
        for($i=0; $i < $total; $i++){
            imagefilledrectangle ( $image, 450, $start, 470, $end, $color[$i]);
            
            imagestring ( $image, 2, 475, $text, $legend[$i] .' - '. $values[$i], $black);
            
            $start += 30;
            $end += 30;
            $text += 30;
        }
        
        // flush image
        #$name = rand ( 10000 , 99999 );
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        imagepng($image, $uploadfile);
        imagedestroy($image);
        
        return $uploadfile;
    }
    
    public function line_chart($data=null, $name=null, $folder=null){
        foreach($data as $key=>$value){
            $values[]=$value;
            $legend[] = $key;
        }
        
        //sort($values);
        $total = count($values);
        $data_sum = array_sum($values);
        
        $image = imagecreate(800,350);
        // allocate some colors
        $white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
        
        //Blue
        $color[0] = imagecolorallocate($image, 51, 102, 204);
        $dkcolor[0] = imagecolorallocate($image, 38, 77, 153);
        //Orange
        $color[1] = imagecolorallocate($image, 220, 57, 18);
        $dkcolor[1] = imagecolorallocate($image, 165, 43, 14);
        //green
        $color[2] = imagecolorallocate($image, 16, 150, 24);
        $dkcolor[2] = imagecolorallocate($image, 12, 113, 18);
        //yellow
        $color[3] = imagecolorallocate($image, 255, 153, 0);
        $dkcolor[3] = imagecolorallocate($image, 191, 115, 0);
        //purple
        $color[4] = imagecolorallocate($image, 153, 0, 153);
        $dkcolor[4] = imagecolorallocate($image, 115, 0, 115);
        //ltblue
        $color[5] = imagecolorallocate($image, 0, 153, 198);
        $dkcolor[5] = imagecolorallocate($image, 0, 115, 149);
        //pink
        $color[6] = imagecolorallocate($image, 221, 68, 119);
        $dkcolor[6] = imagecolorallocate($image, 167, 51, 89);
        //ltgreen
        $color[7] = imagecolorallocate($image, 102, 170, 0);
        $dkcolor[7] = imagecolorallocate($image, 77, 128, 0);
        //red
        $color[8] = imagecolorallocate($image, 184, 46, 46);
        $dkcolor[8] = imagecolorallocate($image, 138, 35, 35);

        $text = 100;
        for($i=20;$i<=320;$i+=60){
            imageline ( $image, 80, $i, 700, $i, $gray);
            
            imagestring ( $image, 2, 50, $i - 5, ''.$text.'', $black);
            $text -= 20;
        }
        
        $w = 600 / $total;
        $start = 100;
        
        for($i=0;$i<$total;$i++){
            $h = $values[$i] * 3;
            $end = $start + $w;
            
            if($i + 1 == $total){
                imageline ( $image, $start, 320 - $h, $start, 320 - $h, $color[$i]);
                imagefilledrectangle ( $image, $start - 2, (320 - $h) - 2, $start + 2, (320 - $h) + 2,  $dkcolor[8]);
            }else{
                imageline ( $image, $start, 320 - $h, $end, 320 - ($values[$i + 1] * 3), $dkcolor[0]);
                imagefilledrectangle ( $image, $start - 2, (320 - $h) - 2, $start + 2, (320 - $h) + 2,  $dkcolor[8]);
            }
            
            imagestring ( $image, 2, $start + 10, 330, $legend[$i], $black);
            
            $start = $end;
        }
        
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        
        imagepng($image, $uploadfile);
        imagedestroy($image);
        
        return $uploadfile;
    }
    
    
    public function pie_chart_bak(){
        $values = array("2010" => 1950, "2011" => 750, "2012" => 2100, "2013" => 580, "2014" => 5000);
        $total = count($values);
        $data = ($total == 0) ? array(360) : array_values($values);
        $keys = ($total == 0) ? array("") : array_keys($values);
        $radius = 30;
        $imgx = 1800 + $radius;
        $imgy = 600 + $radius;
        $cx = 400 + $radius;
        $cy = 200 + $radius;
        $sx = 800;
        $sy = 400;
        $sz = 150;
        $data_sum = array_sum($data);
        $angle_sum = array(-1 => 0, 360);
        
        //$typo = $html->url('/webroot/font/helvetica.ttf');
        $typo = APP.'webroot/font/helvetica.ttf';
        $im = imagecreate($imgx, $imgy);
        imagecolorallocate($im, 255, 255, 255);
        $color = array(
            array(220, 20, 60),
            array(77, 33, 114),
            array(249, 141, 53),
            array(158, 37, 59),
            array(1, 128, 128),
            array(28, 94, 160),
            //array(206, 16, 118),
            array(43, 67, 86),
            //array(155, 108, 166),
            array(83, 69, 62)
        );
        shuffle($color);
        shuffle($color);
        shuffle($color);
        $colors = array(imagecolorallocate($im, $color[0][0], $color[0][1], $color[0][2]));
        $colord = array(imagecolorallocate($im, ($color[0][0] / 1.5), ($color[0][1] / 1.5), ($color[0][2] / 1.5)));
        $factorx = array();
        $factory = array();
        for($i = 0; $i < $total; $i++){
            $angle[$i] = (($data[$i] / $data_sum) * 360);
            $angle_sum[$i] = array_sum($angle);
            $colors[$i] = imagecolorallocate($im, $color[$i][0], $color[$i][1], $color[$i][2]);
            $colord[$i] = imagecolorallocate($im, ($color[$i][0] / 1.5), ($color[$i][1] / 1.5), ($color[$i][2] / 1.5));
            $factorx[$i] = cos(deg2rad(($angle_sum[$i - 1] + $angle_sum[$i]) / 2));
            $factory[$i] = sin(deg2rad(($angle_sum[$i - 1] + $angle_sum[$i]) / 2));
        }
        for($z = 1; $z <= $sz; $z++){
            for($i = 0; $i < $total; $i++){
                imagefilledarc($im, $cx + ($factorx[$i] * $radius), (($cy + $sz) - $z) + ($factory[$i] * $radius), $sx, $sy, $angle_sum[$i - 1], $angle_sum[$i], $colord[$i], IMG_ARC_PIE);
            }
        }
        for($i = 0; $i < $total; $i++){
            imagefilledarc($im, $cx + ($factorx[$i] * $radius), $cy + ($factory[$i] * $radius), $sx, $sy, $angle_sum[$i - 1], $angle_sum[$i], $colors[$i], IMG_ARC_PIE);
            imagefilledrectangle($im, 900, 50 + ($i * 50 * 2), 950, 100 + ($i * 50 * 2), $colors[$i]);
            imagettftext($im, 50, 0, 970, 100 + ($i * 50 * 2), imagecolorallocate($im, 0, 0, 0), $typo, $keys[$i]);
            imagettftext($im, 40, 0, $cx + ($factorx[$i] * ($sx / 4)) - 40, $cy + ($factory[$i] * ($sy / 4)) + 10, imagecolorallocate($im, 0, 0, 0), $typo, $data[$i]);
        }

        imagepng($im,"img/pie.png");
        imagedestroy($im);
    }
    
    public function percentile($rawscore=null, $name=null, $folder=null){
        
        if($rawscore <= 24){
            $rawscore = 24;
        }
        
        if($rawscore >= 47){
            $rawscore = 47;
        }
        
        $scoringArray = array(
            24=>array( 'y'=>'270.75', 'x'=>'25'),
            25=>array( 'y'=>'267.19', 'x'=>'34.50' ),
            26=>array( 'y'=>'266.00', 'x'=>'44.00' ),
            27=>array( 'y'=>'256.50', 'x'=>'53.50' ),
            28=>array( 'y'=>'249.38', 'x'=>'67.75' ),
            29=>array( 'y'=>'242.25', 'x'=>'82.00' ),
            30=>array( 'y'=>'235.12', 'x'=>'96.25' ),
            31=>array( 'y'=>'228.00', 'x'=>'110.50' ),
            32=>array( 'y'=>'213.75', 'x'=>'139.00' ),
            33=>array( 'y'=>'199.50', 'x'=>'167.50' ),
            34=>array( 'y'=>'185.25', 'x'=>'196.00' ),
            35=>array( 'y'=>'171.00', 'x'=>'224.50' ),
            36=>array( 'y'=>'156.75', 'x'=>'253.00' ),
            37=>array( 'y'=>'142.50', 'x'=>'281.50' ),
            38=>array( 'y'=>'128.25', 'x'=>'310.00' ),
            39=>array( 'y'=>'114.00', 'x'=>'338.50' ),
            40=>array( 'y'=>'99.75', 'x'=>'367.00' ),
            41=>array( 'y'=>'85.50', 'x'=>'395.50' ),
            42=>array( 'y'=>'71.25', 'x'=>'424.00' ),
            43=>array( 'y'=>'57.00', 'x'=>'452.50' ),
            44=>array( 'y'=>'42.75', 'x'=>'481.00' ),
            45=>array( 'y'=>'28.50', 'x'=>'509.50' ),
            46=>array( 'y'=>'07.13', 'x'=>'523.75' ),
            47=>array( 'y'=>'00.00', 'x'=>'538.00' )
        );
        
        
        $im = imagecreate(800,350);
       
        //Define Colors
        $white = imagecolorallocate($im, 255, 255, 255);    
        $red = imagecolorallocate($im, 224, 86, 81);    
        $black = imagecolorallocate($im, 0, 0, 0);
        $grey = imagecolorallocate($im, 140, 140, 140);
        $dkgreen = imagecolorallocate($im, 158, 180, 60);
        $ltgreen = imagecolorallocate($im, 79, 226, 138);
        
        $blue = imagecolorallocate($im, 63, 160, 183);
        $line = imagecolorallocate($im, 183, 102, 168);
        $backgroundtext = imagecolorallocate($im, 140, 140, 140);
        
        //box outline
        imagerectangle ( $im, 25, 0, 595, 285, $grey);
        
        
        imageline ( $im,25, 271, 510, 28, $ltgreen);
        
        imageline ( $im,220, 170, 510, 27, $dkgreen);
        imageline ( $im,221, 171, 510, 28, $dkgreen);
        imageline ( $im,222, 172, 510, 29, $dkgreen);
        
        imageline ( $im,25, 170, 223, 170, $dkgreen);
        imageline ( $im,25, 171, 223, 171, $dkgreen);
        imageline ( $im,25, 172, 223, 172, $dkgreen);
        //Fill In rectangle Based On Score
        //imagefilledrectangle ( $im, 2, 22, 5 * $rawscore, 38, $line);
        
        //x position ticks
        $pos = 53.5;
        for($i=1;$i<=20;$i++){
            
            imageline ( $im, $pos, 0, $pos, 285, $grey);
            $pos += 28.5;
        }
        
        //X Position number
        $pos = 0;
        $num = 100;
        for($i=1;$i<=10;$i++){
            imagestring ( $im , 3, 0, $pos, $num, $black);
            
            $pos += 28.5;
            $num -= 10;
        }
        
        //y position ticks
        $pos = 28.5;
        for($i=1;$i<=9;$i++){
            imageline ( $im, 20, $pos, 595, $pos, $grey);
            
            $pos += 28.5;
        }
        
        //Y position num
        imagestring ( $im , 3, 12, 285, "<24", $black);
        imagestring ( $im , 3, 46.5, 285, "27", $black);
        imagestring ( $im , 3, 75, 285, "29", $black);
        imagestring ( $im , 3, 103.5, 285, "31", $black);
        imagestring ( $im , 3, 132, 285, "32", $black);
        imagestring ( $im , 3, 160.5, 285, "33", $black);
        imagestring ( $im , 3, 189, 285, "34", $black);
        imagestring ( $im , 3, 217.5, 285, "35", $black);
        imagestring ( $im , 3, 246, 285, "36", $black);
        imagestring ( $im , 3, 274.5, 285, "37", $black);
        imagestring ( $im , 3, 303, 285, "38", $black);
        imagestring ( $im , 3, 331.5, 285, "39", $black);
        imagestring ( $im , 3, 360, 285, "40", $black);
        imagestring ( $im , 3, 388.5, 285, "41", $black);
        imagestring ( $im , 3, 417, 285, "42", $black);
        imagestring ( $im , 3, 445.5, 285, "43", $black);
        imagestring ( $im , 3, 474, 285, "44", $black);
        imagestring ( $im , 3, 502.5, 285, "45", $black);
        imagestring ( $im , 3, 531, 285, "47+", $black);
        
        //high retension
        imagerectangle ( $im, 125, 20, 300, 50, $black);
        imagefilledrectangle ( $im, 126, 21, 299, 49, $blue);
        imagestring ( $im , 5, 150, 30, "High Retention", $white);
        
        //low retention
        imagerectangle ( $im, 400, 200, 575, 235, $black);
        imagefilledrectangle ( $im, 401, 201, 574, 234, $blue);
        imagestring ( $im , 5, 425, 210, "Low Retention", $white);
        
        $y_val = $scoringArray[$rawscore]['y'];
        $x_val = $scoringArray[$rawscore]['x'];
        
        imageline ( $im, $x_val - 1, 285, $x_val - 1, $y_val, $red);
        imageline ( $im, $x_val, 285, $x_val, $y_val, $red);
        imageline ( $im, $x_val + 1, 285, $x_val + 1, $y_val, $red);
        
        imageline ( $im, 25, $y_val -1, $x_val, $y_val - 1, $red);
        imageline ( $im, 25, $y_val, $x_val, $y_val, $red);
        imageline ( $im, 25, $y_val + 1, $x_val, $y_val + 1, $red);
        
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        
        imagepng($im, $uploadfile);
        imagedestroy($im);
        
        return $uploadfile;
    }
    
    public function bar_graph($data=null, $name=null, $folder=null, $avg=null, $stdev=null){
        //$data = array('avg'=>5);
        
        //$rawscore = $data * 20;
        $rawscore = 10 * ( ( $data - $avg ) / $stdev ) + 50;
        //$rawscore = 1 + ($data - 1) * 99 / 4;
        $im = imagecreate(523,61);
        
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $red = imagecolorallocate($im, 224, 86, 81);
        
        //gray colors
        $gray = imagecolorallocate($im, 216, 212, 212);
        $dkgray = imagecolorallocate($im, 189, 181, 181);
        
        // blue colors
        $line = imagecolorallocate($im, 83, 102, 168);
        $dkblue = imagecolorallocate($im, 56, 64, 94);
        $ltblue = imagecolorallocate($im, 99, 104, 123);
                
        // set up array of points for polygon grey bar
        $values3 = array(
            0  => 0,    // x1
            1  => 20,   // y1
            2  => 20,   // x2
            3  => 0,      // y2
            4  => 520,  // x3
            5  => 0,    // y3
            6  => 500,  // x4
            7  => 20,   // y4
        );
    
        // set up array of points for polygon grey bar
        $values4 = array(
            0  => 500,  // x1
            1  => 20,   // y1
            2  => 520,  // x2
            3  => 0,      // y2
            4  => 520,  // x3
            5  => 25,   // y3
            6  => 500,  // x4
            7  => 45,   // y4
        );
            
        // set up array of points for polygon blue bar
        $averagerange = array(
            0  => 200 + 1,  // x1
            1  => 19,         // y1
            2  => 200 + 19, // x2
            3  => 1,            // y2
            4  => 300 + 19, // x3
            5  => 1,          // y3
            6  => 300,      // x4
            7  => 19,         // y4
        );
    
        // set up array of points for polygon blue bar
        $averagerangebottom = array(
            0  => 200,         // x1
            1  => 44,         // y1
            2  => 200 + 19, // x2
            3  => 26,            // y2
            4  => 300 + 19, // x3
            5  => 26,         // y3
            6  => 300,      // x4
            7  => 44,         // y4
        );
            
        // set up array of points for polygon blue bar
        $averagerangeback = array(
            0  => 200 + 20, // x1
            1  => 2,         // y1
            2  => 300 + 19, // x2
            3  => 2,           // y2
            4  => 300 + 19, // x3
            5  => 25,        // y3
            6  => 200 + 20, // x4
            7  => 25,        // y4
        );
            
        // set up array of points for polygon blue bar
        $values = array(
            0  => 2,                       // x1
            1  => 22,                   // y1
            2  => 22,                 // x2
            3  => 2,                     // y2
            4  => 5 * $rawscore + 20, // x3
            5  => 2,                    // y3
            6  => 5 * $rawscore + 2,  // x4
            7  => 22,                    // y4
        );
    
        // set up array of points for polygon blue bar
        $values2 = array(
            0  => 5 * $rawscore,    // x1
            1  => 22,     // y1
            2  => 5 * $rawscore + 20,   // x2
            3  => 2,       // y2
            4  => 5 * $rawscore + 20,   // x3
            5  => 23,    // y3
            6  => 5 * $rawscore,       // x4
            7  => 43,    // y4
        );
            
        $dashed=array($black, $black, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT ); 
    
        ImageSetStyle($im, $dashed); 
    
        //average range bottom
        #imagefilledpolygon($im, $averagerangebottom, 4, $gray );
    
        //average range back
        #imagefilledpolygon($im, $averagerangeback, 4, $dkgray );
    
        imageline ( $im, 20, 25, 520, 25, IMG_COLOR_STYLED);
    
        //3d y position ticks
        imageline ( $im, 120, 0, 120, 25, IMG_COLOR_STYLED);
        imageline ( $im, 220, 0, 220, 25, IMG_COLOR_STYLED);
        #imageline ( $im, 270, 0, 270, 25, IMG_COLOR_STYLED);
        imageline ( $im, 320, 0, 320, 25, IMG_COLOR_STYLED);
        imageline ( $im, 420, 0, 420, 25, IMG_COLOR_STYLED);
        imageline ( $im, 520, 0, 520, 25, IMG_COLOR_STYLED);
    
        //3d y position ticks
        imageline ( $im, 120, 25, 100, 45, IMG_COLOR_STYLED);
        imageline ( $im, 220, 25, 200, 45, IMG_COLOR_STYLED);
        #imageline ( $im, 270, 25, 250, 45, IMG_COLOR_STYLED);
        imageline ( $im, 320, 25, 300, 45, IMG_COLOR_STYLED);
        imageline ( $im, 420, 25, 400, 45, IMG_COLOR_STYLED);
        imageline ( $im, 520, 25, 500, 45, IMG_COLOR_STYLED);
    
        //blue bar
        imagefilledpolygon($im, $values, 4, $line );
    
        //3d box
        imagepolygon($im, $values3, 4, $black );
    
        //raw score
        imagefilledrectangle ( $im, 2, 22, $rawscore * 5, 43, $dkblue);
        imagefilledpolygon($im, $values2, 4, $ltblue );
    
        //box outline
        imagerectangle ( $im, 0, 20, 500, 45, $black);
    
        //average range top
        #imagefilledpolygon($im, $averagerange, 4, $dkgray );
    
        imagepolygon($im, $values4, 4, $black );
        
        //3d y position ticks
        imageline ( $im, 120, 0, 100, 20, $black);
        imageline ( $im, 220, 0, 200, 20, $black);
        #imageline ( $im, 270, 0, 250, 20, $black);
        imageline ( $im, 320, 0, 300, 20, $black);
        imageline ( $im, 420, 0, 400, 20, $black);
        imageline ( $im, 520, 0, 500, 20, $black);
        
        //y position ticks
        imageline ( $im, 100, 20, 100, 45, $black);
        imageline ( $im, 200, 20, 200, 45, $black);
        #imageline ( $im, 250, 20, 250, 45, $black);
        imageline ( $im, 300, 20, 300, 45, $black);
        imageline ( $im, 400, 20, 400, 45, $black);
        imageline ( $im, 500, 20, 500, 45, $black);
    
        //y position numbers
        imagestring ( $im , 3, 0, 50, "0", $black);
        imagestring ( $im , 3, 100, 50, "20", $black);
        imagestring ( $im , 3, 200, 50, "40", $black);
        #imagestring ( $im , 3, 250, 50, "50", $black);
        imagestring ( $im , 3, 300, 50, "60", $black);
        imagestring ( $im , 3, 400, 50, "80", $black);
        imagestring ( $im , 3, 500, 50, "100", $black);
    
        $rawscoreplot = round($rawscore, 2);
    
        imagestring ( $im , 2, 10, 24, "$rawscoreplot", $white);
        
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        
        imagepng($im, $uploadfile);
        imagedestroy($im);
        
        return $uploadfile;
    
    }
    
    function multi_user_overall_score($supervisor_total,$supervisor_count,$self_total,$self_count,$volunteer_total,$volunteer_count,$peer_total,$peer_count,$direct_report_total,$direct_report_count,$others_total,$others_count){
        $im     = imagecreate(623,72);
        $white  = imagecolorallocate($im, 255, 255, 255);
        $black  = imagecolorallocate($im, 0, 0, 0);
        $gray   = imagecolorallocate($im, 192, 192, 192);
    
        imagerectangle ( $im, 1, 1, 618, 51, $black);

        imagefilledrectangle ( $im, 619,    6,      621,    53,     $gray);
        imagefilledrectangle ( $im, 6,      52,     621,    54,     $gray);
    
        imagestring ( $im , 2, 100, 5, "Self(".$self_count.")"                      , $black);
        imagestring ( $im , 2, 155, 5, "Supervisor(".$supervisor_count.")"          , $black);
        imagestring ( $im , 2, 245, 5, "Peers(".$peer_count.")"                     , $black);
        imagestring ( $im , 2, 310, 5, "Direct Reports(".$direct_report_count.")"   , $black);
        imagestring ( $im , 2, 420, 5, "Volunteers(".$volunteer_count.")"           , $black);
        imagestring ( $im , 2, 500, 5, "Others(".$others_count.")"                 , $black);
    
        imagestring ( $im , 5, 5, 30, "Overall", $black);
    
        imagestring ( $im , 2, 120, 30, $self_total/100           , $black);
        imagestring ( $im , 2, 190, 30, $supervisor_total/100     , $black);
        imagestring ( $im , 2, 260, 30, $peer_total/100           , $black);
        imagestring ( $im , 2, 340, 30, $direct_report_total/100  , $black);
        imagestring ( $im , 2, 450, 30, $volunteer_total/100      , $black);
        imagestring ( $im , 2, 520, 30, $others_total/100         , $black);
            
        $new            = uniqid("",true);
        $randomNum      = substr($new, -8);
            
        $name = $randomNum.".png";

        imagepng($im,"companyimages/$name");
        imagedestroy($im);

        return $name;
    }
        
    function category_graph($data=null, $name=null, $folder=null){
        $height = ((count($data) - 1) * 30) + 47;
            
        $im = imagecreate(623,$height);
        
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $red = imagecolorallocate($im, 255, 0, 0);
        $orange = imagecolorallocate($im, 206, 99, 0);
        $gray = imagecolorallocate($im, 192, 192, 192);
       
        imagefilledrectangle ( $im, 620, 5, 622, $height - 1, $gray);
        imagefilledrectangle ( $im, 5, $height - 3, 622, $height - 1, $gray);
    
        imagerectangle ( $im, 1, 1, 619, $height - 4, $black);
        imagerectangle ( $im, 25, 15, 515, $height - 30, $black);
    
        $leftbg = array(
            0  => 15,           // x1
            1  => 25,           // y1
            2  => 25,           // x2
            3  => 15,           // y2
            4  => 25,           // x3
            5  => $height - 30, // y3
            6  => 15,           // x4
            7  => $height - 20, // y4
        );
    
        $bottom = array(
            0  => 25,           // x1
            1  => $height - 30, // y1
            2  => 515,          // x2
            3  => $height - 30, // y2
            4  => 505,          // x3
            5  => $height - 20, // y3
            6  => 15,           // x4
            7  => $height - 20, // y4
        );
    
        imagefilledpolygon($im, $leftbg, 4, $orange );
        imagepolygon($im, $leftbg, 4, $black );
    
        imagepolygon($im, $bottom, 4, $black );
    
        $line_height = 10;
        
        $color      = array ("9E8ABF","CF629C","71C7C1","71717D","68B36E");
        $ltcolor    = array ("9C7DDD","DF3694","50D9CF","8888A8","48CC50");
        $dkcolor    = array ("7D63A9","BF2E7B","42B4AC","424252","369A3D");
        $c = 0;
            
        foreach($data as $key=>$item){
            $R              = hexdec(substr($color[$c], 0, 2));
            $G              = hexdec(substr($color[$c], 2, 2));
            $B              = hexdec(substr($color[$c], 4, 2));

            $ltR            = hexdec(substr($ltcolor[$c], 0, 2));
            $ltG            = hexdec(substr($ltcolor[$c], 2, 2));
            $ltB            = hexdec(substr($ltcolor[$c], 4, 2));

            $dkR            = hexdec(substr($dkcolor[$c], 0, 2));
            $dkG            = hexdec(substr($dkcolor[$c], 2, 2));
            $dkB            = hexdec(substr($dkcolor[$c], 4, 2));

            $thiscolor      = imagecolorallocate($im,$R,$G,$B);
            $ltthiscolor    = imagecolorallocate($im,$ltR,$ltG,$ltB);
            $dkthiscolor    = imagecolorallocate($im,$dkR,$dkG,$dkB);
            
            if($key == 'Self'){
                $self_score = (100 * ($item - 3)) + 300;
            }else{
                $score = (100 * ($item - 3)) + 300;
            
                $top = array(
                    0  => 15,                       // x1
                    1  => $line_height      + 16,   // y1
                    2  => 25,                       // x2
                    3  => $line_height      + 6,    // y2
                    4  => $score + 25,   // x3
                    5  => $line_height      + 6,    // y3
                    6  => $score + 15,   // x4
                    7  => $line_height      + 16,   // y4
                );
        
                // set up array of points for polygon blue bar
                $front = array(
                    0  => $score + 15,   // x1
                    1  => $line_height      + 16,   // y1
                    2  => $score + 25,   // x2
                    3  => $line_height      + 6,    // y2
                    4  => $score + 25,   // x3
                    5  => $line_height      + 26,   // y3
                    6  => $score + 15,   // x4
                    7  => $line_height      + 36,   // y4
                );    
                
                //blue bar
                imagefilledpolygon($im, $top, 4, $dkthiscolor );
                imagepolygon($im, $top, 4, $black );
        
                //raw score
                imagefilledrectangle ( $im, 15, $line_height + 16, $score + 15, $line_height + 36, $thiscolor );
                imagerectangle ( $im, 15, $line_height + 16, $score + 15, $line_height + 36, $black);
        
                imagefilledpolygon($im, $front, 4,  $ltthiscolor );
                imagepolygon($im, $front, 4, $black );
                
                $string = $score / 100;
                
                imagestring ( $im , 2, $score - 10, $line_height + 20, "$string", $black);
                
                imagestring ( $im , 2, 30, $line_height + 20, $key, $white);
                
                $line_height += 30;
                $c++;
            }
        }
            
        $dashed=array($black, $black, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT ); 
    
        ImageSetStyle($im, $dashed); 
    
        //3d y position ticks
        imageline ( $im, 115, 15, 115, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 215, 15, 215, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 315, 15, 315, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 415, 15, 415, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im, 515, 15, 515, $height - 30, IMG_COLOR_STYLED);
    
        imageline ( $im, 115, $height - 30, 105, $height - 20, $black);
        imageline ( $im, 215, $height - 30, 205, $height - 20, $black);
        imageline ( $im, 315, $height - 30, 305, $height - 20, $black);
        imageline ( $im, 415, $height - 30, 405, $height - 20, $black);
        imageline ( $im, 515, $height - 30, 505, $height - 20, $black);
    
        //y position numbers
        imagestring ( $im , 3, 10, $height - 17, "0", $black);
        imagestring ( $im , 3, 100, $height - 17, "1", $black);
        imagestring ( $im , 3, 200, $height - 17, "2", $black);
        imagestring ( $im , 3, 300, $height - 17, "3", $black);
        imagestring ( $im , 3, 400, $height - 17, "4", $black);
        imagestring ( $im , 3, 500, $height - 17, "5", $black);
    
        $reddashed=array($red, $red, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT ); 
    
        ImageSetStyle($im, $reddashed); 
        
        $string = $self_score / 100;
        imageline ( $im, $self_score + 15, 15, $self_score + 15, $height - 30, IMG_COLOR_STYLED);
        imageline ( $im,$self_score + 15, $height - 30, $self_score + 5, $height - 20, IMG_COLOR_STYLED);
        imagestring ( $im , 3, $self_score, 2, "Self ( $string )", $red);
            
        $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
        $uploadfile = $folder. "/" . $name.".png";
        
        imagepng($im, $uploadfile);
        imagedestroy($im);
        
        return $uploadfile;
    }
    
    function create_multi_bar_graph($data=null, $name=null, $folder=null){
        $h = count($data) * 30 + 40;
        $w = 623;
        
        $im             = imagecreate($w,$h);
            $white          = imagecolorallocate($im, 255, 255, 255);
            $black          = imagecolorallocate($im, 0, 0, 0);
            $red            = imagecolorallocate($im, 255, 0, 0);
            $orange         = imagecolorallocate($im, 206, 99, 0);
            $orangeBG       = imagecolorallocate($im, 255, 204, 0);
            $red            = imagecolorallocate($im, 239, 0, 0);
            $ltred          = imagecolorallocate($im, 202, 0, 0);
            $dkred          = imagecolorallocate($im, 165, 0, 0);

            $Color5         = imagecolorallocate($im, 0, 0, 239);
            $line           = imagecolorallocate($im, 83, 102, 168);
            $dkColor5       = imagecolorallocate($im, 0, 0, 165);
            $ltColor5       = imagecolorallocate($im, 0, 0, 202);

            $gray           = imagecolorallocate($im, 192, 192, 192);
            $ltgray         = imagecolorallocate($im, 230, 230, 230);

            imageline ( $im, 25, 15, 515, 15, $black);

            $leftbg = array(
                0  => 15,           // x1
                1  => 25,           // y1
                2  => 25,           // x2
                3  => 15,           // y2
                4  => 25,           // x3
                5  => $h - 30,      // y3
                6  => 15,           // x4
                7  => $h - 20,      // y4
            );

            $bottom = array(
                0  => 25,           // x1
                1  => $h - 30,      // y1
                2  => 515,          // x2
                3  => $h - 30,      // y2
                4  => 505,          // x3
                5  => $h - 20,      // y3
                6  => 15,           // x4
                7  => $h - 20,      // y4
            );

            imagefilledpolygon($im, $leftbg, 4, $orange );
            imagepolygon($im, $leftbg, 4, $black );
            imagepolygon($im, $bottom, 4, $black );
            
            $dashed = array($black, $black, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT );

            ImageSetStyle($im, $dashed);

            //3d y position ticks
            imageline ( $im, 115, 15, 115, $h - 30, IMG_COLOR_STYLED);
            imageline ( $im, 215, 15, 215, $h - 30, IMG_COLOR_STYLED);
            imageline ( $im, 315, 15, 315, $h - 30, IMG_COLOR_STYLED);
            imageline ( $im, 415, 15, 415, $h - 30, IMG_COLOR_STYLED);

            imageline ( $im, 515, 15, 515, $h - 30, $black);
            imageline ( $im, 115, $h - 30, 105, $h - 20, $black);
            imageline ( $im, 215, $h - 30, 205, $h - 20, $black);
            imageline ( $im, 315, $h - 30, 305, $h - 20, $black);
            imageline ( $im, 415, $h - 30, 405, $h - 20, $black);
            imageline ( $im, 515, $h - 30, 505, $h - 20, $black);

            //y position numbers
            
            imagestring ( $im , 3, 20, 0, "0", $black);
            imagestring ( $im , 3, 110, 0, "20", $black);
            imagestring ( $im , 3, 210, 0, "40", $black);
            imagestring ( $im , 3, 310, 0, "60", $black);
            imagestring ( $im , 3, 410, 0, "80", $black);
            imagestring ( $im , 3, 510, 0, "100", $black);

            imagestring ( $im , 3, 10, $h - 17, "0", $black);
            imagestring ( $im , 3, 100, $h - 17, "20", $black);
            imagestring ( $im , 3, 200, $h - 17, "40", $black);
            imagestring ( $im , 3, 300, $h - 17, "60", $black);
            imagestring ( $im , 3, 400, $h - 17, "80", $black);
            imagestring ( $im , 3, 500, $h - 17, "100", $black);
            
            $lineheight = 10;

            $c = 0;

            $color      = array ("2DA40A","B0B220","B0550E","A5100C","89B566","409CD3","EC814F","71717D","71C7C1","CF629C","9E8ABF","C44056","405B80","BFDDED","6A5183","40959D","8A4056","408F68","828CB0","FFCE40","A7C5AA","FFDD92","A5B9C4","D6D68C","C5BFED","89B566","D6F09E","D6AAC1","5498D3");
            $ltcolor    = array ("75F65B","F5F861","F6AA5E","F9665F","85CD44","0091E1","F25A15","8888A8","50D9CF","DF3694","9C7DD4","D70026","004BAA","B2DEF3","692DAC","00B2BD","B10039","00B458","778CCA","FFD154","A8D8AB","FFDE9E","A6C7D7","E3E274","BAB2F3","85CD44","D2F583","E3A1C4","208BE1");
            $dkcolor    = array ("2A9E07","9C9E00","A05909","A5050D","629C33","007BC4","E65714","424252","42B4AC","BF2E7B","7D63A9","B0001E","002455","A9D1E7","38175A","00727C","63001E","006A35","586695","FFBD00","8AB28D","FFD16E","87A2B0","C8C866","B2A9E7","629C33","C8EB7D","C88DAC","1B75C4");
            
            while (list($key, $origVal) = each($data))
            {
                $R              = hexdec(substr($color[$c], 0, 2));
                $G              = hexdec(substr($color[$c], 2, 2));
                $B              = hexdec(substr($color[$c], 4, 2));

                $ltR            = hexdec(substr($ltcolor[$c], 0, 2));
                $ltG            = hexdec(substr($ltcolor[$c], 2, 2));
                $ltB            = hexdec(substr($ltcolor[$c], 4, 2));

                $dkR            = hexdec(substr($dkcolor[$c], 0, 2));
                $dkG            = hexdec(substr($dkcolor[$c], 2, 2));
                $dkB            = hexdec(substr($dkcolor[$c], 4, 2));

                $thiscolor      = imagecolorallocate($im,$R,$G,$B);
                $ltthiscolor    = imagecolorallocate($im,$ltR,$ltG,$ltB);
                $dkthiscolor    = imagecolorallocate($im,$dkR,$dkG,$dkB);
                
                $string         = ROUND(10 * $origVal + 50);
                
                $val            = 5 * $string;
                
                
                //echo $val.'<br>';
                $CatName        = "$key";

                $top = array(
                    0  => 15,              // x1
                    1  => $lineheight + 16,     // y1
                    2  => 25,             // x2
                    3  => $lineheight + 6,      // y2
                    4  => $val + 25,             // x3
                    5  => $lineheight + 6,        // y3
                    6  => $val + 15,            // x4
                    7  => $lineheight + 16,        // y4
                );

                // set up array of points for polygon blue bar
                $front = array(
                    0  => $val + 15,           // x1
                    1  => $lineheight + 16,     // y1
                    2  => $val + 25,          // x2
                    3  => $lineheight + 6,      // y2
                    4  => $val + 25,          // x3
                    5  => $lineheight + 26,        // y3
                    6  => $val + 15,           // x4
                    7  => $lineheight + 36,        // y4
                );

                //blue bar
                imagefilledpolygon($im, $top, 4, $dkthiscolor );
                imagepolygon($im, $top, 4, $black );

                //raw score
                imagefilledrectangle ( $im, 15, $lineheight + 16, $val + 15, $lineheight + 36, $thiscolor);
                imagerectangle ( $im, 15, $lineheight + 16, $val + 15, $lineheight + 36, $black);
                imagefilledpolygon($im, $front, 4, $ltthiscolor );
                imagepolygon($im, $front, 4, $black );

                imagestring ( $im , 3, $val - 10 , $lineheight + 20, "$string", $white);
                imagestring ( $im , 2, 18, $lineheight + 20, "$CatName", $black);

                $lineheight = $lineheight + 30;

                $c++;
            }

            $folder = (is_null($folder)) ? AuthComponent::user('DetailUser.uploadDir') : $folder ;    
            $uploadfile = $folder. "/" . $name.".png";
        
            imagepng($im, $uploadfile);
            imagedestroy($im);
        
            return $uploadfile;
    }
    
    function createGraphPattern($w='523',$h='81',$rawNum='',$min_score='40',$max_score='60'){
        $min_range = $min_score * 5;
        $max_range = $max_score * 5;
            
        $im     = imagecreate($w,$h);

        $white  = imagecolorallocate($im, 255, 255, 255);
        $black  = imagecolorallocate($im, 0, 0, 0);
        $red    = imagecolorallocate($im, 255, 0, 0);

        //gray colors
        $gray   = imagecolorallocate($im, 216, 212, 212);
        $dkgray = imagecolorallocate($im, 189, 181, 181);

        // blue colors
        $line   = imagecolorallocate($im, 83, 102, 168);
        $dkblue = imagecolorallocate($im, 56, 64, 94);
        $ltblue = imagecolorallocate($im, 99, 104, 123);

        // set up array of points for polygon grey bar
        $values3 = array(
            0  => 0,                    // x1
            1  => 20,                   // y1
            2  => 20,                   // x2
            3  => 0,                    // y2
            4  => 520,                  // x3
            5  => 0,                    // y3
            6  => 500,                  // x4
            7  => 20,                   // y4
        );

        // set up array of points for polygon grey bar
        $values4 = array(
            0  => 500,                  // x1
            1  => 20,                   // y1
            2  => 520,                  // x2
            3  => 0,                    // y2
            4  => 520,                  // x3
            5  => 25,                   // y3
            6  => 500,                  // x4
            7  => 45,                   // y4
        );

        // set up array of points for polygon blue bar
        $averagerange = array(
            0  => $min_range + 1,              // x1
            1  => 19,                   // y1
            2  => $min_range + 19,             // x2
            3  => 1,                    // y2
            4  => $max_range + 19,             // x3
            5  => 1,                    // y3
            6  => $max_range,                  // x4
            7  => 19,                   // y4
        );

        // set up array of points for polygon blue bar
        $averagerangebottom = array(
            0  => $min_range,                  // x1
            1  => 44,                   // y1
            2  => $min_range + 19,             // x2
            3  => 26,                   // y2
            4  => $max_range + 19,             // x3
            5  => 26,                   // y3
            6  => $max_range,                  // x4
            7  => 44,                   // y4
        );

        // set up array of points for polygon blue bar
        $averagerangeback = array(
            0  => $min_range + 20,             // x1
            1  => 2,                    // y1
            2  => $max_range + 19,             // x2
            3  => 2,                    // y2
            4  => $max_range + 19,             // x3
            5  => 25,                   // y3
            6  => $min_range + 20,             // x4
            7  => 25,                   // y4
        );

        // set up array of points for polygon blue bar
        $values = array(
            0  => 2,                    // x1
            1  => 22,                   // y1
            2  => 22,                   // x2
            3  => 2,                    // y2
            4  => 5 * $rawNum + 20,     // x3
            5  => 2,                    // y3
            6  => 5 * $rawNum + 2,      // x4
            7  => 22,                   // y4
        );

        // set up array of points for polygon blue bar
        $values2 = array(
            0  => 5 * $rawNum,          // x1
            1  => 22,                   // y1
            2  => 5 * $rawNum + 20,     // x2
            3  => 2,                    // y2
            4  => 5 * $rawNum + 20,     // x3
            5  => 23,                   // y3
            6  => 5 * $rawNum,          // x4
            7  => 43,                   // y4
        );

        $dashed=array($black, $black, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT );

        ImageSetStyle($im, $dashed);

        //average range bottom
        imagefilledpolygon($im, $averagerangebottom, 4, $gray );

        //average range back
        imagefilledpolygon($im, $averagerangeback, 4, $dkgray );
        imageline ( $im, 20, 25, 520, 25, IMG_COLOR_STYLED);

        //3d y position ticks
        imageline ( $im, 120, 0, 120, 25, IMG_COLOR_STYLED);
        imageline ( $im, 220, 0, 220, 25, IMG_COLOR_STYLED);
        imageline ( $im, 320, 0, 320, 25, IMG_COLOR_STYLED);
        imageline ( $im, 420, 0, 420, 25, IMG_COLOR_STYLED);
        imageline ( $im, 520, 0, 520, 25, IMG_COLOR_STYLED);
            
        imageline ( $im, $min_range + 20, 0, $min_range + 20, 25, IMG_COLOR_STYLED);
        imageline ( $im, $max_range + 20, 0, $max_range + 20, 25, IMG_COLOR_STYLED);

        //3d y position ticks
        imageline ( $im, 120, 25, 100, 45, IMG_COLOR_STYLED);
        imageline ( $im, 220, 25, 200, 45, IMG_COLOR_STYLED);
        imageline ( $im, 320, 25, 300, 45, IMG_COLOR_STYLED);
        imageline ( $im, 420, 25, 400, 45, IMG_COLOR_STYLED);
        imageline ( $im, 520, 25, 500, 45, IMG_COLOR_STYLED);
            
        imageline ( $im, $min_range + 20, 25, $min_range, 45, IMG_COLOR_STYLED);
        imageline ( $im, $max_range + 20, 25, $max_range, 45, IMG_COLOR_STYLED);
            
        //blue bar
        imagefilledpolygon($im, $values, 4, $line );

        //3d box
        imagepolygon($im, $values3, 4, $black );

        //raw score
        imagefilledrectangle ( $im, 2, 22, $rawNum * 5, 43, $dkblue);
        imagefilledpolygon($im, $values2, 4, $ltblue );

        //box outline
        imagerectangle ( $im, 0, 20, 500, 45, $black);

        //average range top
        imagefilledpolygon($im, $averagerange, 4, $dkgray );
        imagepolygon($im, $values4, 4, $black );

        //3d y position ticks
        imageline ( $im, 120, 0, 100, 20, $black);
        imageline ( $im, 220, 0, 200, 20, $black);
        imageline ( $im, 320, 0, 300, 20, $black);
        imageline ( $im, 420, 0, 400, 20, $black);
        imageline ( $im, 520, 0, 500, 20, $black);
            
        imageline ( $im, $min_range + 20, 0, $min_range, 20, $red);
        imageline ( $im, $max_range + 20, 0, $max_range, 20, $red);
            
        //y position ticks
        imageline ( $im, 100, 20, 100, 45, $black);
        imageline ( $im, 200, 20, 200, 45, $black);
        imageline ( $im, 300, 20, 300, 45, $black);
        imageline ( $im, 400, 20, 400, 45, $black);
        imageline ( $im, 500, 20, 500, 45, $black);
            
        imageline ( $im, $min_range, 20, $min_range, 45, $red);
        imageline ( $im, $max_range, 20, $max_range, 45, $red);

        //y position numbers
        imagestring ( $im , 3, 0, 50, "0", $black);
        imagestring ( $im , 3, 100, 50, "20", $black);
        imagestring ( $im , 3, 200, 50, "40", $black);
        imagestring ( $im , 3, 300, 50, "60", $black);
        imagestring ( $im , 3, 400, 50, "80", $black);
        imagestring ( $im , 3, 500, 50, "100", $black);
            
        imagestring ( $im , 3, $min_range, 60, $min_score, $red);
        imagestring ( $im , 3, $max_range, 60, $max_score, $red);

        $rawscoreplot = round($rawNum);

        if($rawscoreplot < $min_score || $rawscoreplot > $max_score ) { 
            $thiscolor = $red; 
        }else{ 
            $thiscolor = $white; 
        }

        imagestring ( $im , 2, 10, 24, "$title ( $rawscoreplot )", $thiscolor);

        $new            = uniqid("",true);
        $randomNum      = substr($new, -8);

        $name = $randomNum.".png";

        imagepng($im,"companyimages/$name");
        imagedestroy($im);

        return $name;
    }
}
