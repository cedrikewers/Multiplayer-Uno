<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library("template");
	}

	public function index()
	{
		$this->template->set('title', "Tres");
		$this->template->load('template/template', 'homepage/homepage');
	}

	
}
