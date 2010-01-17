<?php

class NomeGiocatoreForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('nomeGiocatore', new sfWidgetFormInputText());

    $this->setValidator('nomeGiocatore', new sfValidatorString(array(
      'min_length' => 1,
      'max_length' => sfConfig::get('max_length_nome_giocatore')
    )));
   
  }
}