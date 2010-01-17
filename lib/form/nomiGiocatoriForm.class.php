<?php

class NomiGiocatoriForm extends BaseForm
{
  public function configure()
  {

     $form = new NomiGiocatoriCollectionForm(null, array(
      'size' => $this->getOption('size')
    ));
    
    $this->embedForm('newNomiGiocatori', $form);
    
    $this->widgetSchema->setNameFormat('newNomiGiocatoriBind[%s]');
    
  }
}