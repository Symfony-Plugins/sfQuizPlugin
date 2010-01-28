<?php
class NumeroGiocatoriForm extends BaseForm
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
      'numeroGiocatori'        => new sfWidgetFormInputText(),
      'numeroDomandePerGiocatore' => $this->widgetSchema['numDom'],
    ));
    
    $this->widgetSchema->setLabels(array(
      'numeroGiocatori'    => 'Number of players',
      'numeroDomandePerGiocatore' => 'Number of questions to ask each player'
    ));
    
    $this->widgetSchema['numeroGiocatori']->setAttribute('size', 3);
    $this->widgetSchema['numeroGiocatori']->setAttribute('maxlength', 2);
    
      $this->widgetSchema->setNameFormat('numeroGiocatori[%s]');
    
    
    $this->setValidators(array(
      'numeroGiocatori' => new sfValidatorNumber(array('min' => 1, 'max' => sfConfig::get('app_max_num_giocatori'))),
      'numeroDomandePerGiocatore' => new sfValidatorChoice(array('choices' => $numDomScelte))
    ));
    
     $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('traduzioni_form');
  }
}