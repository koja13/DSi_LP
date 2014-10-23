<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class usermodel extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // ================================ login($username, $password) ================================
    //
    // poziva je fja login() === usercontroller ===
    //
    // Ulazni parametri: $username     - korisnicko ime
    //                   $password     - sifra		 
    //
    // fja na osnovu prosledjenog korisnickog imena i sifre pretrazuje bazu podataka trazeci odgovarajuceg
    // korisnika, ukoliko takav korisnik postoji citaju se njegovi podaci i upisuju u niz koji se cuva
    // u okviru session promenljive (kako bi bio dostupan u svakom momentu dok je sesija aktivna)
    //
    
	function login($email,$password)
    {

    	// trazi korisnika sa zadatim mejlom i sifrom u bazi podataka
		$this->db->where("email",$email);
        $this->db->where("password",$password);
        
        $query=$this->db->get("user");
        if($query->num_rows()>0)
        {
         	foreach($query->result() as $rows)
            {
            	//add all data to session
            	
            	// upisivanje u session cookie kako ne bi morali stalno
            	// da pristupamo bazi da vidimo koji je korisnik logovan
                $newdata = array(
                	   	'user_id' 		=> $rows->id,
                    	'user_name' 	=> $rows->username,
		                'user_email'    => $rows->email,
                		'use_dsi'    => $rows->use_dsi,
                		'account_type' => $rows->account_type,
	                    'logged_in' 	=> TRUE,
                   );
			}
            	$this->session->set_userdata($newdata);
                return true;            
		}
		return false;
    }
    
    // ================================ addUserDSI() ================================
    //
    // poziva je fja registration() === usercontroller ===
    //
    // fja dodaje novog korisnika u bazu podataka (tip naloga je d, kao DSI, ovo je direktna registracija)
    //
    
	public function addUserDSI()
	{
		$data=array(
			'username'=>$this->input->post('user_name'),
			'email'=>$this->input->post('email_address'),
			'password'=>md5($this->input->post('password')),
			'account_type'=> 'd',
			);
		$this->db->insert('user',$data);
	}
	

	// ================================ registerFBuser() ================================
	//
	// poziva je fja registration() === usercontroller ===
	//
	// fja korisniku koji se vec prijavio preko fb naloga menja podatke u bazi, stavlja da je tip naloga d
	// (kao DSi) i dodaje mu korisnicko ime i sifru unete prilikom registracije
	//
	
	public function registerFBuser()
	{
		$data = array(
				'username' => $this->input->post('user_name'),
				'password' => md5($this->input->post('password')),
				'account_type' => 'd'
		);
	
		$this->db->where('email', $this->session->userdata('user_email'));
		$this->db->update('user', $data);
	
	}
	
	
	
	// ================================ addUserFB($name, $username, $email, $account_type) ================================
	//
	// poziva je fja getUserDataFB() === usercontroller ===
	//
	// Ulazni parametri: $name		   - ime i prezime fb korisnika
	//					 $username     - korisnicko ime
	//                   $email        - email
	//                   $account_type - tip naloga
	//
	// fja dodaje novog korisnika u bazu podataka, ukoliko je tip naloga f (kao fb) podrazumeva se da se 
	// korisnik loguje preko fb naloga, ukoliko se korisnik vec logovao ranije onda se samo uzimaju
	// podaci iz baze i upisuju u promenljivu sesije
	//
	

	public function addUserFB($name, $email, $use_dsi ,$account_type)
	{
		//$this->db->where("username",$username);
		$this->db->where("email",$email);
		//$this->db->where("password",$password);
		
		$query=$this->db->get("user");
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				//$account_type = $rows->account_type;
				$newdata = array(
						'user_id' 		=> $rows->id,
						//'user_name' 	=> $rows->username,
						'user_email'    => $rows->email,
						'use_dsi'       => $rows->use_dsi,
						'account_type'  => $rows->account_type,
						'logged_in' 	=> TRUE,
				);
			}
			$this->session->set_userdata($newdata);
			
		}
		else
		{
		
			// upisivanje podataka o korisniku preuzetih sa fb
			$data=array(
					//'name'=> $name,
				//	'username'=> $username,
					'email'=> $email,
                    'use_dsi' => $use_dsi,
					'account_type' => $account_type
			);
			$this->db->insert('user',$data);
			
			// uzimimanje informacija o korisniku iz baze i njihovo upisivanje u promeniljivu sesije
			$this->db->where("username",$username);
			$this->db->where("email",$email);
			
			$query1=$this->db->get("user");
			if($query1->num_rows()>0)
			{
				foreach($query1->result() as $rows)
				{
					//$account_type = $rows->account_type;
					$newdata = array(
							'user_id' 		=> $rows->id,
					//		'user_name' 	=> $rows->username,
							'user_email'    => $rows->email,
							'use_dsi'    => $rows->use_dsi,
							'account_type' => $rows->account_type,
							'logged_in' 	=> TRUE,
					);
				}
				$this->session->set_userdata($newdata);
					
			}
		}
	}
	
	
	// ================================ getQuestions() ================================
	//
	// poziva je fja quiz() === usercontroller ===
	//
	// fja cita iz baze pitanja za kviz a zatim ih stampa ka klijentu ($data niz) u formi div-pitanje
	// prva tri pitanja su vidljiva (ostatak pitanja je sakriven uspomoc display:none)
	//
	
	public function getQuestions()
	{
		$query=$this->db->get("quiz_questions");
		if($query->num_rows()>0)
		{	
			foreach($query->result() as $rows)
			{
				$qNo = $rows->question_number;
				$ques = $rows->question;
				$correct_answer_number = $rows->correct_answer_number;
				$answer1 = $rows->answer1;
				$answer2 = $rows->answer2;
				$answer3 = $rows->answer3;
				
			//	if($qNo<4)
				//{
					$question = " <div id='q" .$qNo. "' class='question'>" .
					"<p id='question" .$qNo. "' class ='qPar'>".$qNo. ". ".$ques. "</p> " .
					"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label class='answerLabel' for='q" .$qNo. "a1'>" . $answer1 . "</label></p>" .
					"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label class='answerLabel'  for='q" .$qNo. "a2'>" . $answer2 . "</label></p>" .
					"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label class='answerLabel'  for='q" .$qNo. "a3'>" . $answer3 . "</label></p>" .
					"</div>";
				//}
				//else
				//{
				/*	$question = " <div id='q" .$qNo. "' style='display:none;' class='question'>" .
							"<p id='question" .$qNo. "' class ='qPar'>" .$qNo. ". ".$ques. "</p> <br />" .
							"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label class='answerLabel'  for='q" .$qNo. "a1'>" . $answer1 . "</label></p>" .
							"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label  class='answerLabel' for='q" .$qNo. "a2'>" . $answer2 . "</label></p>" .
							"<p class='answer'><input class='radio' type='radio' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label  class='answerLabel' for='q" .$qNo. "a3'>" . $answer3 . "</label></p>" .
							"</div>";
				}*/
				$questions[$qNo] = $question;
				
			}
			
			$data["questions"] = $questions;
			return $data;
		}
	}
	
	
	// ================================ saveQuizResults($userAnswers) ================================
	//
	// poziva je fja getQuizResults() === usercontroller ===
	//
	// Ulazni parametri: $userAnswers	  - niz stringova sa odgovorima na pitanaj u kvizu
	//
	// fja koja upisuje korisnikove odgovore na pitanja u bazu podataka
	//
	
	public function saveQuizResults($userAnswers)
	{
		for($i=1;$i<count($userAnswers);$i++)
		{
			$data[$i] = array(
					'session_id' => $this->session->userdata('session_id'),
					'user_name' => $this->session->userdata('user_name') ,
					'question_number' => $i ,
					'user_answer' => $userAnswers[$i]
			);
			
			$this->db->insert('quiz_results', $data[$i]);
		}
		return "Success";
	}
	
	// ======== saveUserActions($currentLessionNumber, $subject, $object, $currentDateTime) ========
	//
	// poziva je fja getUserActions() === usercontroller ===
	//
	// Ulazni parametri: $currentLessionNumber	  	- broj lekcije na kojoj se desilo prevljacenje reci na rec
	//					 $subject     				- objekat
	//                   $object        			- subjekat
	//                   $currentDateTime 			- datum/vreme akcije
	//
	// fja koja upisuje korisnikovu akciju prevlacenja u bazu podataka, upisuju se trenutni br lekcije
	// subjekat, objekat i vreme prevlacenja
	//
	
	public function saveUserActions($currentLessionNumber, $subject, $object, $currentDateTime)
	{/*
		for($i=1;$i<count($userAnswers);$i++)
		{*/
		$data = array(
						'session_id' => $this->session->userdata('session_id'),
						'user_name' => $this->session->userdata('user_name') ,
						'lession_number' => $currentLessionNumber ,
						'subject' => $subject,
						'object' => $object,
						'time' => $currentDateTime
					);
							
			$this->db->insert('user_actions', $data);
		//}
	}
	
	
	public function saveUserActionsDSiALogs($subject, $object, $predicate, $action, $currentDateTime)
	{
		$data = array(
				'session_id' => $this->session->userdata('session_id'),
				'user_name' => $this->session->userdata('user_name') ,
				'subject' => $subject,
				'object' => $object,
				'predicate' => $predicate,
				'action' => $action,
				'time' => $currentDateTime
		);
		
		$this->db->insert('dsi_a_logs', $data);
	
	}
	
	// ======== saveUserActionsLessions($currentLessionNumber, $action, $next_prev_lession_number,$currentDateTime) ========
	//
	// pozivaju je fje getUserActionsLessions() i logout() === usercontroller ===
	//
	// Ulazni parametri: $currentLessionNumber	  	- broj lekcije na kojoj se desilo prevljacenje reci na rec
	//					 $action     				- akcija koja je obavljena
	//                   $next_prev_lession_number  - broj lekcije na koju se prelazi (sledeca ili prethodna, ili null)
	//                   $currentDateTime 			- datum/vreme akcije
	//
	// fja koja upisuje u bazu korisnikovu akciju pokretanja odredjene lekcije, tj prelaska sa jedne na drugu lekciju
	// u bazi se cuvaju broj lekcije na kojoj se korisnik trenutno nalazi, obavljena akcija (stisnuto dugme prev, next, pokretanje dela za ucenje,
	// zavrsetak ucenja, pokretanje kviza, prosledjivanje rezultata kviza, logout korisnika...)
	//
	
	public function saveUserActionsLessions($currentLessionNumber, $action, $next_prev_lession_number,$currentDateTime)
	{
		$data = array(
				'session_id' => $this->session->userdata('session_id'),
				'user_name' => $this->session->userdata('user_name') ,
				'lession_number' => $currentLessionNumber ,
				'action' => $action,
				'next_prev_lession_number' => $next_prev_lession_number,
				'time' => $currentDateTime
		);
			
		$this->db->insert('user_actions_lessions', $data);
	}
	
	// ================================ getResults() ================================
	//
	// poziva je fja QuizResultPage() === usercontroller ===
	//
	// fja cita iz baze korisnikove odgovore na pitanja u kvizu a zatim ih stampa ka klijentu ($data niz) u formi div-pitanje
	// 
	
	public function getResults()
	{
		$query1=$this->db->get("quiz_questions");
		
		$sql = "SELECT question_number, user_answer FROM quiz_results WHERE session_id = '".$this->session->userdata('session_id')."' AND user_name = '".$this->session->userdata('user_name')."';";
		
		$query2 = $this->db->query($sql);
		$userAnswers = null;
		foreach ($query2->result() as $row)
		{
			$userAnswers[$row->question_number] = $row->user_answer;
			//echo $row->question_number;
			//echo $row->user_answer;
		}
		
		$counter = 1;

		if($query1->num_rows()>0)
		{
			foreach($query1->result() as $rows)
			{
				$qNo = $rows->question_number;
				$ques = $rows->question;
				$correct_answer_number = $rows->correct_answer_number;
				$answer1 = $rows->answer1;
				$answer2 = $rows->answer2;
				$answer3 = $rows->answer3;
	
				$question = " <div id='q" .$qNo. "' class='question'>" .
							"<p id='question" .$qNo. "' class ='qPar'>".$qNo. ". ".$ques. "</p> <br />";
				
				if($userAnswers[$qNo]==1)
				{
					if($correct_answer_number==1)
					{
					
						$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "' checked> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> <img src=". base_url("correct.png")." alt='correct' height='23' width='27'> </p>";
						$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
						$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
						$question .="</div>";
					}
					else
					{
						if($correct_answer_number==2)
						{
						
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "' checked> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'> </p>";
							$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> </p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
							$question .="</div>";
						}
						else
						{
							if($correct_answer_number==3)
							{
							
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "' checked> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'> </p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
								$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> </p>";
								$question .="</div>";
							}

						}
					}

				}
				else
				{
					if($userAnswers[$qNo]==2)
					{
						/*$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label></p>";
						$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "' checked> <label for='q" .$qNo. "a2'>" . $answer2 . "</label><img src='<?php echo base_url('/correct.jpg')?>' alt='correct' height='23' width='23'></p>";
						$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
						"</div>";*/
						
						if($correct_answer_number==1)
						{
								
							$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label></p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'  checked> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'> </p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
							$question .="</div>";
						}
						else
						{
							if($correct_answer_number==2)
							{
						
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
								$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'  checked> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> <img src=". base_url("correct.png")." alt='correct' height='23' width='27'></p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
								$question .="</div>";
							}
							else
							{
								if($correct_answer_number==3)
								{
										
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'  checked> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'></p>";
									$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> </p>";
									$question .="</div>";
								}
						
							}
						}
						

					}
					else
					{
						if($userAnswers[$qNo]==3)
						{
							/*$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label></p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "' checked> <label for='q" .$qNo. "a3'>" . $answer3 . "</label><img src='<?php echo base_url('/correct.jpg')?>' alt='correct' height='23' width='23'></p>";
							"</div>";*/
							
							if($correct_answer_number==1)
							{
							
								$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "' checked> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'> </p>";
								$question .="</div>";
							}
							else
							{
								if($correct_answer_number==2)
								{
							
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
									$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> </p>";
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "' checked> <label for='q" .$qNo. "a3'>" . $answer3 . "</label>  <img src=". base_url("wrong.png")." alt='wrong' height='23' width='23'> </p>";
									$question .="</div>";
								}
								else
								{
									if($correct_answer_number==3)
									{
							
										$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
										$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> </p>";
										$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'  checked> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> <img src=". base_url("correct.png")." alt='correct' height='23' width='27'></p>";
										$question .="</div>";
									}
							
								}
							}
							

						}
						else
						{
							
							if($correct_answer_number==1)
							{
									
								$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label></p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
								$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> </p>";
								$question .="</div>";
							}
							else
							{
								if($correct_answer_number==2)
								{
										
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
									$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> </p>";
									$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label>  </p>";
									$question .="</div>";
								}
								else
								{
									if($correct_answer_number==3)
									{
											
										$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label> </p>";
										$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label> </p>";
										$question .= "<p class='answerResultCorrect'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label> </p>";
										$question .="</div>";
									}
										
								}
							}
							
							/*
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a1' value='" .$answer1. "'> <label for='q" .$qNo. "a1'>" . $answer1 . "</label></p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a2' value='" .$answer2. "'> <label for='q" .$qNo. "a2'>" . $answer2 . "</label></p>";
							$question .= "<p class='answerResult'><input class='radio' type='radio' disabled='disabled' name='q" .$qNo. "' id='q" .$qNo. "a3' value='" .$answer3. "'> <label for='q" .$qNo. "a3'>" . $answer3 . "</label></p>";
							$question .="</div>";*/
						}
					}
					
				}

				
				$questions[$qNo] = $question;
				
				//echo $questions[$qNo];
			}
				
			$data["questions"] = $questions;
			return $data;
		}
	}
}
?>