<?php
class sfQuizStartComponents extends sfComponents
{
  public function executeTabelloneGiocatore()
  {
    $this->nome = $this->quiz->nomeGiocatoreCorrente();
    $this->giocatore = $this->quiz->numeroGiocatoreCorrente()-1;
    $this->totDomande = $this->quiz->numDomPerGiocatore();
    $this->risposteDate = $this->quiz->getRisposteDate();
  }
}