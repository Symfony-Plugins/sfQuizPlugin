<?php

class NameOfPlayersCollectionForm extends sfForm
{
  public function configure()
  {
   
    if(!$nome = $this->getOption('size'))
    {
      throw new InvalidArgumentException(_('You must provide a size.'));
    }

    for ($i = 0; $i < $this->getOption('size'); $i++)
    {
      $form = new PlayerNameForm();
      $this->embedForm($i, $form);
    }

  }
}