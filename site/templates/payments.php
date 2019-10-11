<?php snippet('header');
$db = new SQLite3('assets/db/simple_postcode.db', SQLITE3_OPEN_READWRITE);
$res = $db->query('SELECT * FROM results');

if (isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, "id");
    if (isset($_POST['paid'])) {
        $statement = $db->prepare("UPDATE results set paid=1 WHERE id=:id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $msg = 'Order '. $id . ' updated to PAID';
    } else {
        $statement = $db->prepare("UPDATE results set paid=0 WHERE id=:id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $msg = 'Order '. $id . ' updated to UNPAID';
    } 
} else {
    $msg = null;
}
?>

<section id="payments-screen" class="screen payments-screen selected">
  <div class="screen-content">
    <?php 
        if ($msg != null) {
            echo '<h2 class="screen-title large-text message">'. $msg .'</h2>';
        }
    ?>
    <h2 class="screen-title large-text">Results and Payments</h2>
    
        <?php 
        $results = array();
        while ($row = $res->fetchArray()) { 
            array_push($results, $row);
        }
        $results = array_reverse($results, true);
        foreach ($results as $row) {
        ?>
        <ul class="order">
            <li>Order Number: <?php echo $row['id']; ?><br></li>
            <li>
                <span>Date: <?php echo $row['date_time']; ?></span><br>
                <span>Size: <?php echo $row['trophy_size']; ?></span><br>
                <span>Price: £<?php echo $row['price']; ?></span><br>
            </li>
            <form method="post">
                <li>
                    <?php if ($row['paid'] == 1) { echo 'Paid '; }else{ echo "Did not complete order";} ?>
                      
                    </label>
               </li>
            </form>
        </ul>
        <?php } ?>
  </div>
</section>

<?php snippet('footer') ?>
