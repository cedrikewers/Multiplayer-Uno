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
		echo "data: {$JSONdata}\n\n";
		flush();
	}


	
}
