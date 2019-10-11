<?php snippet('header');
  $payment = null;

  if (isset($_POST['payment_id'])) {
      $payment = 'success';
  } else {
      $payment = 'failed';
  }

  if($payment == 'success'): 
      $title = $page->success_subtitle()->kirbyText();
      $body = $page->success_body_text()->kirbyText();
  elseif($payment == 'failed'):
      $title = $page->failed_subtitle()->kirbyText();
      $body = $page->failed_body_text()->kirbyText();
  else:
      $title = 'System Error';
      $body = '<p>This is page is not currently working.</p>';
  endif;
?>

<section id="online-payment-screen" class="screen online-payment-screen selected">
  <div class="screen-content">
    <h2 class="screen-title large-text"><?= $title;?></h2>
    <div class="answers">
      <p>Your purchase number is:</p>
      <p id="payment-number" class="xl-text">
      <?php if (isset($_POST['id'])) {
          $id = filter_input(INPUT_POST, "customer_id");
          $db = new SQLite3('assets/db/simple_postcode.db', SQLITE3_OPEN_READWRITE);
          $statement = $db->prepare("UPDATE results set paid=1 WHERE id=:id");
          $statement->bindParam(':id', $id);
          $statement->execute();
          echo $id;
      } ?>
      </p>
    </div>
    <div class="answers">
      <?= $body;?>
    </div>
  </div>
  <div class="buttons">
    <a href="<?= $site->url();?>" class="left button">Restart</a>
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

<?php snippet('footer') ?>
