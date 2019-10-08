<?php snippet('header') ?>
<div id="total-bar" style="display:none;position: fixed; top:0; left:0; height:15px; padding: 0 5px 0; background:black; text-align: center; color: white; line-height: 15px; font-size: 10px;">Running total:0, price: £0.00</div>
<form method="get" name="accolade-pricing-form" id="accolade-pricing-form" action="<?= $pages->find('results-page')->url();?>">   
  
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
    $ID = 1;
    $screen = $pages->findBy('id', $ID);
    if($screen):?>
    <section id="trophy-display" class="screen trophy-display simple">
      <div class="screen-content">
          <h2 class="screen-title large-text">Trophy:</h2>
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
            <li>Trophy ID: <?= $screen->id();?></li>
            <li>Size: <?= $screen->size();?></li>
            <input type="checkbox" name="id" value="<?= $screen->id();?>" class="invisible" checked>
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
              <a class="next button submit">Submit Answers</a>
            </div>

          </section>
  <?php endif;?>



</form>


<!--   <?php //// Results Screen //// 
    $screen = $page->children()->find('results-screen'); 
    if($screen):?>
      <section id="results-screen" class="screen results-screen">
        <div class="screen-content">
          <h1 class="main-title screen-title"><?= $screen->subtitle();?></h1>
          
          <div class="answers">
            <?= $screen->body_text()->kirbyText();?>
            <ul id="results-list" class="answers invisible">
          
            </ul>
            <br><br>
            <ul class=" ">
                  <li class="answer">
                <label class="container">Small Trophy    <input id="trophy-results-small" name="trophy-results" value="small" type="radio" disabled>
                  <span class="checkmark"></span>
                </label>
              </li> 

                <li class="answer">
                <label class="container">Medium Trophy     <input id="trophy-results-medium" name="trophy-results" value="medium" type="radio" disabled>
                  <span class="checkmark"></span>
                </label>
              </li> 

                <li class="answer">
                <label class="container">Large Trophy      <input id="trophy-results-large" name="trophy-results" value="large" type="radio" disabled>
                  <span class="checkmark"></span>
                </label>
              </li> 
            </ul>

            <div class="prices-wrapper">
              <p>Your personalised price valid for today:</p>
              <p class="xl-text" id="results-price"></p>
            </div>
          </div>
        </div>
        
        <div class="buttons">
          <form id="payment_submit" action=''method='post'>
          <a data-href="<?= $site->children()->find('pay-cashier')->url(); ?>" class="button left pay_cashier" id="pay_cashier">Pay at Cashier</a>
          <a data-href="<?= $site->children()->find('online-payment-screen')->url(); ?>" class="button right pay_online" id="pay_online">Pay Online</a>
          <input type="hidden" name="trophy-size">
          <input type="hidden" name="final-price">
          </form>
        </div>

      </section>
    <?php endif; ?> -->

</div>

<?php snippet('footer') ?>
