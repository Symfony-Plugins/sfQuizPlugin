<?php
/**
 * 
 * @package sfQuizPlugin
 * @author Fabrizio Pucci <fabrizio.pucci.ge@gmail.com>
 *
 */
class QuizManager
{
  
  /**
   * Identificativo del quiz che si sta giocando
   * E' il riferimento al campo quiz->id
   * 
   * @var int
   */
  private $quiz;
  
  /**
   * Numero dei giocatori che partecipano al quiz
   *
   * @var int
   */
  private $numPlayers;

  /**
   * Nomi dei giocatori
   *
   * @var array
   */
  private $nameOfPlayers = array();

  /**
   * Numero totale delle domande da fare a ciascun giocatore
   *
   * @var int
   */
  private $numQuestForPlayer;

  /**
   * Memorizza l'indice array della domanda corrente
   * 
   * @var int
   */
  private $keyCurrentQuestion;
  
  /**
   *
   * Partial score for each player
   *
   * Punteggio parziale per ciascun giocatore
   *
   * @var array
   */
  private $score = array();

  /**
   * Elenco delle domande da fare
   * Vanno da 0 a ($numQuestForPlayer * $numPlayers)
   * Struttura: [num domanda] = [domanda_id]
   *
   * @var array
   */
  private $questions = array();

  /**
   * Difficoltà supportate nativamente dal quiz
   * 
   * @var array
   */
  private $difficulty = array('constantDifficulty', 'increasingDifficulty', 'maxDifficulty');
  
  /**
   * Numero delle risposte da visualizzare per ciascuna domanda
   * se $modalitàRispostePerDomanda è settato a 'numeroFisso'
   * 
   * @var int
   */
  private $numAnswersForQuestion;
  
  /**
   * [num domanda][] = array(
   *   'answers_id' => '',
   *   'correct' => ''
   * );
   *
   * @var array
   */
  private $answers = array();

  /**
   * [num domanda][] = array(
   *   'answers_id' => $answer->id,
   *   'correct' => $answer->correct
   * );
   *
   * @var array
   */
  private $risposteDate = array();

  /**
   * Metodo set per identificativo quiz
   * 
   * @param unknown_type $id
   * @return void
   */
  public function setQuiz($id)
  {
    $this->quiz = $id;
  }
  
  /**
   * Metodo get per identificativo quiz
   * 
   * @return int
   */
  public function getQuiz()
  {
   
    return $this->quiz;
  }
  
  /**
   * Metodo get/set per il numero dei giocatori
   *
   * @param int $num
   * @return void
   */
  public function numPlayers($num = null)
  {
    if ($num != null)
    {
      $this->numPlayers = $num;
    }
    return $this->numPlayers;
  }
  
  /**
   * Numero totale delle domande da fare per giocatore
   * 
   * @param int $num Valore da assegnare
   * @return int
   */
  public function numQuestForPlayer($num = null)
  {
    if ($num != null)
    {
      $this->numQuestForPlayer = $num;
    }
    return $this->numQuestForPlayer;
  }
  
  public function setNameOfPlayers(array $nomi)
  {
    foreach ($nomi as $nome)
    {
      $this->nameOfPlayers[] = $nome;
    }
  }
  
  public function addPlayerName($nome)
  {
    $this->nameOfPlayers[] = $nome;
  }
  
  public function playerName($i)
  {
  
    return $this->nameOfPlayers[$i];
  }
  
  /**
   * Setta la modalità relativa al numero di risposte da visualizzare
   * per una data domanda
   * 
   * @param unknown_type $modalita
   * @return unknown_type
   */
  public function setModalitaRispostePerDomanda($modalita)
  {
    
    if (in_array($modalita, $this->modalita))
    {
      $this->modalitaRispostePerDomanda = $modalita;
      
    }
    else
    {
      Throw new Exception('La modalità di risposta '.$modalita.' non è prevista dal plugin sfQuizPlugin');
    }
  }
  
  /**
   * Metodo get per recuperare la modalità relativa al numero di risposte
   * da visualizzare per una data domanda
   * @return string
   */
  public function getModalitaRispostePerDomanda()
  {
    return $this->modalitaRispostePerDomanda;
  }
  
  public function getNumAnswersForQuestion()
  {
    return $this->numAnswersForQuestion;
  }
  
 public function getNumAnswersForCurrentQuestion()
  {
    if ($this->getModalitaRispostePerDomanda == 'constantDifficulty')
    {
      return $this->numAnswersForQuestion;
    }
    else if ($this->getModalitaRispostePerDomanda == 'increasingDifficulty')
    {
      // Algoritmo in base a numero domanda attuale?
    }
    
  }
  
