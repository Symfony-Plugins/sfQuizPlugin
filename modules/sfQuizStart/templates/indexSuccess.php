<p><?php echo __('Welcome') ?></p>

<form method="post">
<?php echo $form['playersNumber']->renderRow() ?><br />
<?php echo $form['numberQuestionsForPlayer']->renderRow() ?><br />
<?php echo $form->renderHiddenFields() ?>

<input type="submit" value="<?php echo _('>>') ?>">

</form>