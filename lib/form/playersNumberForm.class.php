<?php
class PlayersNumberForm extends BaseForm
{
  public function configure()
  {
    
    $numDomScelte = array (
        '1' => '1',
        '2' => '2',
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
    );

    $this->widgetSchema['numDom'] = new sfWidgetFormChoice(array(
      'choices' => $numDomScelte));
    
    $this->setWidgets(array(
      'playersNumber'        => new sfWidgetFormInputText(),
      'numberQuestionsForPlayer' => $this->widgetSchema['numDom'],
    ));
    
    $this->widgetSchema->setLabels(array(
      'playersNumber'    => 'Number of players',
      'numberQuestionsForPlayer' => 'Number of questions to ask each player'
    ));
    
    $this->widgetSchema['playersNumber']->setAttribute('size', 3);
    $this->widgetSchema['playersNumber']->setAttribute('maxlength', 2);
    
      $this->widgetSchema->setNameFormat('playersNumber[%s]');
    
    
    $this->setValidators(array(
      'playersNumber' => new sfValidatorNumber(array('min' => 1, 'max' => sfConfig::get('app_max_num_giocatori'))),
      'numberQuestionsForPlayer' => new sfValidatorChoice(array('choices' => $numDomScelte))
    ));
    
     $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('traduzioni_form');
  }
}