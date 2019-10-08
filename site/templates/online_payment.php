<?php
    if (isset($_POST['trophy-size']) && isset($_POST['final-price'])) {
        $size = filter_input(INPUT_POST, "trophy-size"); 
        $price = filter_input(INPUT_POST, "final-price");
        $db_path = 'assets/db/simple_postcode.db';
        $db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
        $statement = $db->prepare("INSERT INTO results(date_time, price, trophy_size, paid) VALUES(:timestamp, :price, :size, 0)");
        $statement->bindParam(':price', $price);
        $statement->bindParam(':size', $size);
        date_default_timezone_set("Europe/London");
        $timestamp = date("Y/m/d H:i");
        $statement->bindParam(':timestamp', $timestamp);
        $statement->execute();
        $id = $db->lastInsertRowID();

        //  Stripe create payment intent
        require_once('assets/php/stripe-php/init.php'); ?>
        <section id="online-payment-screen" class="screen online-payment-screen selected">
              <div class="screen-content">
        <?php
           require_once('assets/php/stripe_env.php');
//        \Stripe\Stripe::setApiKey('sk_test_5uh8JHy65XEfqeZjrHmMqczn00Tmhto1Vt');
        \Stripe\Stripe::setApiKey($sk);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int)$price * 100,
            'currency' => 'gbp',
            'description' => 'ID:' . $id,
        ]);
            
        //  Generate Page

          snippet('header') 
         ?>
          <h2 class="screen-title large-text">Online Payment</h2>
            <div class="answers">
              <h3 class="subtitle">Order Number: <?php echo $id; ?></h3>
              <ul>
                <li>Trophy Size: <?= $size;?></li>
                <li>Price: £<?= $price;?></li>   
              </ul>
            </div> 
            <form action="./online-payment-end-screen" method="post" id="payment-form" class="answers">
                <h3 class="subtitle">Payment Details:</h3><br>
                <div class="input-wrapper"> 
                  <input id="cardholder-name" type="text" class="payment-form-input" placeholder="Carholder's Name">
                </div>

                <div class="input-wrapper">
                  <input id="cardholder-email" type="text" class="payment-form-input" placeholder="Email Address">
                </div>

                <input name="id" type="hidden" value="<?php echo $id; ?>">
                <!-- placeholder for Elements -->
                <div id="card-element" class="card-wrapper"></div>
                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>

                <button id="card-button" class="payment-submit-button" data-secret="<?= $intent->client_secret ?>">
                  Submit Payment
                </button>
            </form>
<?php
    }
?>

     </div>
        </section>

<div class="restart-wrapper">
  <div class="restart-alert">
    <div class="restart-alert-inner">
      <h3>Timed Out</h3>
      <p>Do you wish to continue?</p>
      <div class="button-group">
        <a class="button continue">Continue</a><a href="<?= $site->url();?>" id="restart" class="button restart">Restart</a>
      </div>
    </div>
  </div>
</div>
<?= js(['assets/js/stripe_pay.js']) ?>
<?php snippet('footer') ?>
