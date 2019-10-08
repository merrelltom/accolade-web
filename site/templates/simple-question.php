<?php
	if($question->allow_multiple() == 'false'):
		$type = 'radio';
	else:
		$type = 'checkbox';
	endif;
?>

<ul class="answers">
	<?php if($question_type == 'complex'){ echo '<h3 class="subtitle">' . $question->title() . '</h3>'; } ?>
<?php foreach($question->answers()->toStructure() as $answer): ?>
    <li class="answer">
		<label class="container"><?= $answer->answer_label();?>
		  <input name="<?= $question->title();?>" value="<?= $answer->answer_value();?>" type="<?= $type; ?>">
		  <span class="checkmark"></span>
		</label>
	</li>	

<?php endforeach; ?>
</ul>