  /**
   * Memorizza gli id delle domande da fare
   * scelte in modo casuale da una query sul database
   * 
   * @return void
   */
  public function setQuestions()
  {

    $questions = Doctrine::getTable('QuizQuestions')->inizializzaDomandeDaFare($this->getQuiz(), $this->numPlayers * $this->numQuestForPlayer);

    foreach($questions as $question)
    {

      $this->questions[] = $question->id;
    }
    
  }
  
  /**
   * Metodo get per recuperare le chiavi delle domande
   * 
   * @return array
   */
  public function getQuestions()
  {
    return $this->questions;
  }
  
  /**
   * @return unknown_type
   */
  public function setAnswers()
  {
    foreach ($this->getQuestions() as $d => $question)
    {
      
      $answers = Doctrine::getTable('QuizAnswers')->inizializzaRisposteDaFare($question, 5);
     
      
      foreach($answers as $r => $answer)
      {
        
        $this->answers[$d][]= array(
 		  'answers_id' => $answer->id,
 		  'correct' => $answer->correct
			);
        //echo $risposta->Translation['it']->risposta;
        
      }
    }
    
  }
 
  public function setRispostaData($answer, $player=null, $question=null)
  {
    $player =  $player ? $player : $this->numberCurrentPlayer();
    $question =  $question ? $question : $this->numberCurrentQuestion();
    $answer_id = $this->answers[$question][$answer]['answers_id'];
    
    $this->risposteDate[$giocatore][$domanda] = array(
      'answer' => $answer,
      'answers_id' => $answer_id,
      'correct' => $this->correctAnswer($question, $answer)
    );
  }

  public function getRisposteDate()
  {
    return $this->risposteDate;
  }
  
  /**
   * @param int $num [0..$numPlayers*numQuestForPlayer-1]
   * @return unknown_type
   */
  public function setKeyCurrentQuestion($num)
  {
    /*
    if ($num > ($this->numPlayers * $this->numQuestForPlayer) - 1)
    {
      $messaggio = 'Tentativo di settare come domanda corrente '.$num;
      $messaggio .= ', un valore maggiore di '.($this->numPlayers * $this->numQuestForPlayer - 1);
      throw new Exception($messaggio, 001); 
    }
    */
    $this->keyCurrentQuestion = $num;
  }
  
  /**
   * Restituire la chiave array
   * 
   * @return int
   */
  public function getKeyCurrentQuestion()
  {

    return $this->keyCurrentQuestion;
  }
  
  /**
   * Restituisce il numero della domanda corrente del giocatore
   * 
   * 
   * @return number Numero domanda (parte da 1)
   */
  public function numberCurrentQuestion()
  {
    return round(($this->getKeyCurrentQuestion()+1)/$this->numPlayers());
  }
  
  /**
   * Restituisce il numero del giocatore corrente
   * 
   * @return number|number
   */
  public function numberCurrentPlayer()
  {
    $numDomandaCorrente = $this->getKeyCurrentQuestion()+1;
    if ($numDomandaCorrente <= $this->numPlayers())
    {
      return $numDomandaCorrente;
    }
    else
    {
      return ($numDomandaCorrente % $this->numPlayers());
    }
  }
  
  /**
   * Restituisce il nome del giocatore corrente
   * 
   * @return string
   */
  public function nameCurrentPlayer()
  {
   
    return $this->playerName($this->numberCurrentPlayer()-1);
  }
  
  /**
   * Restituisce il testo di una domanda
   * 
   * @param int $dom Chiave array della domanda
   * @return string
   */
  public function textQuestion($dom)
  {
    $id = $this->questions[$dom];
    return Doctrine::getTable('QuizQuestions')->textQuestion($id);
  }
  
  /**
   * Restituisce il testo della domanda corrente
   * 
   * @return string
   */
  public function textCurrentQuestion()
  { 
    
    return $this->textQuestion($this->getKeyCurrentQuestion());
  }
  
  public function textsAnswers($dom)
  {
    
    foreach($this->answers[$dom] as $i => $answer)
    {
     
      $risp = Doctrine::getTable('QuizAnswers')->textAnswer($answer['answers_id']);
     
      $r[$i] = array(
        'text' => $risp[0]->Translation['it']->answer,
      );

    }
    return $r;
  }
  
  public function textsCurrentAnswers()
  {
    return $this->textsAnswers($this->getKeyCurrentQuestion());
  }
  
  public function correctAnswer($question, $answer)
  {
/*
    echo "Cerco $domanda e $risposta. Verifica ".$this->answers[$question][$answer]['correct'];
    echo "<pre>";
    print_r($this->answers);
    echo "</pre>";
   */ 
    return $this->answers[$question][$answer]['correct'];
  }
  
  /**
   * @return unknown_type
   */
  public function nextRound()
  {
    
    $this->setKeyCurrentQuestion($this->getKeyCurrentQuestion()+1);
  
  return $this->getKeyCurrentQuestion() <= $this->numQuestForPlayer()? true : false;
  }
}
