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
        	$this->template->load('template/template', 'game/game', array('id' => $id));
        }
        else{
            redirect();
        }
    }

	public function update($id, $userId){
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$lobbyData = fopen('./lobbys/'.$id.'.json', 'r'); 
		$data = json_decode(fread($lobbyData, filesize('./lobbys/'.$id.'.json')), true);
		fclose($lobbyData);

		foreach($data['players'] as $name => $player){
			if($player['id'] == $userId){//nur beim Spieler
				$data['client']['username'] = $player['username'];
				$data['client']['number'] = $name;
			}
		}

		unset($data['talon']);
		foreach($data['players'] as $playerName => $val){
			unset($data['players'][$playerName]['id']);
		}

		$JSONdata = json_encode($data);
		echo "retry: 1000\n";
		echo "data: {$JSONdata}\n\n";
		flush();
	}

	public function playCard($id, $userName)
	{
		$lobbyData = $this->getJSON($id);

		foreach($lobbyData['players'][$userName]['hand'] as $number => $card){
			if($card['id'] == $_POST['id']){			
				array_push($lobbyData['oTalon'], $card);
				unset($lobbyData['players'][$userName]['hand'][$number]);
			}
		}
		$lobbyData['players'][$userName]['hand'] = array_values($lobbyData['players'][$userName]['hand']);

		$this->setJSON($id, $lobbyData);

	}

	public function drawCard($id, $userName)
	{
		$lobbyData = $this->getJSON($id);

		$card = array_pop($lobbyData['talon']);

		array_push($lobbyData['players'][$userName]['hand'], $card);

		if(count($lobbyData['talon']) < 2){
			$lobbyData['talon'] = array_merge($lobbyData['talon'], $lobbyData['oTalon']);
			unset($lobbyData['oTalon']);
			shuffle($lobbyData['talon']);
		}

		$this->setJSON($id, $lobbyData);

		echo json_encode($card);
	}


	
}
