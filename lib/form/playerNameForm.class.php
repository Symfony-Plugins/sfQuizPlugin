<?php

class PlayerNameForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('playerName', new sfWidgetFormInputText());

    $this->setValidator('playerName', new sfValidatorString(array(
      'min_length' => 1,
      'max_length' => sfConfig::get('max_length_player_name')
    )));
   
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('traduzioni_form');
  }
}