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
   * Id of the quiz that you are playing
   * 
   * Identificativo del quiz che si sta giocando
   * E' il riferimento al campo quiz->id
   * 
   * @var int
   */
  private $quiz;
  
  /**
   * Number of players participating in the quiz
   * 
   * Numero dei giocatori che partecipano al quiz
   *
   * @var int
   */
  private $numPlayers;

  /**
   * Player names
   * 
   * Nomi dei giocatori
   *
   * @var array
   */
  private $nameOfPlayers = array();

  /**
   * Total number of questions to ask each player
   * 
   * Numero totale delle domande da fare a ciascun giocatore
   *
   * @var int
   */
  private $numQuestForPlayer;

  /**
   * Stores the array index of current demand
   * 
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
   * List of questions to ask
   * 
   * Elenco delle domande da fare
   * Vanno da 0 a ($numQuestForPlayer * $numPlayers)
   * Struttura: [num domanda] = [domanda_id]
   *
   * @var array
   */
  private $questions = array();

  /**
   * Difficulty natively supported by quiz
   * 
   * Difficoltà supportate nativamente dal quiz
   * 
   * @var array
   */
  private $difficulty = array('constantDifficulty', 'increasingDifficulty', 'maxDifficulty');
  
  /**
   * Number of hits to display for each question
   * if $modalitàRispostePerDomanda is set to 'fixedNumber'
   * 
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
  private $answersGiven = array();

  /**
   * Set method for quiz
   * 
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
   * Get method for quiz
   * 
   * Metodo get per identificativo quiz
   * 
   * @return int
   */
  public function getQuiz()
  {
   
    return $this->quiz;
  }
  
  /**
   * Get / set methods for the number of players
   * 
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
   * Total number of questions to be done to player
   * 
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
  
  /**
   * Set method for the names of the players
   * 
   * Metodo set per il nome dei giocatori
   * 
   * @param array $nomi
   * @return void
   */
  public function setNameOfPlayers(array $nomi)
  {
    foreach ($nomi as $nome)
    {
      $this->nameOfPlayers[] = $nome;
    }
  }
  
  /**
   * Add the name of a player
   * 
   * Aggiunge il nome di un giocatore
   * 
   * @param unknown_type $nome
   * @return unknown_type
   */
  public function addPlayerName($nome)
  {
    $this->nameOfPlayers[] = $nome;
  }
  
  /**
   * Returns the name of a player
   * 
   * Restituisce il nome di un giocatore
   * 
   * @param integer $i
   * @return string
   */
  public function playerName($i)
  {
  
    return $this->nameOfPlayers[$i];
  }
  
  /**
   * Set the mode for the number of responses that display a given demand
   * 
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
   * Get method to retrieve the mode for the number of responses that display a given question
   * 
   * Metodo get per recuperare la modalità relativa al numero di risposte
   * da visualizzare per una data domanda
   * 
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
 
  /**
   * Save the answer
   * 
   * Salva la risposta data
   * 
   * @param integer $answer
   * @param integer $player
   * @param integer $question
   * @return void
   */
  public function setAnswerGiven($answer, $player=null, $question=null)
  {
    $player =  $player ? $player : $this->numberCurrentPlayer();
    $question =  $question ? $question : $this->numberCurrentQuestion();
    $answer_id = $this->answers[$question][$question]['answers_id'];
    
    $this->answersGiven[$player][$question] = array(
      'answer' => $answer,
      'answers_id' => $answer_id,
      'correct' => $this->correctAnswer($question, $answer)
    );
  }

  /**
   * Get method for the given answers
   * 
   * Metodo get per le risposte fate
   * 
   * @return array
   */
  public function getAnswersGiven()
  {
    return $this->answersGiven;
  }
  
  /**
   * Set method for the key to the current question
   * 
   * Metodo set per la chiave delle domanda corrente
   * 
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
   * Return the key array of current question
   * 
   * Restituire la chiave array della domanda corrente
   * 
   * @return int
   */
  public function getKeyCurrentQuestion()
  {

    return $this->keyCurrentQuestion;
  }
  
  /**
   * Returns the number of the current question of the player
   * 
   * Restituisce il numero della domanda corrente del giocatore
   * 
   * @return number Numero domanda (parte da 1)
   */
  public function numberCurrentQuestion()
  {
    return round(($this->getKeyCurrentQuestion()+1)/$this->numPlayers());
  }
  
  /**
   * Returns the number of the current player
   * 
   * Restituisce il numero del giocatore corrente
   * 
   * @return number|number
   */
  public function numberCurrentPlayer()
  {
    $numCurrentQuestion = $this->getKeyCurrentQuestion()+1;
    if ($numCurrentQuestion <= $this->numPlayers())
    {
      return $numCurrentQuestion;
    }
    else
    {
      return ($numCurrentQuestion % $this->numPlayers());
    }
  }
  
  /**
   * Returns the name of the current player
   * 
   * Restituisce il nome del giocatore corrente
   * 
   * @return string
   */
  public function nameCurrentPlayer()
  {
   
    return $this->playerName($this->numberCurrentPlayer()-1);
  }
  
  /**
   * Returns the text of a question
   * 
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
   * Returns the text of the current question
   * 
   * Restituisce il testo della domanda corrente
   * 
   * @return string
   */
  public function textCurrentQuestion()
  { 
    
    return $this->textQuestion($this->getKeyCurrentQuestion());
  }
  
  /**
   * Returns the responses of a given question
   * 
   * Restituisce le risposte di una data domanda
   * 
   * @param integer $dom
   * @return array
   */
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
  
  /**
   * Returns the current question answers
   * 
   * Restituisce le risposte della domanda corrente
   * 
   * @return array
   */
  public function textsCurrentAnswers()
  {
    return $this->textsAnswers($this->getKeyCurrentQuestion());
  }
  
  /**
   * Check if the answer is correct
   * 
   * Verifica se la risposta è corretta
   * 
   * @param integer $question
   * @param integer $answer
   * @return bool
   */
  public function correctAnswer($question, $answer)
  {

    return $this->answers[$question][$answer]['correct'];
  }
  
  /**
   * Next round
   * 
   * Prossimo turno
   * 
   * @return unknown_type
   */
  public function nextRound()
  {
    
    $this->setKeyCurrentQuestion($this->getKeyCurrentQuestion()+1);
  
  return $this->getKeyCurrentQuestion() <= $this->numQuestForPlayer()? true : false;
  }
}
