<?php

class sfQuizStartActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {

    $this->form = new NumeroGiocatoriForm();

    if ($request->isMethod('post'))
    {
       
      $this->form->bind($request->getParameter('numeroGiocatori'));
      if ($this->form->isValid())
      {
        $this->redirect('@quiz-nomi-giocatori?'.http_build_query($this->form->getValues()));
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
  public function executeNomiGiocatori(sfWebRequest $request)
  {
     
    $this->form = new NomiGiocatoriForm(null, array(
          'size' => $request->getParameter('numeroGiocatori')
    ));
     
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('newNomiGiocatoriBind'));
      if ($this->form->isValid())
      {

        $quiz = new GestioneQuiz();

        // Setta il quiz
        $quiz->setQuiz(1);

        // Setta numero giocatori
        $quiz->numGiocatori($request->getParameter('numeroGiocatori'));

        // Setta numero domande per giocatore
        $quiz->numDomPerGiocatore($request->getParameter('numeroDomandePerGiocatore'));

        // Setta la chiave array della domanda corrente
        $quiz->setChiaveDomandaCorrente(0);
         
        // Setta i nomi dei giocatori

        $giocatori = $request->getParameter('newNomiGiocatoriBind');
        foreach($giocatori['newNomiGiocatori'] as $giocatore)
        {
          $quiz->addNomeGiocatore($giocatore['nomeGiocatore']);
        }
         
        // Setta le domande da fare
        $quiz->setDomande();

        // Setta le risposte da fare
        $quiz->setRisposte();
         

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

     
    if($this->quiz->numeroDomandaCorrente() >($this->quiz->numDomPerGiocatore() * $this->quiz->numGiocatori()))
    {
       
      $this->redirect('@quiz-fine-gioco');
    }

    $domanda = $this->quiz->testoDomandaCorrente();
    $this->domanda = $domanda[0]->Translation['it']->domanda;

    $this->risposte = $this->quiz->testiRisposteCorrenti();

     

    if ($request->isMethod('post'))
    {
      // Memorizzo risposta
      
      $this->quiz->setRispostaData($request->getParameter('risposta'));
      
      if (!$this->quiz->turnoSuccessivo()) {$this->redirect('quiz-fine-gioco');};
      echo 'La tua risposta è '.$request->getParameter('risposta').'. ';

      if ($this->quiz->rispostaGiusta($this->quiz->getChiaveDomandaCorrente(), $request->getParameter('risposta')))
      {
        $this->redirect('@quiz-risposta-giusta');

      }
      else
      {
        $this->redirect('@quiz-risposta-sbagliata');

      }
    }
  }

  public function executeRispostaGiusta(sfWebRequest $request)
  {

  }

  public function executeRispostaSbagliata(sfWebRequest $request)
  {

  }

  public function executeFineGioco(sfWebRequest $request)
  {
    unset($this->quiz);
  }
}