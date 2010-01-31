<?php

class NameOfPlayersForm extends BaseForm
{
  public function configure()
  {

     $form = new NameOfPlayersCollectionForm(null, array(
      'size' => $this->getOption('size')
    ));
    
    $this->embedForm('newNameOfPlayers', $form);
    
    $this->widgetSchema->setNameFormat('newNameOfPlayersBind[%s]');
    
  }
}