
<?php 

snippet('header') ;

if (isset($_POST['score']) && isset($_POST['id'])) {
   $score = intval(filter_input(INPUT_POST, "score", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW)); 
   $id = intval(filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW)); 
   $trophy = $pages->findBy('id', $id);
   $size = $trophy->size();
   $b = $trophy->price()->toInt();
   $r = array($site->likely_range_low()->toInt(), $site->likely_range_high()->toInt());
   if ($size == "small") {
       $v = $site->small_trophy_variance()->toInt();
       $m = array( $site->small_gradient_1()->toInt(), 
            $site->small_gradient_2()->toInt(), 
            $site->small_gradient_3(), 
            $site->small_gradient_4()->toInt());
   } else if ($size == "medium") {
       $v = $site->medium_trophy_variance()->toInt();
       $m = array( $site->medium_gradient_1()->toInt(), 
            $site->medium_gradient_2()->toInt(), 
            $site->medium_gradient_3()->toInt(), 
            $site->medium_gradient_4()->toInt());
   } else {
       $v = $site->large_trophy_variance()->toInt();
       $m = array( $site->large_gradient_1()->toInt() , 
            $site->large_gradient_2()->toInt(), 
            $site->large_gradient_3(), 
            $site->large_gradient_4()->toInt());
   }
   
   foreach ($m as &$value) {
       $value = 1 + $value / 100;
   }
   unset($value); 
   
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
    
    $p = round($p, 2);
}

?>

<section id="results-screen" class="screen results-screen">
  <div class="screen-content">
    <h1 class="main-title screen-title"><?= $page->subtitle();?></h1>

    <div class="answers">
      <?= $page->body_text()->kirbyText();?>
      <ul id="results-list" class="answers invisible">

      </ul>

      <div class="prices-wrapper">
        <?= $score;?><br><?= $b;?>
        <p>Your personalised price valid for today:</p>
        <p class="xl-text" id="results-price"><?php echo $p;?></p>
      </div>
    </div>
  </div>

  <div class="buttons">
    <form id="payment_submit" action=''method='post'>
    <a data-href="<?= $pages->find('pay-cashier')->url(); ?>" class="button left pay_cashier" id="pay_cashier">Pay at Cashier</a>
    <a data-href="<?= $pages->find('online-payment-screen')->url(); ?>" class="button right pay_online" id="pay_online">Pay Online</a>
    <input type="hidden" name="final-price" value="$p">
    </form>
  </div>

</section>


<?php snippet('footer') ?>
