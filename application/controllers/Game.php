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
				if(isset($_POST['color'])){//if +4 or c => color gets updated
					$card['name'] = $_POST['color'].substr($card['name'], 1);
				}

				switch(substr($card['name'], 1)){
					case 'plus4':
						$lobbyData['cardDictate'] = array('order' => 'plus4', 'origin' =>$userName, 'executed' => false);
						break;
					case 'plus2':
						$lobbyData['cardDictate'] = array('order' => 'plus2', 'origin' =>$userName,'executed' => false);
						break;
					case "r":
						$lobbyData['clockwise'] = !$lobbyData['clockwise'];
						break;
					case "a":
						if($lobbyData['clockwise'])$lobbyData['turn']++;
						else $lobbyData['turn']--;
						break;
				}	

				array_push($lobbyData['oTalon'], $card);
				unset($lobbyData['players'][$userName]['hand'][$number]);
			}
		}
		$lobbyData['players'][$userName]['hand'] = array_values($lobbyData['players'][$userName]['hand']);

		if($lobbyData['clockwise'])$lobbyData['turn'] = ($lobbyData['turn']+1) % $lobbyData['playerCount'];
		else $lobbyData['turn'] = ($lobbyData['turn']+($lobbyData['playerCount']-1)) % $lobbyData['playerCount'];
		$lobbyData['round']++;

		$this->setJSON($id, $lobbyData);

	}

	public function drawCard($id, $userName, $endTurn = 1)
	{
		$lobbyData = $this->getJSON($id);

		$card = array_pop($lobbyData['talon']);

		array_push($lobbyData['players'][$userName]['hand'], $card);

		if(count($lobbyData['talon']) < 2){
			$lobbyData['talon'] = array_merge($lobbyData['talon'], $lobbyData['oTalon']);
			unset($lobbyData['oTalon']);
			shuffle($lobbyData['talon']);
		}

		$cardCanBePlayed = false;
		if($endTurn == 1){
			$topCard = end($lobbyData['oTalon']);
			if(substr($topCard['name'], 0, 1) == substr($card['name'], 0, 1) or substr($topCard['name'], 1) == substr($card['name'], 1) or substr($card['name'],0 , 1) == "n"){
				$cardCanBePlayed = true;
			}

			if($cardCanBePlayed == false){
				if($lobbyData['clockwise'])$lobbyData['turn'] = ($lobbyData['turn']+1) % $lobbyData['playerCount'];
				else $lobbyData['turn'] = ($lobbyData['turn']+($lobbyData['playerCount']-1)) % $lobbyData['playerCount'];
				$lobbyData['round']++;
			}
		}

		$this->setJSON($id, $lobbyData);

		$sendData = array('card' => $card, 'playable' => $cardCanBePlayed);

		echo json_encode($sendData);
	}

	public function orderExecuted($id){
		$lobbyData = $this->getJSON($id);

		$lobbyData['cardDictate']['executed'] = true;

		$this->setJSON($id, $lobbyData);
	}

	public function endTurn($id){
		$lobbyData = $this->getJSON($id);

		if($lobbyData['clockwise'])$lobbyData['turn'] = ($lobbyData['turn']+1) % $lobbyData['playerCount'];
		else $lobbyData['turn'] = ($lobbyData['turn']+($lobbyData['playerCount']-1)) % $lobbyData['playerCount'];
		$lobbyData['round']++;

		$this->setJSON($id, $lobbyData);

	}


	
}
