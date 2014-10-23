<?php
define("RDFAPI_INCLUDE_DIR", "././rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

class RdfController extends CI_Controller {

	/*private $answersAndIDsArray;
	private $subjectObjectPredicateIDsArray;*/
	
	private $falseAnswersArray;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->helper('url');
		$this->load->model('usermodel');
		$this->load->library('session');
	}

	function index()
	{	
		$this->load->library("simple_html_dom");
		
		//$this->writeAllStatementAndProperties();
		
		
		
		
		
		
		
		
		/*$sub = $_POST['s'];
		$obj = $_POST['o'];
		$truePre = $_POST['truePredicate'];
		$predicates = $_POST['newPredicates'];
		$rdfGraphName = $_POST['rdfGraph'];*/
		
		//$pre =  $this->putBottomLines($pre);
		
		// Create an empty Model
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
		
		
		
		//$subject = $rdfGraph->createResource("fejs");
		
		////
		$predicate1 = $rdfGraph->createResource("nekipredikat");
		/*$predicate2 = $rdfGraph->createResource("je2");
		$predicate3 = $rdfGraph->createResource("je3");
		$predicate4 = $rdfGraph->createResource("je4");
		$predicate5 = $rdfGraph->createResource("je5");*/
		////
		
		//$object = $rdfGraph->createLiteral("zaraza");
		
		$trueProperty= $rdfGraph->createProperty("true");
		$falseProperty= $rdfGraph->createProperty("false");
		
		
		// ovde treba ono što se uzme kao predikat
		$predicateLiteral1 = $rdfGraph->createLiteral("literalnekogpredikata");
	/*	$predicateLiteral2 = $rdfGraph->createLiteral("je2");
		$predicateLiteral3 = $rdfGraph->createLiteral("je3");
		$predicateLiteral4 = $rdfGraph->createLiteral("je4");
		$predicateLiteral5 = $rdfGraph->createLiteral("je5");*/
		///////
		
		
		
		//$tFalse= $model->createProperty("true");
		
		// Add the property to the predicate
		$predicate1->addProperty($trueProperty, $predicateLiteral1);
		/*$predicate2->addProperty($falseProperty, $predicateLiteral2);
		$predicate3->addProperty($falseProperty, $predicateLiteral3);
		$predicate4->addProperty($falseProperty, $predicateLiteral4);
		$predicate5->addProperty($falseProperty, $predicateLiteral5);*/
		
		
		/////////////////////////////////////////////////////////
		
		// $predicateLiteral = $model->createLiteral("true");
		//	$tFalse= $model->createProperty("false");
		
		// Add the property
		//	$predicate->addProperty($tFalse, $predicateLiteral);
		
		/////////////////////////////////////////////////////////
		
		
		$statement1 = new Statement (null, $predicate1, null);
		/*$statement2 = new Statement ($subject, $predicate2, $object);
		$statement3 = new Statement ($subject, $predicate3, $object);
		$statement4 = new Statement ($subject, $predicate4, $object);
		$statement5 = new Statement ($subject, $predicate5, $object);*/
		
		// ucitavanje RDF grafa
		//	$exists = file_exists($rdfGraphName);
		
		//if($exists==true)
		//	{
		// ovde se prosledi ime RDF grafa, tj putanja i ime
		//	$rdfGraph->load($rdfGraphName);
		//	}
		
		
		
		$rdfGraph->addWithoutDuplicates($statement1);
		/*$rdfGraph->addWithoutDuplicates($statement2);
		$rdfGraph->addWithoutDuplicates($statement3);
		$rdfGraph->addWithoutDuplicates($statement4);
		$rdfGraph->addWithoutDuplicates($statement5);*/
		
		$rdfGraph->saveAs("modelRes.rdf", "rdf");
		
		
	}
	
	
	function removeStatements()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$predicates = $_POST['predicates'];
		$rdfGraphName = $_POST['rdfGraph'];

		// Create an empty Model
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
		
		// ucitavanje RDF grafa
		$exists = file_exists($rdfGraphName);
		
		if($exists==true)
		{
			// ovde se prosledi ime RDF grafa, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
		
		$subject = $rdfGraph->createResource($sub);
		$object = $rdfGraph->createLiteral($obj);

		
		for ($i = 1; $i <= 5; $i++)
		{
			$predicate = $rdfGraph->createResource($this->putBottomLines($predicates[$i]));
			
			$predicate->removeProperties();
			
			$statement = new Statement ($subject, $predicate, $object);
			$rdfGraph->remove($statement);
		}
		
		$rdfGraph->saveAs($rdfGraphName, "rdf");
	}

	// ================================ writeAllStatementAndProperties() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubjectObjectPredicate(form)
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//                   $obj     - objekat iskaza	- stize kroz url u okvicu $_POST
	//					 $pre	  - predikat iskaza - stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu subjekta, objekta i predikta koje je dobila, ova funkcija otvara RDF fajl,
	// i upisuje novi iskaz u rdf graf ukoliko takav vec ne postoji
	// (za upisivanje se koristi fja addWithoutDuplicates($statement))
	//
	function writeAllStatementAndProperties()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$truePre = $this->putBottomLines($_POST['truePredicate']);
		$predicates = $_POST['predicates'];
		$rdfGraphName = $_POST['rdfGraph'];
	
		//$pre =  $this->putBottomLines($pre);
	
		// Create an empty Model
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
		
		// ucitavanje RDF grafa
		$exists = file_exists($rdfGraphName);
		
		if($exists==true)
		{
			// ovde se prosledi ime RDF grafa, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
		$subject = $rdfGraph->createResource($sub);
		$object = $rdfGraph->createLiteral($obj);
		
		$trueProperty= $rdfGraph->createProperty("true");
		$falseProperty= $rdfGraph->createProperty("false");
		
		for ($i = 1; $i <= 5; $i++)
		{
			$predicate = $rdfGraph->createResource($this->putBottomLines($predicates[$i]));
			$predicateLiteral = $rdfGraph->createLiteral($this->putBottomLines($predicates[$i]));
				
			if($this->putBottomLines($predicates[$i])==$truePre)
			{
				$predicate->addProperty($trueProperty, $predicateLiteral);
			}
			else
			{
				$predicate->addProperty($falseProperty, $predicateLiteral);
			}
				
			$statement = new Statement ($subject, $predicate, $object);
			$rdfGraph->addWithoutDuplicates($statement);
		}
		
	/*	foreach ($predicates as $currentPredicate)
		{
			$predicate = $rdfGraph->createResource($this->putBottomLines($currentPredicate));
			$predicateLiteral = $rdfGraph->createLiteral($this->putBottomLines($currentPredicate));
			
			if($this->putBottomLines($currentPredicate)==$truePre)
			{
				$predicate->addProperty($trueProperty, $predicateLiteral);
			}
			else
			{
				$predicate->addProperty($falseProperty, $predicateLiteral);
			}
			
			$statement = new Statement ($subject, $predicate, $object);
			$rdfGraph->addWithoutDuplicates($statement);
		}
		*/
	
		$rdfGraph->saveAs($rdfGraphName, "rdf");
	
	}
	
	/* sigurnosna kopija
	 * 
	 * 
	function writeAllStatementAndProperties()
	{
	
	
	
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$truePre = $_POST['truePredicate'];
		$predicates = $_POST['newPredicates'];
		$rdfGraphName = $_POST['rdfGraph'];
	
		//$pre =  $this->putBottomLines($pre);
	
		// Create an empty Model
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
	

	
		$subject = $rdfGraph->createResource("fejs");
	
		////
		$predicate1 = $rdfGraph->createResource("je1");
		$predicate2 = $rdfGraph->createResource("je2");
		$predicate3 = $rdfGraph->createResource("je3");
		$predicate4 = $rdfGraph->createResource("je4");
		$predicate5 = $rdfGraph->createResource("je5");
		////
	
		$object = $rdfGraph->createLiteral("zaraza");
	
		$trueProperty= $rdfGraph->createProperty("true");
		$falseProperty= $rdfGraph->createProperty("false");
	
	
		// ovde treba ono što se uzme kao predikat
		$predicateLiteral1 = $rdfGraph->createLiteral("je1");
		$predicateLiteral2 = $rdfGraph->createLiteral("je2");
		$predicateLiteral3 = $rdfGraph->createLiteral("je3");
		$predicateLiteral4 = $rdfGraph->createLiteral("je4");
		$predicateLiteral5 = $rdfGraph->createLiteral("je5");
		///////
	
	
	
		//$tFalse= $model->createProperty("true");
	
		// Add the property to the predicate
		$predicate1->addProperty($trueProperty, $predicateLiteral1);
		$predicate2->addProperty($falseProperty, $predicateLiteral2);
		$predicate3->addProperty($falseProperty, $predicateLiteral3);
		$predicate4->addProperty($falseProperty, $predicateLiteral4);
		$predicate5->addProperty($falseProperty, $predicateLiteral5);
	
	
		/////////////////////////////////////////////////////////
		
		// $predicateLiteral = $model->createLiteral("true");
	//	$tFalse= $model->createProperty("false");
	
		// Add the property
	//	$predicate->addProperty($tFalse, $predicateLiteral);
	
		/////////////////////////////////////////////////////////
	
	
		$statement1 = new Statement ($subject, $predicate1, $object);
		$statement2 = new Statement ($subject, $predicate2, $object);
		$statement3 = new Statement ($subject, $predicate3, $object);
		$statement4 = new Statement ($subject, $predicate4, $object);
		$statement5 = new Statement ($subject, $predicate5, $object);
	
		// ucitavanje RDF grafa
		//	$exists = file_exists($rdfGraphName);
	
		//if($exists==true)
	//	{
		// ovde se prosledi ime RDF grafa, tj putanja i ime
	//	$rdfGraph->load($rdfGraphName);
	//	}
		
	
	
		$rdfGraph->addWithoutDuplicates($statement1);
		$rdfGraph->addWithoutDuplicates($statement2);
		$rdfGraph->addWithoutDuplicates($statement3);
		$rdfGraph->addWithoutDuplicates($statement4);
		$rdfGraph->addWithoutDuplicates($statement5);
	
		$rdfGraph->saveAs("modelRes.rdf", "rdf");
	
	}*/
	
	function getAnswerFromClient()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$pre = $_POST['p'];
		
		$currentDateTime = $_POST['currentDateTime'];
		$rdfGraphName = $_POST['rdfGraph'];
		
		$action = "";
		///////////////////////////////
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
		
		$subject = $rdfGraph->createResource($sub);
		$object = $rdfGraph->createLiteral($obj);
		$predicate = $rdfGraph->createResource($this->putBottomLines($pre));
		
		$exists = file_exists($rdfGraphName);
		
		// ucitavanje RDF-a
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
		
		
		$statements = $rdfGraph->find($subject, $predicate, $object);
		
		$trueProperty= $rdfGraph->createProperty("true");
		$falseProperty= $rdfGraph->createProperty("false");
		
		if (count($statements)!=0)
		{
			$br = 0;
			foreach ($statements as $currentStatement)
			{
				
		
				$currentPredicate = $currentStatement->getPredicate();
		
				if(count($currentPredicate->listProperties($trueProperty))!=0)
				{
					
					$action = "a_true";
				}
				else if(count($currentPredicate->listProperties($falseProperty))!=0)
				{
					
					$action = "a_false";
				}

			}
		}
		else 
		{
			$action = "a_false";
		}
		
		$this->usermodel->saveUserActionsDSiALogs($sub, $obj, $pre, $action, $currentDateTime);
		
		//echo $action;

	}
	
	
	/*public function getUserActions()
	{
		$currentLessionNumber = $_POST['currentLessionNumber'];
		$subject = $_POST['subject'];
		$object = $_POST['object'];
		$currentDateTime = $_POST['currentDateTime'];
	
		$this->usermodel->saveUserActions($currentLessionNumber, $subject, $object, $currentDateTime);
	}
	*/
	
	function getPredicates()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$mode = $_POST['mode'];
		$rdfGraphName = $_POST['rdfGraph'];
		
		
		$rdfGraph = ModelFactory::getResModel(MEMMODEL);
		
		$subNominative = $this->getNominative(mb_strtolower($this->putBottomLines($sub),'UTF-8'));
		$objNominative = $this->getNominative(mb_strtolower($this->putBottomLines($obj),'UTF-8'));
		
		$subject = $rdfGraph->createResource($subNominative);
		$object = $rdfGraph->createLiteral($objNominative);
		
		
		////
		/*$predicate1 = $rdfGraph->createResource("je1");
		$predicate2 = $rdfGraph->createResource("je2");
		$predicate3 = $rdfGraph->createResource("je3");
		$predicate4 = $rdfGraph->createResource("je4");
		$predicate5 = $rdfGraph->createResource("je5");*/
		////
		
		
		
		
	
		/*$subject = new Resource ($sub);
		$object = new Literal ($obj);*/
	
	//	$rdfGraph = ModelFactory::getDefaultModel();
		
		//echo $sub. " " . $obj. " " . $rdfGraphName;
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje RDF-a
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
	
		$statements = $rdfGraph->find($subject, NULL, $object);
		

		
	//	$string = $statements[0]->getLabelObject();
		
	//	echo " ". $string . "   velicina: " .count($m) . "! <br />";
		
	//	$pred = $statements[1]->getPredicate();
		
		//$pred->
		
		$trueProperty= $rdfGraph->createProperty("true");
		$falseProperty= $rdfGraph->createProperty("false");
		
		
		/* // ovo ovde radi
		foreach ($pred->listProperties($falseProperty) as $currentResource)
		{
			echo $currentResource->getLabelObject().'<BR>';
		};
		*/
		
		if (count($statements)!=0)
		{
			if ($mode == "read")
			{
				$br = 0;
				foreach ($statements as $currentStatement)
				{
					$br = $br + 1;
					echo "<p class='answerPar' id='idAnswer". $br ."'>" . $br.  ". ";
					echo $this->removeBottomLines($currentStatement->getLabelSubject());
					echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($currentStatement->getLabelPredicate()) . "</span> ";
					echo " " . $this->removeBottomLines($currentStatement->getLabelObject()) . "<BR>";
					echo "<script> setClickEventHandlerStatementsDiv(); </script>";
					
					
					// upisivanje subjekta, predikta i objekta u niz
					/*$this->subjectObjectPredicateIDsArray[$br][0] = $currentStatement->getLabelSubject();
					$this->subjectObjectPredicateIDsArray[$br][1] = $this->removeBottomLines($currentStatement->getLabelPredicate());
					$this->subjectObjectPredicateIDsArray[$br][2] = $currentStatement->getLabelObject();
					
					// upisivanje id-ja paragrafa i true/false vrednosti
					$this->answersAndIDsArray[$br][0] = "idAnswer". $br;*/
					
					
					echo "<script>";
					
					echo "savePredicatesFromServer(\"idAnswer" . $br . "\", \"" . $this->removeBottomLines($currentStatement->getLabelPredicate()) ."\");";

					/*echo "subObjPreArray['idAnswer". $br."'] = \"".$currentStatement->getLabelSubject(). "\";";
					echo "subObjPreArray['idAnswer". $br."'][1] = \"".$this->removeBottomLines($currentStatement->getLabelPredicate()). "\";";
					echo "subObjPreArray['idAnswer". $br."'][2] = \"".$currentStatement->getLabelObject(). "\";";*/
					echo "</script>";
					
				/*	$currentPredicate = $currentStatement->getPredicate();
					
					if(count($currentPredicate->listProperties($trueProperty))!=0)
					{
						$this->answersAndIDsArray[$br][1] = "true";
					}
					else if(count($currentPredicate->listProperties($falseProperty))!=0)
					{
						$this->answersAndIDsArray[$br][1] = "false";
					}
				*/
	
					/*echo $this->subjectObjectPredicateIDsArray[$br][0] . "<br />";
					echo $this->subjectObjectPredicateIDsArray[$br][1] . "<br />";
					echo $this->subjectObjectPredicateIDsArray[$br][2] . "<br />";
	
					echo $this->answersAndIDsArray[$br][0] . "<br />";
					echo $this->answersAndIDsArray[$br][1] . "<br />";*/
					
					
					
					/*echo count($currentPredicate->listProperties($trueProperty));
					
					foreach ($currentPredicate->listProperties($trueProperty) as $currentProperty)
					{
						echo $currentProperty->getLabelObject().'<BR>';
					};*/
	
				/*	echo $currentStatement->getPredicate()->getLabel().'<BR>';
					
					foreach ($pred->listProperties($falseProperty) as $currentStatement)
					{
						echo $currentStatement->getLabelObject().'<BR>';
					};*/
				};
			
			}
			else if($mode=="edit")
			{
				$br = 0;
				foreach ($statements as $currentStatement)
				{
					$br = $br + 1;
					echo "<p class='answerPar' id='idAnswer". $br ."'>" . $br.  ". ";
					echo $currentStatement->getLabelSubject();
					echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($currentStatement->getLabelPredicate()) . "</span> ";
					echo " " . $currentStatement->getLabelObject() . "<BR>";
					
					echo "<script>";
					echo "setClickEventHandlerStatementsDiv();";
					echo "savePredicatesFromServer(\"idAnswer" . $br . "\", \"" . $this->removeBottomLines($currentStatement->getLabelPredicate()) ."\");";
					echo "changeSubmitButtonText(\"Delete relations!\");";
					echo "</script>";
						
				/*	$currentPredicate = $currentStatement->getPredicate();
						
					if(count($currentPredicate->listProperties($trueProperty))!=0)
					{
						$this->answersAndIDsArray[$br][1] = "true";
					}
					else if(count($currentPredicate->listProperties($falseProperty))!=0)
					{
						$this->answersAndIDsArray[$br][1] = "false";
					}*/

				}
			}
		
		}
		else 
		{
			if($mode == "read")
			{
				
			
			//echo "Trenutno ne postoji veza izmedju pojmova";
			
			$statements = $rdfGraph->find(NULL, NULL, NULL);
			
			$br = 0;
			$this->falseAnswersArray[1] = "";
			
			foreach ($statements as $currentStatement)
			{
				$currentPredicate = $currentStatement->getPredicate();
					
				if(count($currentPredicate->listProperties($falseProperty))!=0)
				{
					//$this->answersAndIDsArray[$br][1] = "false";
					if(!in_array($this->removeBottomLines($currentStatement->getLabelPredicate()), $this->falseAnswersArray))
					{
						$br = $br + 1;
						$this->falseAnswersArray[$br] = $this->removeBottomLines($currentStatement->getLabelPredicate());
					}
				}
			
			}
			
			if(count($this->falseAnswersArray)<5)
			{
				$rand_keys = array_rand($this->falseAnswersArray, count($this->falseAnswersArray));
			}
			else
			{
				$rand_keys = array_rand($this->falseAnswersArray, 5);
			}
			//echo count($this->falseAnswersArray);
			
			$br = 0;
			
			foreach ($rand_keys as $rand_key)
			{
				$br = $br + 1;
				echo "<p class='answerPar' id='idAnswer". $br ."'>" . $br.  ". ";
				//echo $currentStatement->getLabelSubject();
				echo $sub;
				echo " <span style='color:green; font-weight:bold;'>" . $this->falseAnswersArray[$rand_key] . "</span> ";
			//	echo " " . $currentStatement->getLabelObject() . "<BR>";
				echo " " . $obj . "<BR>";
				echo "<script> setClickEventHandlerStatementsDiv(); </script>";
				
				echo "<script>";
					
				echo "savePredicatesFromServer(\"idAnswer" . $br . "\", \"" . $this->falseAnswersArray[$rand_key] ."\");";
				
				echo "</script>";
					
				/*$currentPredicate = $currentStatement->getPredicate();
					
				if(count($currentPredicate->listProperties($trueProperty))!=0)
				{
					$this->answersAndIDsArray[$br][1] = "true";
				}
				else if(count($currentPredicate->listProperties($falseProperty))!=0)
				{
					$this->answersAndIDsArray[$br][1] = "false";
				}*/
			}
			
			/*
			$br = 0;
			foreach ($statements as $currentStatement)
			{
				$br = $br + 1;
				echo "<p class='answerPar' id='idAnswer". $br ."'>" . $br.  ". ";
				echo $currentStatement->getLabelSubject();
				echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($currentStatement->getLabelPredicate()) . "</span> ";
				echo " " . $currentStatement->getLabelObject() . "<BR>";
				echo "<script> setClickEventHandlerStatementsDiv(); </script>";

				echo "<script>";
			
				echo "savePredicatesFromServer(\"idAnswer" . $br . "\", \"" . $this->removeBottomLines($currentStatement->getLabelPredicate()) ."\");";

				echo "</script>";
			
				$currentPredicate = $currentStatement->getPredicate();
			
				if(count($currentPredicate->listProperties($trueProperty))!=0)
				{
					$this->answersAndIDsArray[$br][1] = "true";
				}
				else if(count($currentPredicate->listProperties($falseProperty))!=0)
				{
					$this->answersAndIDsArray[$br][1] = "false";
				}

			}*/
			}
			else if($mode == "edit")
			{

				echo "no_relations";
				
				/*echo "<script>";
				echo "no_relations = 'true';";
				echo "changeSubmitButtonText(\"". "Submit relations!" ."\");";
				echo "</script>";*/

			}
		}
		
		/*
		  //// ovo ovde radi
		 echo $pred->getLabel();
		
		echo "ima property " . $pred->hasProperty($trueProperty);*/
		
		
		
		
	/*	while ($it->hasNext()) {
			$statement = $it->next();
		
			$subjectsObjects .= $statement->getLabelSubject() . " ";
		
			$subjectsObjects .= $statement->getLabelObject() . " ";
		}*/
		
		/*for ($statements->rewind(); $statements->valid(); $statements->next())
		{
			$currentResource=$statements->current();
			echo $currentResource->getLabel().'<BR>';
		};
		*/
		/*if($statements->size() == 0)
		{
			echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $statements->getStatementIterator();
	
			while ($it->hasNext()) {
	
				$statement = $it->next();
	
				echo $statement->getLabelSubject();
				echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($statement->getLabelPredicate()) . "</span> ";
				echo " " . $statement->getLabelObject() . "<BR>";
			}
	
	
		}*/
	
		//$rdfGraph->close();
	
	}
	
	// vraca nominativ reci koja joj se prosledi, otvori rdf fajl sa recima u svim padežima
	// nadje tu rec, uzme nominativ i vrati kao odgovor
	function getNominative($word)
	{
	
		$rdfGraphName = "modelResSviPadezi.rdf";
	
		$object = new Literal ($word);
	
		$rdfGraph = ModelFactory::getDefaultModel();
	
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje RDF-a
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
	
		$m = $rdfGraph->find(NULL, NULL, $object);
	
		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
			return $word;
		}
		else
		{
			$it = $m->getStatementIterator();
	
			while ($it->hasNext()) {
	
				$statement = $it->next();
	
				return $statement->getLabelSubject();
	
			}
	
		}
	
		$rdfGraph->close();
	
	}
	
	function pickRandomNumbers($maxNumber)
	{
		while(count($randomNumbers)<=5)
		{
			$randomNumbers[count($randomNumbers) + 1] = rand(1, $maxNumber);
		}
		return $randomNumbers;
	}
	
	// ================================ putBottomLines($str) ================================
	//
	// poziva je fja writeStatement()
	//
	// Ulazni parametri: $str - string kod koga treba da se umesto blanko znaka " " stave donje crte "_"
	//
	function putBottomLines($str)
	{
		return str_replace(" ", "_", $str);
	}

	// ================================ removeBottomLines($str) ================================
	//
	// poziva je fja getPredicate()
	//
	// Ulazni parametri: $str - string kod koga treba da se umesto donjih crta "_" stave blanko znaci " " 
	//
	function removeBottomLines($str)
	{
		return str_replace("_", " ", $str);
	}

	// ================================ uploadRdfGraph() ================================
	//
	// poziva se sa klijenta prilikom upload-a rdf grafa,
	// link ka ovoj fji se postavlja u action propertiju forme za upload rdf grafa
	//
	function uploadRdfGraph()
	{
		$uploaddir = '';
		$uploadfile = $uploaddir . basename($_FILES['filesRdf']['name']);
		echo basename($_FILES['filesRdf']['name']);
	
		if (move_uploaded_file($_FILES['filesRdf']['tmp_name'], $uploadfile))
		{
			echo "success";
			
			echo
			
			// ukoliko je u pitanju ReadController onda se vrsi spanovanje nakon upload-a
			// rdf fajla, pa se nakon toga prikazuje dugme za download
			// ovo radimo kako bi obezbedili spanovanje teksta na klijentu tek nakon
			// zavrsenog upload-a rdf fajla na server
			"<script>
				
				if(parent.config.controller=='ReadController')
				{
					parent.span();
				}
					
			</script>";
		}
		else
		{
			echo "error";
		}
	}
	
	// ================================ uploadTextFile() ================================
	//
	// poziva se sa klijenta prilikom upload-a text fajla,
	// link ka ovoj fji se postavlja u action propertiju forme za upload fajla sa tekstom
	//
	function uploadTextFile()
	{
		$uploaddir = './textFiles/';
		$uploadfile = $uploaddir . basename($_FILES['filesText']['name']);
		echo basename($_FILES['filesText']['name']);
	
		if (move_uploaded_file($_FILES['filesText']['tmp_name'], $uploadfile))
		{
			echo "success";
			
			// poziv fje na klijentu iz iframe-a pristupanjem njegovom parent-u, tj celom window objektu
			// ovo radimo kako bi obezbedili ucitavanje teksta na klijentu tek nakon zavrsenog upload-a na server
			echo
			
			"<script>
					
					parent.getTextFromServer(\"". $_FILES['filesText']['name'] . "\");
							
			</script>";
	
		}
		else
		{
			echo "error";
		}
	}
	
	// ================================ writeStatement() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubjectObjectPredicate(form)
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//                   $obj     - objekat iskaza	- stize kroz url u okvicu $_POST
	//					 $pre	  - predikat iskaza - stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST                
	//
	// Na osnovu subjekta, objekta i predikta koje je dobila, ova funkcija otvara RDF fajl,
	// i upisuje novi iskaz u rdf graf ukoliko takav vec ne postoji
	// (za upisivanje se koristi fja addWithoutDuplicates($statement))
	//
	function writeStatement()
	{

		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$pre = $_POST['p'];
		$rdfGraphName = $_POST['rdfGraph'];
		
		$pre =  $this->putBottomLines($pre);

		$subject = new Resource ($sub);
		$object = new Literal ($obj);
		$predicate = new Resource ($pre);

		$statement = new Statement ($subject, $predicate, $object);

		$rdfGraph = ModelFactory::getDefaultModel();

		// ucitavanje RDF grafa
		$exists = file_exists($rdfGraphName);

		if($exists==true)
		{
			// ovde se prosledi ime RDF grafa, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}

		$rdfGraph->addWithoutDuplicates($statement);

		$rdfGraph->saveAs($rdfGraphName, "rdf");

		$rdfGraph->close();
		
	}

	// ================================ getPredicate() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubjectObject()
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//                   $obj     - objekat iskaza	- stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu subjekta i objekta koje je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze koji imaju taj subjekat i objekat (rezultat je lancana
	// lista RDF iskaza u promenljivoj tipa klase MemModel). Onda se prodje kroz ovu listu
	// iskaza, i iz svakog se izvuce predikat (veza). Prilikom svakog izvlacenja veze,
	// stampa (echo) subjekat, zelenu vezu (predikat) i objekat, nazad ka klijentu.
	function getPredicate()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$rdfGraphName = $_POST['rdfGraph'];

		$subject = new Resource ($sub);
		$object = new Literal ($obj);

		$rdfGraph = ModelFactory::getDefaultModel();


		$exists = file_exists($rdfGraphName);

		// ucitavanje RDF-a
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}


		$m = $rdfGraph->find($subject, NULL, $object);

		if($m->size() == 0)
		{
			echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
				
			while ($it->hasNext()) {

				$statement = $it->next();

				echo $statement->getLabelSubject();
				echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($statement->getLabelPredicate()) . "</span> ";
				echo " " . $statement->getLabelObject() . "<BR>";
			}
				
				
		}

		$rdfGraph->close();

	}

	// ================================ getObjects() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubject()
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu subjekta koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze koji imaju taj subjekat (rezultat je lancana
	// lista RDF iskaza u promenljivoj tipa klase MemModel). Onda se prodje kroz ovu listu
	// iskaza, i iz svakog se izvuce objekat. Prilikom svakog izvlacenja objekta,
	// o se istovremeno i stampa (echo), nazad ka klijentu
	// (stampa se u obliku jQuery selektora " span.dragdrop:contains('OBJEKAT') ", kako bi te objekte pronasli u tekstu).
	//
	function getObjects()
	{
		$sub = $_POST['s'];
		$rdfGraphName = $_POST['rdfGraph'];

		$subject = new Resource ($sub);

		$rdfGraph = ModelFactory::getDefaultModel();

		$exists = file_exists($rdfGraphName);

		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}

		$m = $rdfGraph->find($subject, NULL, NULL);

		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();

			while ($it->hasNext()) {
				$statement = $it->next();

				if($it->hasNext())
				{
					echo "span.dragdrop:contains(" . $statement->getLabelObject() . "),";
				}
				else
				{
					echo "span.dragdrop:contains(" . $statement->getLabelObject() . ")";
				}
			}
				
		}
		
		$rdfGraph->close();

	}

	// ================================ getSubjectsObjects() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja spanReadMode()
	//
	// Ulazni parametri: $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu naziva rdf fajla koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze
	// (tj sve subjekte i objekte u obliku niza stringova pomocu fje getStringArraySO($rdfGraphName))
	// Ka klijentu se stampa (echo) niz subjekata i objekata u obliku jednog stringa gde su reci odvojene znakom "|"
	// kako bi se taj string iskoristio kao regular expression za pronalazenje tih reci u tekstu
	//
	function getSubjectsObjects()
	{
		$rdfGraphName = $_POST['rdfGraph'];
	
		$exists = file_exists($rdfGraphName);
	
		if ($exists == true)
		{
			$arraySO= $this->getStringArraySO($rdfGraphName);
				
			$subjectsObjects = "";
	
			foreach ($arraySO as $SubObj)
			{
				//$this->removeBottomLines($SubObj);
				$subjectsObjects.=$this->removeBottomLines($SubObj)."|";
				$subjectsObjects.=$this->getAllFormsOfSubjectsObjects($SubObj);
			}
	
			$subjectsObjects = substr($subjectsObjects, 0, -2);
	
			echo $subjectsObjects;
	
		}
		else
		{
			echo "";
		}
	}
	
	// funkcija koja vraca sve oblike reci koja joj se prosledi, tj tu rec u svim padežima
	// ovo se radi kad se spanuju reci kako bi bili spanovani svi oblici neke reci
	function getAllFormsOfSubjectsObjects($sub)
	{
		$rdfGraphName = "modelResSviPadezi.rdf";
	
		$subject = new Resource ($sub);
	
		$rdfGraph = ModelFactory::getDefaultModel();
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
		$m = $rdfGraph->find($subject, NULL, NULL);
	
		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
	
			while ($it->hasNext()) {
				$statement = $it->next();
	
				if($it->hasNext())
				{
					echo $this->removeBottomLines($statement->getLabelObject()). "|";
				}
				else
				{
					echo $this->removeBottomLines($statement->getLabelObject()). "|";
				}
			}
	
		}
	
		$rdfGraph->close();
	
	}
	
	// ================================ getStringArraySO($rdfGraphN) ================================
	//
	// poziva je fja getSubjectsObjects()
	//
	// Ulazni parametri: $rdfGraphN - ime RDF fajla
	//
	// Na osnovu naziva rdf fajla koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze (tj sve subjekte i objekte u obliku niza stringova)
	// ova fja vraca niz stringova (subjekte i objekte)
	//
	function getStringArraySO($rdfGraphN)
	{
	
		$subjectsObjects = "";
	
		$rdfGraphName = $rdfGraphN;
	
		$rdfGraph = ModelFactory::getDefaultModel();
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
		$m = $rdfGraph->find(NULL, NULL, NULL);
	
		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
	
			while ($it->hasNext()) {
				$statement = $it->next();
	
				$subjectsObjects .= $statement->getLabelSubject() . " ";
	
				$subjectsObjects .= $statement->getLabelObject() . " ";
			}
		}
		
		$rdfGraph->close();
	
		$arraySO = explode(' ', $subjectsObjects);
		$nizSubObj = array_unique($arraySO);
	
		return $nizSubObj;
	
	}

	// ================================ getText() ================================
	//
	// ajax odgovor
	// poziva je fja sa klijenta getTextFromServer(tFileName)
	//
	// Ulazni parametri: $textFileName - ime fajla sa tekstom - stize kroz url u okvicu $_POST
	//
	// Na osnovu naziva fajla sa tekstom koji je dobila, ova funkcija cita sadrzaj tog fajla i
	// stampa (echo) taj sadrzaj ka klijentu
	//
	function getText()
	{
		$textFileName = $_POST['textFile'];
		$textFromFile = $this->readText($textFileName);
		echo $textFromFile;
	}
	
	// ================================ readText($par) ================================
	//
	// poziva je fja getText()
	//
	// Ulazni parametri: $par - ime fajla sa tekstom
	//
	// Na osnovu naziva fajla sa tekstom koji je dobila, ova funkcija cita sadrzaj tog fajla i
	// vraca ga kao rezultat poziva fje
	//
	function readText($par)
	{
		$this->load->helper('file');
		$textFromFile = read_file("./textFiles/" . $par);
		return $textFromFile;
	}
	

}

?>