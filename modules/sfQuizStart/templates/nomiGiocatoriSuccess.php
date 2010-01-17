
<form method="post">

<?php foreach ($form['newNomiGiocatori'] as $i => $nome): ?>
  <div>
  <?php echo $nome['nomeGiocatore']->renderLabel(_('Nome giocatore').' '.($i+1)) ?>
  <?php echo $nome['nomeGiocatore']->render() ?>
  <?php echo $nome['nomeGiocatore']->renderError() ?>
  </div>
<?php endforeach; ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value=">>">
</form>