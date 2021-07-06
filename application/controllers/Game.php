<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library("template");
	}

	public function index()
	{
		redirect();
	}

    //Helper Methods
	public function getJSON($id)
	{
		$lobbyData = fopen('./lobbys/'.$id.'.json', 'r+'); 
		$dataJSON = fread($lobbyData, filesize('./lobbys/'.$id.'.json'));
		fclose($lobbyData);
		return json_decode($dataJSON, TRUE);
		
	}

	public function setJSON($id, $data)
	{
		$lobbyData = fopen('./lobbys/'.$id.'.json', 'w'); 
		fwrite($lobbyData, json_encode($data));
		fclose($lobbyData);
	}

    public function main($id)
    {
        if(file_exists('./lobbys/'.$id.'.json')){
            $this->template->set('title', 'Tres');
        	$this->template->load('template/template', 'game/game');
        }
        else{
            redirect();
        }
    }

	
}
