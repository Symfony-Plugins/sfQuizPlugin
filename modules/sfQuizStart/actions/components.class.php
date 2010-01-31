<?php
class sfQuizStartComponents extends sfComponents
{
  public function executeBoardPlayer()
  {
    $this->name = $this->quiz->nameCurrentPlayer();
    $this->player = $this->quiz->numberCurrentPlayer()-1;
    $this->totQuestions = $this->quiz->numQuestForPlayer();
    $this->answersGiven = $this->quiz->getAnswersGiven();
  }
}