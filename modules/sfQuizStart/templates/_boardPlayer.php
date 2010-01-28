Percorso giocatore <?php echo $nome?>

<?php for ($d=1; $d<=$totDomande; $d++):?>
<div>
<?php echo $d.': ' ?>
<?php if (isset($risposteDate[$giocatore+1][$d])):?>
<?php echo $risposteDate[$giocatore+1][$d]['giusta'] ?  __('Correct') : __('Wrong') ?>
<?php else: ?>
<?php echo __('To respond') ?>

<?php endif; ?>
</div>
<?php endfor;?>
