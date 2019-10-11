<?php
   $score = 1;
   $size = "small";
   $b = 100;
   $r = array(-40, 40);
   if ($size == "small") {
       $v = 5;
       $m = array(1.001, 1.01, 1.02, 1.001);
   } else if ($size == "medium") {
       $v = 5;
       $m = array(1.001, 1.01, 1.02, 1.001);
   } else {
       $v = 5;
       $m = array(1.001, 1.01, 1.02, 1.001);
   }
   
   $s = 0 - $score;
   $rand_b = (((mt_rand(0,1000)/1000) * ($v * 2)) - $v) + $b;
   if ($s <= $r[0]) {
        $p = $rand_b * pow($m[0], $s - $r[0]) * pow($m[1], $r[0]);
    } else if ($s > $r[0] && $s <= 0) {
        $p = $rand_b * pow($m[1], $s);
    } else if ($s > 0 && $s <= $r[1]) {
        $p = $rand_b * pow($m[2], $s);
    } else if ($s > $r[1]) {
        $p = $rand_b * pow($m[3], $s - $r[1]) * pow($m[3], $r[1]);
    }
    
    echo 'score:'.$s . ' | rand_b:'.$rand_b . ' | price:' . $p;
