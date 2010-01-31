<?php use_stylesheet('quizPlugin') ?>
<div class="board">
<?php include_component('sfQuizStart', 'boardPlayer', array('quiz' => $quiz))?>
</div>

<h2>
<?php echo __('Question %num% for the player %name%', array('%num%' => $quiz->numberCurrentQuestion(), '%name%' => $quiz->nameCurrentPlayer()))?>
</h2>

<p>
<?php echo $question ?>
</p>
<form method="post">
<?php foreach ($answers as $i => $answer): ?>
<input type="radio" name="answer" value="<?php echo $i ?>" />
<?php echo $answer['text'] ?><br />
<?php endforeach ?>

<input type="submit" value=">>">
</form>