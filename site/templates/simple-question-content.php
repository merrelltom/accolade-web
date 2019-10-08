<?php 
    $questions = $screen->children();
    $question_type = 'simple';
    $question = $questions->first();
?>

<section id="<?=$screen->slug();?>" class="screen <?=$screen->slug();?> <?= $question_type?>">
  <div class="screen-content">
      <h2 class="screen-title large-text"><?= $question->title();?></h2>
        <?php include('simple-question.php');?>
  </div>
  <div class="buttons">
    <a class="prev button">Previous</a>
    <a class="next button">Next</a>
  </div>

</section>