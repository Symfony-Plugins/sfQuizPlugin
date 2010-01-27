<?php use_stylesheet('quizPlugin') ?>
<div class="tabellone">
<?php include_component('sfQuizStart', 'tabelloneGiocatore', array('quiz' => $quiz))?>
</div>

<h2>Domanda <?php echo $quiz->numeroDomandaCorrente() ?> per il giocatore <?php echo $quiz->nomeGiocatoreCorrente() ?>
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