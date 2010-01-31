<?php
/**
 */
class PluginQuizAnswersTable extends Doctrine_Table
{
 /**
   * Inizializza le risposte.
   * La procedura avviene domanda per domanda invece che tutte assieme
   * in tal modo è possibile assegnare un numero di risposte diverso
   * (necessario ad esempio per difficoltaCrescente)
   *
   * @param unknown_type $quiz_domande_id
   * @param unknown_type $limit
   * @return Doctrine_Collection
   */
  public function inizializzaRisposteDaFare($quiz_questions_id, $limit)
  {

    $q = Doctrine_Query::create()
    ->select('r.id, r.correct')
    ->from('QuizAnswers r')
    ->where('r.quiz_questions_id = ? AND r.correct = 0', $quiz_questions_id)
    ->orderBy('RAND()')
    ;

    if ($limit != null)
    {
      $q = $q->limit($limit);
    }
    $wrongs =  $q->execute();

    $q = Doctrine_Query::create()
    ->select('r.id, r.correct')
    ->from('QuizAnswers r')
    ->where('r.quiz_questions_id = ? AND r.correct = 1', $quiz_questions_id);

    $corrects = $q->execute();
    return $wrongs->merge($corrects);

  }
  
  public function textAnswer($id)
  {
    $q= Doctrine_Query::create()
    ->select('r.id')
    ->addSelect('rt.answer')
    ->from('QuizAnswers r')
    ->leftJoin('r.Translation rt')
    ->where('r.id = ?', $id);
    
    /*
    foreach($risposte as $risposta)
    {
      $q->orWhere('r.id = '.$risposta['risposte_id']);
    }
    */
    
    return $q->execute();
  }
}