<?php __('Player %name% results', array('%name%' => $name))?>

<?php for ($d=1; $d<=$totQuestions; $d++):?>
<div>

<?php if (array_key_exists($player+1, $risposteDate) && isset($risposteDate[$player+1][$d]['correct'])):?>
<?php echo $risposteDate[$player+1][$d]['correct'] ?  __('Correct') : __('Wrong') ?>
<?php else: ?>
<?php echo __('To respond') ?>

<?php endif; ?>
</div>
<?php endfor;?>
