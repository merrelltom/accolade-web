
<?php 

snippet('header') ;
//Is trophy available?:
$db = new SQLite3('assets/db/simple_postcode.db', SQLITE3_OPEN_READWRITE);
$statement = $db->prepare("SELECT count(*) from results where date_time = ? and paid = 1");
date_default_timezone_set("Europe/London");
$timestamp = date("Y/m/d");
$statement->bindValue(1, $timestamp);
$result = $statement->execute();
$paid_today = $result->fetchArray();
if ($paid_today[0] == 0) {  

    if (isset($_POST['score']) && isset($_POST['id'])) {

    //    Price Calculation:
    //    1. Get Variables
    //    
       $score = intval(filter_input(INPUT_POST, "score", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW)); 
       $trophy_id = intval(filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW)); 
       $trophy = $pages->findBy('id', $trophy_id);
       $size = $trophy->size();
       $b = $trophy->price()->toInt();
       $r = array($site->likely_range_low()->toInt(), $site->likely_range_high()->toInt());
       if ($size == "small") {
           $v = $site->small_trophy_variance()->toInt();
           $m = array( $site->small_gradient_1()->toInt(), 
                $site->small_gradient_2()->toInt(), 
                $site->small_gradient_3()->toInt(), 
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
                $site->large_gradient_3()->toInt(), 
                $site->large_gradient_4()->toInt());
       }

       foreach ($m as &$value) {
           $value = 1 + $value / 100;
       }
       unset($value); 
    //   
    //   2. Generate Price:
    //   
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
        $shipping = $site->shipping_price()->toInt();
        $total_price = $p + $shipping;
    //  
    //    Insert into database:
    //    
        $statement = $db->prepare("INSERT INTO results(date_time, price, trophy_size, trophy_id, paid) "
                . "VALUES(:timestamp, :price, :size, :id, 0)");
        $statement->bindParam(':price', $p);
        $statement->bindParam(':size', $size);
        $statement->bindParam(':id', $trophy_id);
        date_default_timezone_set("Europe/London");
        $timestamp = date("Y/m/d");
        $statement->bindParam(':timestamp', $timestamp);
        $statement->execute();
        $customer_id = $db->lastInsertRowID();
    //
    //  Stripe create payment intent
    //
        require_once('assets/php/stripe-php/init.php'); ?>
        <?php
           require_once('assets/php/stripe_env.php');
            \Stripe\Stripe::setApiKey('sk_test_5uh8JHy65XEfqeZjrHmMqczn00Tmhto1Vt');
    //    \Stripe\Stripe::setApiKey($sk);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int)$total_price * 100,
            'currency' => 'gbp',
            'description' => 'ID:' . $customer_id,
        ]);
    }

    ?>

    <section id="results-screen" class="screen results-screen">
      <div class="screen-content">
        <h1 class="main-title screen-title"><?= $page->subtitle();?></h1>

        <?php if($page->body_text()):?>
          <div class="answers">
            <?= $page->body_text()->kirbyText();?>
          </div>
        <?php endif;?>

        <div class="answers">
            <p>Your personalised price:<br><br></p>
            <hr>
            <ul>
              <li id="results-price">Trophy Price: £<?php echo $p;?></li>
              <li>Shipping: £<?php echo $shipping;?></li>
              <li><b>Total: £<?php echo $total_price;?></b></li>
            </ul>
        </div>


        <div class=" ">
          <form action="./online-payment-end-screen" method="post" id="payment-form" class="answers">
              <h3 class="subtitle">Payment Details:</h3><br>
              <div class="input-wrapper"> 
                <input id="cardholder-name" type="text" class="payment-form-input" placeholder="Carholder's Name">
              </div>

              <div class="input-wrapper">
                <input id="cardholder-email" type="text" class="payment-form-input" placeholder="Email Address">
              </div>

              <div class="input-wrapper">
                <input id="cardholder-address1" required type="text" class="payment-form-input" placeholder="Address Line 1">
              </div>        
              <div class="input-wrapper">
                <input id="cardholder-address2" type="text" class="payment-form-input" placeholder="Address Line 2">
              </div>        
              <div class="input-wrapper">
                <input id="cardholder-city" type="text" class="payment-form-input" placeholder="City">
              </div>
              <div class="input-wrapper">
                <input id="cardholder-county" type="text" class="payment-form-input" placeholder="County">
              </div>
              <div class="input-wrapper">
                <input id="cardholder-postcode" required type="text" class="payment-form-input" placeholder="Postcode">
              </div>
              <div class="input-wrapper">
                <input id="cardholder-phone" type="text" class="payment-form-input" placeholder="Contact telephone number">
              </div>

              <input name="customer_id" type="hidden" value="<?php echo $customer_id; ?>">
              <!-- placeholder for Elements -->
              <div id="card-element" class="card-wrapper"></div>
              <!-- Used to display form errors. -->
              <div id="card-errors" role="alert"></div>

              <button id="card-button" class="payment-submit-button" data-secret="<?= $intent->client_secret ?>">
                Submit Payment
              </button>
          </form>
        </div>
      </div>

    </section>

<?= js(['assets/js/stripe_pay.js']);
} else {
    echo "Trophy already purchased";
}              
?>
<?php snippet('footer') ?>
