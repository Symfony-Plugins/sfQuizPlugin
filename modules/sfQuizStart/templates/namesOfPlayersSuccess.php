
<form method="post">

<?php foreach ($form['newNameOfPlayers'] as $i => $nome): ?>
  <div>
  <?php echo $nome['playerName']->renderLabel(__('Nome giocatore %num%', array('%num%' => ($i+1)))) ?>
  <?php echo $nome['playerName']->render() ?>
  <?php echo $nome['playerName']->renderError() ?>
  </div>
<?php endforeach; ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value=">>">
</form>