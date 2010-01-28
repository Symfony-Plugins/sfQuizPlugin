<?php
/**
 */
class PluginQuizQuestionsTable extends Doctrine_Table
{
/**
   * @param int $numDomande Numero delle domande da fare
   * @return Doctrine_Collection
   */
  public function inizializzaDomandeDaFare($quiz_id, $numDomande)
  {

    $q = Doctrine_Query::create()
    ->select('d.id')
    ->from('QuizQuestions d')
    ->where('d.quiz_id = ? AND d.type_response = "single"', $quiz_id)
    ->orderBy('rand()')
    ->limit($numDomande);

    return $q->execute();

  }
  
  /**
   * @param int $id
   * @return Doctrine_Collection
   */
  public function testoDomanda($id)
  {
    
     $q = Doctrine_Query::create()
    ->select('d.id')
    ->addSelect('dt.question')
    ->from('QuizQuestions d')
    ->leftJoin('d.Translation dt')
    ->where('d.id = ?', $id);
    return $q->execute();
  }
}