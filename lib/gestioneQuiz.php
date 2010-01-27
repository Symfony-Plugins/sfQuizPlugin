<?php
/**
 * 
 * @package sfQuizPlugin
 * @author Fabrizio Pucci <fabrizio.pucci.ge@gmail.com>
 *
 */
class GestioneQuiz
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
  private $numGiocatori;

  /**
   * Nomi dei giocatori
   *
   * @var array
   */
  private $nomiGiocatori = array();

  /**
   * Numero totale delle domande da fare a ciascun giocatore
   *
   * @var int
   */
  private $numDomPerGiocatore;

  /**
   * Memorizza l'indice array della domanda corrente
   * 
   * @var int
   */
  private $chiaveDomandaCorrente;
  
  /**
   *
   * Partial score for each player
   *
   * Punteggio parziale per ciascun giocatore
   *
   * @var array
   */
  private $punteggio = array();

  /**
   * Elenco delle domande da fare
   * Vanno da 0 a ($numDomPerGiocatore * $numGiocatori)
   * Struttura: [num domanda] = [domanda_id]
   *
   * @var array
   */
  private $domande = array();

  /**
   * Difficoltà supportate nativamente dal quiz
   * 
   * @var array
   */
  private $difficolta = array('difficoltaCostante', 'difficoltaCrescente', 'difficoltaMassima');
  
  /**
   * Numero delle risposte da visualizzare per ciascuna domanda
   * se $modalitàRispostePerDomanda è settato a 'numeroFisso'
   * 
   * @var int
   */
  private $numRispostePerDomanda;
  
  /**
   * [num domanda][] = array(
   *   'risposte_id' => '',
   *   'giusta' => ''
   * );
   *
   * @var array
   */
  private $risposte = array();

  /**
   * [num domanda][] = array(
   *   'risposte_id' => $risposta->id,
   *   'giusta' => $risposta->giusta
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
  public function numGiocatori($num = null)
  {
    if ($num != null)
    {
      $this->numGiocatori = $num;
    }
    return $this->numGiocatori;
  }
  
  /**
   * Numero totale delle domande da fare per giocatore
   * 
   * @param int $num Valore da assegnare
   * @return int
   */
  public function numDomPerGiocatore($num = null)
  {
    if ($num != null)
    {
      $this->numDomPerGiocatore = $num;
    }
    return $this->numDomPerGiocatore;
  }
  
  public function setNomiGiocatori(array $nomi)
  {
    foreach ($nomi as $nome)
    {
      $this->nomiGiocatori[] = $nome;
    }
  }
  
  public function addNomeGiocatore($nome)
  {
    $this->nomiGiocatori[] = $nome;
  }
  
  public function nomeGiocatore($i)
  {
  
    return $this->nomiGiocatori[$i];
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
  
  public function getNumRispostePerDomanda()
  {
    return $this->numRispostePerDomanda;
  }
  
 public function getNumRispostePerDomandaCorrente()
  {
    if ($this->getModalitaRispostePerDomanda == 'difficoltaCostante')
    {
      return $this->numRispostePerDomanda;
    }
    else if ($this->getModalitaRispostePerDomanda == 'difficoltaCrescente')
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
  public function setDomande()
  {

    $domande = Doctrine::getTable('QuizDomande')->inizializzaDomandeDaFare($this->getQuiz(), $this->numGiocatori * $this->numDomPerGiocatore);
    
    foreach($domande as $domanda)
    {
     
      $this->domande[] = $domanda->id;
    }
   
  }
  
  /**
   * Metodo get per recuperare le chiavi delle domande
   * 
   * @return array
   */
  public function getDomande()
  {
    return $this->domande;
  }
  
  /**
   * @return unknown_type
   */
  public function setRisposte()
  {
    foreach ($this->getDomande() as $d => $domanda)
    {
      
      $risposte = Doctrine::getTable('QuizRisposte')->inizializzaRisposteDaFare($domanda, 5);
     
      
      foreach($risposte as $r => $risposta)
      {
        
        $this->risposte[$d][]= array(
 		  'risposte_id' => $risposta->id,
 		  'giusta' => $risposta->giusta
			);
        //echo $risposta->Translation['it']->risposta;
        
      }
    }
    
  }
 
  public function setRispostaData($risposta, $giocatore=null, $domanda=null)
  {
    $giocatore =  $giocatore ? $giocatore : $this->numeroGiocatoreCorrente();
    $domanda =  $domanda ? $domanda : $this->numeroDomandaCorrente();
    $risposta_id = $this->risposte[$domanda][$risposta]['risposte_id'];
    
    $this->risposteDate[$giocatore][$domanda] = array(
      'risposta' => $risposta,
      'risposta_id' => $risposta_id,
      'giusta' => $this->rispostaGiusta($domanda, $risposta)
    );
  }

  public function getRisposteDate()
  {
    return $this->risposteDate;
  }
  
  /**
   * @param int $num [0..$numGiocatori*numDomPerGiocatore-1]
   * @return unknown_type
   */
  public function setChiaveDomandaCorrente($num)
  {
    /*
    if ($num > ($this->numGiocatori * $this->numDomPerGiocatore) - 1)
    {
      $messaggio = 'Tentativo di settare come domanda corrente '.$num;
      $messaggio .= ', un valore maggiore di '.($this->numGiocatori * $this->numDomPerGiocatore - 1);
      throw new Exception($messaggio, 001); 
    }
    */
    $this->chiaveDomandaCorrente = $num;
  }
  
  /**
   * Restituire la chiave array
   * 
   * @return int
   */
  public function getChiaveDomandaCorrente()
  {

    return $this->chiaveDomandaCorrente;
  }
  
  /**
   * Restituisce il numero della domanda corrente del giocatore
   * 
   * 
   * @return number Numero domanda (parte da 1)
   */
  public function numeroDomandaCorrente()
  {
    return round(($this->getChiaveDomandaCorrente()+1)/$this->numGiocatori());
  }
  
  /**
   * Restituisce il numero del giocatore corrente
   * 
   * @return number|number
   */
  public function numeroGiocatoreCorrente()
  {
    $numDomandaCorrente = $this->getChiaveDomandaCorrente()+1;
    if ($numDomandaCorrente <= $this->numGiocatori())
    {
      return $numDomandaCorrente;
    }
    else
    {
      return ($numDomandaCorrente % $this->numGiocatori());
    }
  }
  
  /**
   * Restituisce il nome del giocatore corrente
   * 
   * @return string
   */
  public function nomeGiocatoreCorrente()
  {
   
    return $this->nomeGiocatore($this->numeroGiocatoreCorrente()-1);
  }
  
  /**
   * Restituisce il testo di una domanda
   * 
   * @param int $dom Chiave array della domanda
   * @return string
   */
  public function testoDomanda($dom)
  {
    $id = $this->domande[$dom];
    return Doctrine::getTable('QuizDomande')->testoDomanda($id);
  }
  
  /**
   * Restituisce il testo della domanda corrente
   * 
   * @return string
   */
  public function testoDomandaCorrente()
  { 
    
    return $this->testoDomanda($this->getChiaveDomandaCorrente());
  }
  
  public function testiRisposte($dom)
  {
    
    foreach($this->risposte[$dom] as $i => $risposta)
    {
     
      $risp = Doctrine::getTable('QuizRisposte')->testoRisposta($risposta['risposte_id']);
     
      $r[$i] = array(
        'testo' => $risp[0]->Translation['it']->risposta,
      );

    }
    return $r;
  }
  
  public function testiRisposteCorrenti()
  {
    return $this->testiRisposte($this->getChiaveDomandaCorrente());
  }
  
  public function rispostaGiusta($domanda, $risposta)
  {
/*
    echo "Cerco $domanda e $risposta. Verifica ".$this->risposte[$domanda][$risposta]['giusta'];
    echo "<pre>";
    print_r($this->risposte);
    echo "</pre>";
   */ 
    return $this->risposte[$domanda][$risposta]['giusta'];
  }
  
  /**
   * @return unknown_type
   */
  public function turnoSuccessivo()
  {
    $this->setChiaveDomandaCorrente($this->getChiaveDomandaCorrente()+1);
  return $this->getDomandaCorrente() < $this->numDomPerGiocatore()? true : false;
  }
}
