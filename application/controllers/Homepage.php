<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library("Template");
	}

	public function index()
	{
		$this->Template->set('title', "Tres");
		$this->Template->load('template/template', 'homepage/homepage');
	}

	
}
