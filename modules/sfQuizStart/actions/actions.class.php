<?php

class sfQuizStartActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {

    $this->form = new PlayersNumberForm();

    if ($request->isMethod('post'))
    {
       
      $this->form->bind($request->getParameter('playersNumber'));
      if ($this->form->isValid())
      {
        $this->redirect('@quiz-names-of-players?'.http_build_query($this->form->getValues()));
      }

    }
  }

  /**
   *
   * @todo Modificare la modalità per la selezione del quiz, per ora c'è cablato $quiz->setQuiz(1)
   *
   * @param sfWebRequest $request
   * @return unknown_type
   */
  public function executeNamesOfPlayers(sfWebRequest $request)
  {
     
    $this->form = new NameOfPlayersForm(null, array(
          'size' => $request->getParameter('playersNumber')
    ));
     
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('newNameOfPlayersBind'));
      if ($this->form->isValid())
      {

        $quiz = new QuizManager();

        // Setta il quiz
        $quiz->setQuiz(1);

        // Setta numero giocatori
        $quiz->numPlayers($request->getParameter('playersNumber'));

        // Setta numero domande per giocatore
        $quiz->numQuestForPlayer($request->getParameter('numberQuestionsForPlayer'));

        // Setta la chiave array della domanda corrente
        $quiz->setKeyCurrentQuestion(0);
         
        // Setta i nomi dei giocatori

        $giocatori = $request->getParameter('newNameOfPlayersBind');
        foreach($giocatori['newNameOfPlayers'] as $giocatore)
        {
          $quiz->addPlayerName($giocatore['playerName']);
        }
         
        // Setta le domande da fare
        $quiz->setQuestions();

        // Setta le risposte da fare
        $quiz->setAnswers();
         

        $this->getUser()->setAttribute('quiz', $quiz);
        $this->redirect('@quiz-game?');
      }

    }

  }
  /**
   * @param sfWebRequest $request
   * @return unknown_type
   */
  public function executeGame(sfWebRequest $request)
  {

    $this->quiz = $this->getUser()->getAttribute('quiz');

    //print_r($this->quiz->getQuestions());exit;
    
    if($this->quiz->numberCurrentQuestion() >($this->quiz->numQuestForPlayer() * $this->quiz->numPlayers()))
    {
       
      $this->redirect('@quiz-end-game');
    }

    $question = $this->quiz->textCurrentQuestion();
    $this->question = $question[0]->Translation['it']->question;

    $this->answers = $this->quiz->textsCurrentAnswers();

    if ($request->isMethod('post'))
    {
      // Memorizzo risposta
      
      $this->quiz->setAnswerGiven($request->getParameter('answer'));
      
      if (!$this->quiz->nextRound()) {$this->redirect('quiz-end-game');};
     // echo __('La tua risposta è %answer%', array('%answer%' => $request->getParameter('answer'))).'. ';

      if ($this->quiz->correctAnswer($this->quiz->getKeyCurrentQuestion(), $request->getParameter('answer')))
      {
        $this->redirect('@quiz-correct-answer');

      }
      else
      {
        $this->redirect('@quiz-wrong-answer');

      }
    }
  }

  public function executeCorrectAnswer(sfWebRequest $request)
  {

  }

  public function executeWrongAnswer(sfWebRequest $request)
  {

  }

  public function executeEndGame(sfWebRequest $request)
  {
    unset($this->quiz);
  }
}