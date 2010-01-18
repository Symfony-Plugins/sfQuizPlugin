<p><?php echo __('Benvenuti') ?></p>

<form method="post">
<?php echo $form['numeroGiocatori']->renderRow() ?><br />
<?php echo $form['numeroDomandePerGiocatore']->renderRow() ?><br />
<?php echo $form->renderHiddenFields() ?>

<input type="submit" value="<?php echo _('>>') ?>">

</form>