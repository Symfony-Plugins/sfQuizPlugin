<?php use_stylesheet('quizPlugin') ?>
<div class="board">
<?php include_component('sfQuizStart', 'boardPlayer', array('quiz' => $quiz))?>
</div>

<h2>
<?php echo __('Question %num% for the player %nome%', array('%num%' => $quiz->numeroDomandaCorrente(), '%nome%' => $quiz->nomeGiocatoreCorrente()))?>
</h2>

<p>
<?php echo $domanda ?>
</p>
<form method="post">
<?php foreach ($risposte as $i => $risposta): ?>
<input type="radio" name="risposta" value="<?php echo $i ?>" />
<?php echo $risposta['testo'] ?><br />
<?php endforeach ?>

<input type="submit" value=">>">
</form>