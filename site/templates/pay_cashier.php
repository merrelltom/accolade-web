<?php snippet('header'); 
    if (isset($_POST['trophy-size']) && isset($_POST['final-price'])) {
        $size = filter_input(INPUT_POST, "trophy-size"); 
        $price = filter_input(INPUT_POST, "final-price");
        $db_path = 'assets/db/simple_postcode.db';
        $db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
        $statement = $db->prepare("INSERT INTO results(price, trophy_size, paid) VALUES(:price, :size, 0)");
        $statement->bindParam(':price', $price);
        $statement->bindParam(':size', $size);
        $statement->execute();
        $id = $db->lastInsertRowID();
    } else {
        $price = 0;
        $id = 'none';
    }
?>

<section id="online-payment-screen" class="screen online-payment-screen selected">
  <div class="screen-content">
    <h2 class="screen-title large-text"><?= $page->subtitle();?></h2>
    <div class="answers">
      <p>Your purchase number is:</p>
      <p id="payment-number" class="xl-text"><?php echo $id; ?></p>
    </div>
    <div class="answers">
      <p>Your personalised price valid for today:£</p>
      <p id="price" class="xl-text"><?php echo $price; ?></p>
    </div>
    <div class="answers">
      <?= $page->body_text()->kirbyText();?>
    </div>
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
