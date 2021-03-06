<?php snippet('header'); 
//Get available trophy:
$db = new SQLite3('assets/db/simple_postcode.db', SQLITE3_OPEN_READWRITE);
$statement = $db->prepare("SELECT count(*) from results where date_time = ? and paid = 1");
date_default_timezone_set("Europe/London");
$timestamp = date("Y/m/d");
$statement->bindValue(1, $timestamp);
$result = $statement->execute();
$paid_today = $result->fetchArray();
if ($paid_today[0] == 0) {  
//    
//Tropy Available:
//
    $statement = $db->prepare("SELECT trophy_id FROM results WHERE paid = 1 ORDER BY trophy_id DESC LIMIT 1");
    $result = $statement->execute();
    $id_val = $result->fetchArray(SQLITE3_ASSOC);
    if (array_key_exists('trophy_id', $id_val)) {
        $id = ($id_val['trophy_id']+ 1);
    } else {
        $id = 1;
    }
    ?>
    <form method="post" name="accolade-pricing-form" id="accolade-pricing-form" action="<?= $pages->find('results-page')->url();?>">   

      <?php //// Start Screen ////
        $screen = $page->children()->find('start-screen'); 
        if($screen):?>
          <section id="start-screen" class="screen start-screen selected">
            <div class="screen-content">
              <h1 class="main-title screen-title"><?= $screen->subtitle();?></h1>

              <div class="answers">
                <?= $screen->body_text()->kirbyText();?>
                <input type="checkbox" name="start" value="0" class="invisible" checked>
              </div>
            </div>

            <div class="buttons">
              <a class="next button">Proceed</a>
            </div>

          </section>
        <?php endif; ?>

      <?php //// Tophy Display //// 
        $screen = $pages->findBy('id', $id);
        if($screen):?>
        <section id="trophy-display" class="screen trophy-display simple">
          <div class="screen-content">
              <h2 class="screen-title large-text">Available Trophy:</h2>
              <ul class="answers">
                <li class="image-container">
                  <?php if ($thumb = $screen->featured_image()->toFile()):?>
                  <figure>
                     <img class="img-contain trophy-image" srcset="
                    <?= $thumb->thumb(array('width' => 320))->url();?> 320w, 
                    <?= $thumb->thumb(array('width' => 640))->url();?> 640w, 
                    <?= $thumb->thumb(array('width' => 960))->url();?> 960w, 
                    <?= $thumb->thumb(array('width' => 1200))->url();?> 1200w"
                    sizes="(min-width: 1000px) 33vw, (min-width: 768px) 50vw, 100vw"
                    src="<?= $thumb->thumb(array('width' => 640))->url();?>"
                    >
                  </figure>
                  <?php endif;?>
                </li> 
                <?php if ($screen->description()):?>
                <li><?= $screen->description();?></li>
                <?php endif;?>
                <input type="checkbox" name="start" value="0" class="invisible" checked>
              </ul>  
            </div>
            <div class="buttons">
              <a class="prev button">Previous</a>
              <a class="next button">Next</a>
            </div>
        </section>
        <?php endif;?>

      <?php //// Post Code Screen //// ?>

      <section id="postcode-screen" class="screen postcode-screen">
        <div class="screen-content">
          <h2 class="screen-title large-text">What is your postcode?</h2>
          <ul class="answers no-border">
            <div id="postcode-message"> </div>
            <li class="input-wrapper">
            <input type="text" id="postcode" class="postcode" placeholder="Enter your postcode...">
            <input id="pc_result" type="checkbox" name="postcode result" value="0" class="invisible" checked>
            </li>
            <!-- <button id="pc_check" type="button"/>Check Postcode</button> -->
            <hr>
            <li class="answer">
              <label class="container">Not applicable / rather not say
                <input name="postcode-na" value="0" type="checkbox">
                <span class="checkmark"></span>
              </label>
            </li> 
          </ul>
        </div>
        <div class="buttons">
          <a class="prev button">Previous</a>
          <a class="next button">Next</a>
        </div>
      </section>


      <?php //// Age Screen //// 
        $screen = $page->children()->find('screen-2-Age');
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>


      <?php //// Intention ////
        $screen = $page->children()->find('screen-4-Intention'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Upbringing 1 ////
        $screen = $page->children()->find('screen-5-Upbringing'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>


      <?php //// Upbringing 2 ////
        $screen = $page->children()->find('screen-6-Upbringing'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Upbringing 3 ////
        $screen = $page->children()->find('screen-7-Upbringing'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Upbringing 4 ////
        $screen = $page->children()->find('screen-8-Upbringing'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Upbringing 5 ////
        $screen = $page->children()->find('screen-10-Upbringing'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Social Capital ////
        $screen = $page->children()->find('screen-9-Social-Capital'); 
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>

      <?php //// Ask 1 of 2 Cultural Social Questions //// 
        $int = rand(1,100);
        if($int < 60){
          $screen = $page->children()->find('screen-11-Cultural-Capital'); 
        }else{
          $screen = $page->children()->find('screen-11b-Cultural-Capital'); 
        }
        if($screen):
          include('simple-question-content.php');
        endif;
      ?>  


      <?php //// Agreeableness and Extroversion ////
        $screen = $page->children()->find('screen-12-Big5-Extraversion'); 
        if($screen):
          $questions = $screen->children()->shuffle()->limit(3);
          if($questions){
            foreach ($questions as $question):?>
              <section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?>">
                <div class="screen-content">

                  <?php 
                      $question_type = 'complex';
                  ?>
                    <h2 class="screen-title large-text"><?= $screen->subtitle();?></h2>
                      <?php include('simple-question.php');?>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <a class="next button">Next</a>
                </div>

              </section>

            <?php endforeach;
          }
        endif;
      ?> 

      <?php //// Neuroticism, Openness, Conscientiousness ////
        $screen = $page->children()->find('neuroticism-openness-conscientiousness'); 
        if($screen):
          $questions = $screen->children()->shuffle()->limit(3);
          if($questions){
            foreach ($questions as $question):?>
              <section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?>">
                <div class="screen-content">

                  <?php 
                      $question_type = 'complex';
                  ?>
                    <h2 class="screen-title large-text"><?= $screen->subtitle();?></h2>
                      <?php include('simple-question.php');?>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <a class="next button">Next</a>
                </div>

              </section>

            <?php endforeach;
          }
        endif;
      ?>

      <?php //// Dospert Social ////
        $screen = $page->children()->find('screen-13-Dospert-Social'); 
        if($screen):
          $questions = $screen->children()->shuffle()->limit(3);
          if($questions){
            foreach ($questions as $question):?>
              <section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?>">
                <div class="screen-content">

                  <?php 
                      $question_type = 'simple';
                  ?>
                    <h2 class="screen-title large-text"><?= $question->title();?></h2>
                      <?php include('simple-question.php');?>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <a class="next button">Next</a>
                </div>

              </section>

            <?php endforeach;
          }
        endif;
      ?>

      <?php //// Hidden Labour ////
        $screen = $page->children()->find('screen-14-Hidden-Labour'); 
        if($screen):
          $questions = $screen->children()->shuffle()->limit(2);
          if($questions){
            foreach ($questions as $question):?>
              <section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?>">
                <div class="screen-content">

                  <?php 
                      $question_type = 'simple';
                  ?>
                    <h2 class="screen-title large-text"><?= $question->title();?></h2>
                      <?php include('simple-question.php');?>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <a class="next button">Next</a>
                </div>

              </section>

            <?php endforeach;
          }
        endif;
      ?>

      <?php //// Additional ////
        $screen = $page->children()->find('screen-16-Additional'); 
        if($screen):
          $questions = $screen->children()->shuffle()->limit(1);
          if($questions){
            foreach ($questions as $question):?>
              <section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?>">
                <div class="screen-content">

                  <?php 
                      $question_type = 'simple';
                  ?>
                    <h2 class="screen-title large-text"><?= $question->title();?></h2>
                      <?php include('simple-question.php');?>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <a class="next button">Next</a>
                </div>

              </section>

            <?php endforeach;
          }
        endif;
      ?> 

      <?php //// Financial Screen ////
        $screen = $page->children()->find('screen-15-Optional-Finance-Economic-Capital'); 
        if($screen):?>
           <section id="<?=$screen->slug();?>" class="screen financial-screen">
                <div class="screen-content">
                  <h2 class="screen-title large-text"><?= $screen->subtitle();?></h2>
                  <ul class="answers">
                      <li class="answer">
                      <label class="container">Yes
                        <input name="financial-consent" value="yes" type="radio">
                        <span class="checkmark"></span>
                      </label>
                    </li> 
                    <li class="answer">
                      <label class="container">No
                        <input name="financial-consent" value="no" type="radio">
                        <span class="checkmark"></span>
                      </label>
                    </li>               
                  </ul>
                  <div class="financial-conditional-questions invisible">
                      <?php 
                        $questions = $screen->children();
                        $question_type = 'complex';
                        foreach ($questions as $question):
                          include('simple-question.php');                  
                        endforeach;
                      ?>
                  </div>
                </div>
                <div class="buttons">
                  <a class="prev button">Previous</a>
                  <input type="number" id="id" value="<?= $id;?>" name="id" class="invisible">
                  <input type="number" id="formScore" value="0" name="score"  class="invisible">
                  <input type="submit" class="next button submit">
                </div>

              </section>
      <?php endif;?>
    </form>
    </div>

<?php 
} else {
//    
//    No Trophy Available:
//    
        $screen = $page->siblings()->findByUri('no-trophies-available-today');
        if($screen):?>
        <section id="start-screen" class="screen start-screen selected">
          <div class="screen-content">
            <h2 class="screen-title large-text"><?= $screen->title();?></h2>
            <div class="answers">
              <?= $screen->text()->kt() ?>
            </div>
          </div>
        </section>
        <?php endif;?>
<?php } snippet('footer') ?>
