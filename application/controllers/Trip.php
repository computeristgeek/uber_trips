<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Trip extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation','database']);
		$this->load->helper(['url', 'language']);
        $this->load->model(['trip_model']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
        
        //Redirect if needed, otherwise display the user list
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
	}

	public function set_trip()
	{
        //if the last trip was in last month/year, reset first
        $this->trip_model->reset_counts();
        echo json_encode($this->trip_model->count_trip());
	}

	public function get_monopoly()
	{
        //if the last trip was in last month/year, reset
        $this->trip_model->reset_counts();
        header("Cache-Control: public, max-age=3600, s-maxage=3600");
        echo json_encode($this->trip_model->get_monopoly());

	}

}
