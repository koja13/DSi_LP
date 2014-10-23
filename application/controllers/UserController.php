<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class usercontroller extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('usermodel');
	}
	
	// ================================ index() ================================
	//
	// poziva se pokretanjem usercontroller-a automatski
	//
	// ova fja automatski pokrece Sign In stranu
	//
	public function index()
	{
		if(($this->session->userdata('user_name')!=""))
		{
			$this->welcome();
		}
		else{
			$data['title']= 'Sign In | DSi A';
			$this->load->view('HeaderView',$data);
			$this->load->view("SignInView.php", $data);
			$this->load->view('FooterView',$data);
		}
	}
	
	// ================================ login() ================================
	//
	// poziva se u okviru forme za prijavu na sistem, klikom na dugme Sign In (Submit dugme)
	//
	// ova fja ukoliko su uneti podaci za prijavu ispravni (proverava se u bazi) prosledjuje korisnika
	// na welcome stranu, koja sledi posle prijave korisnika na sistem
	//
	public function login()
	{
		$email=$this->input->post('email_address');
		$password=md5($this->input->post('pass'));
	
		$result=$this->usermodel->login($email,$password);
		if($result)
		{
			$this->welcome();
		}
		else
		{
			//$this->index();
			if(($this->session->userdata('user_name')!=""))
			{
				$this->welcome();
			}
			else{
				$data['title']= 'Sign In | DSi A';
				$data['error_message']= "Login failed. Try again!";
				$this->load->view('HeaderView',$data);
				$this->load->view("SignInView.php", $data);
				$this->load->view('FooterView',$data);
			}
				
		}
	
	}
	
	// ================================ thanks() ================================
	//
	// poziva je fja registration() == usercontroller ==
	//
	// ova fja nakon uspesne registracije otvara stranu na kojoj je korisniku ispisano da se uspesno registrovao
	// i na kojoj moze da se prijavi na sistem
	//
	public function thanks()
	{
		$data['title']= 'Sign In | DSi A';
		$data['message'] = "Thanks for registering!";
		$this->load->view('HeaderView',$data);
		$this->load->view("SignInView.php", $data);
		//$this->load->view('thank_view.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ registration() ================================
	//
	// poziva se u okviru forme za registrovanje na sistem, klikom na Submit dugme
	//
	// ova fja registruje korisnika na sistem (cuva podatke u bazi), prvo se izvrsi provera (form validation) za svako polje u okviru forme
	// nakon toga ukoliko su ispravno uneseni podaci pozivaju se ---- fja addUserDSI() ili fja registerFBuser() --- == usermodel ==
	// koje unose podatke u bazu a korisnik se onda preusmerava na stranu gde mu se daje do znanja da se uspesno registrovao, i da moze da se loguje
	// 
	public function registration()
	{
		$this->load->library('form_validation');
        
		// field name, error message, validation rules
		$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[6]|xss_clean|is_unique[user.username]');
        // ne raditi validaciju emaila ukoliko se korisnik vec ulogovao koristeci fb account
        if($this->session->userdata('account_type') !="f")
        {
            $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email|is_unique[user.email]');
		}
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');
	
		if($this->form_validation->run() == FALSE)
		{
			//$this->index();
            echo $this->session->userdata('account_type');
			$this->register();
			//echo validation_errors();
		}
		else
		{
		  if($this->session->userdata('account_type') =="f")
          {
            // ako se registruje korisnik koji se vec logovao fb nalogom
            $this->usermodel->registerFBuser();
			$this->thanks();
            
          }
          else
          {
            // ako se korisnik registruje regularno
			$this->usermodel->addUserDSI();
			$this->thanks();
          }
		}
	}
	
	// ================================ register() ================================
	//
	// poziva je fja registration() == usercontroller ==
	//
	// ova fja redirektuje na stranu za registrovanje, poziva se ukoliko podaci za registrovanje nisu ispravno uneseni u formu za registraciju
	//
	public function register()
	{
		if(($this->session->userdata('user_name')!=""))
		{
			$this->welcome();
		}
		else{
			$data['title']= 'Registration | DSi A';
			$this->load->view('HeaderView',$data);
			$this->load->view("RegistrationView", $data);
			$this->load->view('FooterView',$data);
		}
	}
	
	// ================================ registerFBUser() ================================
	//
	// poziva se klikom na link u navigation divu u okviru MainView pogleda
	//
	// ova fja redirektuje na stranu za registrovanje kada je korisnik ulogovan pomocu fb naloga
	//
	public function registerFBUser()
	{
		$data['title']= 'Registration | DSi A';
		$this->load->view('HeaderView',$data);
		$this->load->view("RegistrationView", $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ getUserDataFB() ================================
	//
	// odogovor na ajax poziv fje saveUserDataFromFB(response) == fbLoginScript.js ==
	//
	// ova preuzima podatke iz post zahteva prosledjene od klijenta i prosledjuje ih modelu kako bi bili sacuvani u bazi
	//
	public function getUserDataFB()
	{
		$name = $_POST['name'];
		//$username = $_POST['username'];
		$email = $_POST['email'];
        $use_dsi = $_POST['use_dsi'];
		$account_type = $_POST['account_type'];
        
        echo $name ." ". $email ." ". $use_dsi ." ". $account_type;
		
		//$this->user_model->addUserFB($name, $username, $email, $use_dsi, $account_type);
        $this->usermodel->addUserFB($name, $email, $use_dsi, $account_type);
	}
	
	// ================================ welcome() ================================
	//
	// pozivaju je fje index(), login() i register() == usercontroller ==
	//
	// ova fja otvara Welcome stranu (WelcomeView pogled) nakon uspesne prijave korisnika na sistem
	//
	public function welcome()
	{
		$data['title']= 'Welcome | DSi A';
		$this->load->view('HeaderView',$data);
		$this->load->view('WelcomeView.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ start() ================================
	//
	// poziva se klikom na dugme u formi za pokretanje glavnog pogleda, Submit dugme == WelcomeView ==
	//
	// ova fja otvara stranu sa lekcijama, glavni pogled
	//
	public function start()
	{

		$data['mode'] = "read";
		//$data=$this->usermodel->getQuestions();
		$data['title']= 'DSi A';
		$this->load->view('HeaderView',$data);
		$this->load->view('MainView.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	
	
	public function edit()
	{
	
		$data['mode'] = "edit";
		//$data=$this->usermodel->getQuestions();
		$data['title']= 'DSi A';
		$this->load->view('HeaderView',$data);
		$this->load->view('EditView.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ getUserActions() ================================
	//
	// odogovor na ajax poziv fje sendUserActions(subject, object) == MainScript.js ==
	//
	// ova fja cita iz post promeniljive podatke poslate od klijenta i upisuje ih u bazu i to podatke:
	// koja rec na koju je prevucena, na kojoj lekciji i vreme prevlacenja
	//
	public function getUserActions()
	{
		$currentLessionNumber = $_POST['currentLessionNumber'];
		$subject = $_POST['subject'];
		$object = $_POST['object'];
		$currentDateTime = $_POST['currentDateTime'];
		
		$this->usermodel->saveUserActions($currentLessionNumber, $subject, $object, $currentDateTime);
	}
	
	// ================================ getUserActionsLessions() ================================
	//
	// odogovor na ajax poziv fje sendUserActionsLessions(currentLessionNumber, action, next_prev_lession_number) == MainScript.js ==
	//
	// ova fja cita iz post promeniljive podatke poslate od klijenta i upisuje ih u bazu i to podatke:
	// izvrsena akcija, lekcija na kojoj se korisnik trenutno nalazi, naredna lekcija na koju ce korisnik biti preusmeren, vreme akcije
	//
	public function getUserActionsLessions()
	{
		$currentLessionNumber = $_POST['currentLessionNumber'];
		$action = $_POST['action'];
		$next_prev_lession_number = $_POST['next_prev_lession_number'];
		$currentDateTime = $_POST['currentDateTime'];
		
		$this->usermodel->saveUserActionsLessions($currentLessionNumber, $action, $next_prev_lession_number, $currentDateTime);
	}
	
	
	public function getUserActionsDSiALogs()
	{

		$subject = $_POST['s'];
		$object = $_POST['o'];
		$predicate = $_POST['p'];
		$action = $_POST['action'];
		$currentDateTime = $_POST['currentDateTime'];
	
		
		$this->usermodel->saveUserActionsDSiALogs($subject, $object, $predicate, $action, $currentDateTime);
	}
	
	// ================================ startQuiz() ================================
	//
	// poziva se klikom na link u navigation divu u okviru MainView pogleda
	//
	// ova fja otvara stranu koja sledi nakon citanja lekcija, welcome strana za kviz
	//
	public function startQuiz()
	{
		$data['title']= 'Start quiz | DSi A';
		$this->load->view('HeaderView',$data);
		$this->load->view('WelcomeQuizView.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ quiz() ================================
	//
	// poziva se klikom na dugme u formi za pokretanje kviza, Submit dugme == WelcomeQuizView ==
	//
	// ova fja otvara stranu sa kvizom
	//
	public function quiz()
	{
		$data=$this->usermodel->getQuestions();
		
		//echo $data["questions"];
		//$data['title']= 'Quiz | DSi A';
		//$this->load->view('HeaderQuizView',$data);
		//$this->load->view('QuizView.php', $data);
		//$this->load->view('FooterView',$data);
	}
	
	// ================================ saveQuizResults() ================================
	//
	// odgovor na ajax poziv fje sendQuizResults() == QuizView ==
	//
	// ova fja upisuje u bazu rezultate kviza, poziva fju saveQuizResults($userAnswers) za cuvanje podataka o rezultatima == usermodel ==
	// 
	public function saveQuizResults()
	{
		$userAnswers = $_POST['userAnswers'];
		$currentDateTime = $_POST['currentDateTime'];
		$message = $this->usermodel->saveQuizResults($userAnswers);
		//$this->usermodel->saveUserActionsLessions(null, "finish_quiz", null, $currentDateTime);
		
		echo $message;
	}
	
	// ================================ QuizResultPage() ================================
	//
	// poziva je javascript fja QuizResults() na klijentu == QuizView ==
	//
	// ova fja otvara stranu sa rezultatima kviza
	// 
	public function QuizResultPage()
	{
		$data = $this->usermodel->getResults();
		
		$data['title'] = 'Quiz results | DSi A';
		$this->load->view('HeaderQuizView',$data);
		$this->load->view('QuizResultsView.php', $data);
		$this->load->view('FooterView',$data);
	}
	
	// ================================ logout() ================================
	//
	// poziva se klikom na link u navigation divu == WelcomeView, MainView, WelcomeQuizView, QuizView ==
	//
	// fja koja izloguje korisnika, upise podatke o ovoj akciji u bazu, obrise podatke iz sesije i pokrene Sign In stranu
	//
	public function logout()
	{
		// upisivanje informacije o logout-u u bazu
		$this->usermodel->saveUserActionsLessions(null, "logged_out", null, date('Y-m-d H:i:s'));
		
		$newdata = array(
		'user_id'   =>'',
		'user_name'  =>'',
		'user_email'     => '',
		'logged_in' => FALSE,
		);
		
		
		$this->session->unset_userdata($newdata);
		$this->session->sess_destroy();
		$this->index();
	}
}
?>