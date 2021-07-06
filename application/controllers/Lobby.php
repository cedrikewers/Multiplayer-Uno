<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lobby extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library("template");
	}
	//Redirect 
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


	//Update the client
	public function update($id){
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$lobbyData = fopen('./lobbys/'.$id.'.json', 'r'); 
		$data = fread($lobbyData, filesize('./lobbys/'.$id.'.json'));
		fclose($lobbyData);

		echo 'data: {$data}\n\n';
		flush();
	}


	//Functions triggerd by Client

	//create lobby
    public function create()
    {
		$id = substr(hash("md5", random_bytes(20)), 0, 6);
		if(!isset($_COOKIE['id'])){
			$userId = hash("md5", random_bytes(20));
			setcookie('id', $userId, 0, '/');
		}
		else{
			$userId = $_COOKIE['id'];
		}

		$lobby = array(
			'id' => $id, 
			'playerCount' => 1,
			'state' => 'lobby',
			'players' => array(
				'host' => array(
					'username' => $_GET['username'],
					'id' => $userId
				)
			)
		);

		$json = json_encode($lobby);

		$file = fopen('./lobbys/'.$id.'.json', "w+");
		fwrite($file, $json);
		fclose($file);

        $this->lobby($id, true);
    }
	//show lobby
	public function lobby($id, $host = false){
		if(file_exists('./lobbys/'.$id.'.json')){
			$viewData = array('id' => $id, 'host' => false);

			if($host){
				$viewData['host'] = true;
			}

			$this->template->set('title', 'Tres');
        	$this->template->load('template/template', 'lobby/lobby', $viewData);
		}
		else{
			redirect('');
		}
	}
	//join lobby
	public function join($id){
		$data = $this->getJSON($id);

		if(!isset($_COOKIE['id'])){
			$userId = hash("md5", random_bytes(20));
			setcookie('id', $userId, 0, '/');
		}
		else{
			$userId = $_COOKIE['id'];
		}

		array_push($data['players'], array('username' => $_GET['username'], 'id' => $userId));
		$data['playerCount']++;

		$this->setJSON($id, $data);

		redirect('/'.$id);
	}

	//start game
	public function start($id)	
	{
		$data = $this->getJSON($id);

		$data['state'] = 'game';

		$this->setJSON($id, $data);
	}

	

}
