<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('login_model');
		$this->client_logon = $this->session->userdata('logged');
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function index()
	{
	$this->template->set('title', 'SIMAKDA');
	if ($this->client_logon){
        $user              = $this->session->userdata('pcUser');
		$otoriname         = $this->session->userdata('pcOtoriName');
		$logintime         = $this->session->userdata('pcLoginTime');
		//$userlevel	  = $$this->session->userdata('pcUserlevel');
		$thn_ang	   = $this->session->userdata('pcThang');	
		//$jaka = $this->session->userdata('pcJaka');
       
		//$this->template->set('title', 'SIMAKDA');
	
		$this->template->load('template', 'home',$otoriname);
	       
        }
	 else
        {
	//$this->welcome->login();
           //redirect('login');
	   redirect('/welcome/login');
	}
	}
	
	Public function login()
	{
	
		if($_POST)
		{
		
		$user = $this->login_model->validate($_POST['username'], $_POST['password'],$_POST['pcthang']);
 
			if($user == TRUE)
			{
			
				   redirect('welcome');;
			}
			else
			{
			$this->template->set('title', 'SIMAKDA');
			
			$data['pesan'] = 'Username atau password salah!';
			$this->template->load('template_login', 'login',$data);
			}
 
		}
		else
		{
		
		$this->template->set('title', 'SIMAKDA');
		$this->template->load('template_login', 'login');
		}
	}
 
	 Public function logout()
	{
		$this->login_model->logout();
		   redirect('/welcome/login');;
	}
 

	public function ceklogin(){
		$user=$this->session->userdata('pcNama');
		if ($user==''){
			echo '1';
		}else{
			echo '0';
		}
	}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */