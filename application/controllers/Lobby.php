<?php
defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Lobby extends CI_Controller
{

    public function __construct()
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
        $redis = new Redis();
        $redis->connect('redis');
        return json_decode($redis->get($id), true);
    }

    public function setJSON($id, $data)
    {
        $redis = new Redis();
        $redis->connect('redis');
        $redis->set($id, json_encode($data));
    }

    //Update the client
    public function update($id)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $data = $this->getJSON($id);

        unset($data['talon']);

        $data = json_encode($data);
        echo "data: {$data}\n\n";
        flush();
    }

    //Functions trigged by Client

    //create lobby
    public function create()
    {
        $id = substr(hash("md5", random_bytes(20)), 0, 6);
        if (! isset($_COOKIE['id'])) {
            $userId = hash("md5", random_bytes(20));
            setcookie('id', $userId, 0, '/');
        } else {
            $userId = $_COOKIE['id'];
        }

        $talon = $this->newTalon();

        $oTalon = [array_pop($talon)];

        $newHand = [];
        for ($i = 0; $i < 5; $i++) {
            array_push($newHand, array_pop($talon));
        }

        $lobby = [
            'id'          => $id,
            'playerCount' => 1,
            'state'       => 'lobby',
            'turn'        => 0,
            'round'       => 1,
            'clockwise'   => true,
            'winner'      => null,
            'cardDictate' => ['order' => null, 'origin' => null, 'executed' => null], //i.e. +4/+2 (draw cards); syntax: [order => ""+2/+4", origin => player, round => round]
            'players'     => [
                'host' => [
                    'username' => $_GET['username'],
                    'id'       => $userId,
                    'hand'     => $newHand,
                ],
            ],
            'talon'       => $talon,
            'oTalon'      => $oTalon,
        ];

        $this->setJSON($id, $lobby);
        $this->lobby($id, true);
    }
    //show lobby
    public function lobby($id, $host = false)
    {
        $redis = new Redis();
        $redis->connect('redis');
        if ($redis->exists($id)) {
            $viewData = ['id' => $id, 'host' => false];

            if ($host) {
                $viewData['host'] = true;
            }

            $this->template->set('title', 'Tres');
            $this->template->load('template/template', 'lobby/lobby', $viewData);
        } else {
            redirect('');
        }
    }
    //join lobby
    public function join($id)
    {
        $data = $this->getJSON($id);

        if (! isset($_COOKIE['id'])) {
            $userId = hash("md5", random_bytes(20));
            setcookie('id', $userId, 0, '/');
        } else {
            $userId = $_COOKIE['id'];
        }

        $newHand = [];
        for ($i = 0; $i < 5; $i++) {
            array_push($newHand, array_pop($data['talon']));
        }

        array_push($data['players'], [
            'username' => $_GET['username'],
            'id'       => $userId,
            'hand'     => $newHand,
        ]);
        $data['playerCount']++;

        $this->setJSON($id, $data);

        redirect('/' . $id);
    }

    //start game
    public function start($id)
    {
        $data = $this->getJSON($id);

        $data['state'] = 'game';

        $this->setJSON($id, $data);
    }

    public function newTalon()
    {
        $colors = ['r' => 0, 'g' => 2, 'b' => 3, 'y' => 1];

        $talon = [];

        $id = 1;

        for ($i = 0; $i < 4; $i++) {
            array_push($talon, ['name' => 'nplus4', 'x' => 13, 'y' => 4, 'id' => $id]);
            $id++;
            array_push($talon, ['name' => 'nc', 'x' => 13, 'y' => 0, 'id' => $id]);
            $id++;
        }
        foreach ($colors as $c => $y) {
            for ($i = 0; $i < 10; $i++) {
                array_push($talon, ['name' => $c . $i, 'x' => $i, 'y' => $y, 'id' => $id]);
                $id++;
            }
            for ($i = 1; $i < 10; $i++) {
                array_push($talon, ['name' => $c . $i, 'x' => $i, 'y' => $y, 'id' => $id]);
                $id++;
            }
            for ($i = 0; $i < 2; $i++) {
                array_push($talon, ['name' => $c . "plus2", 'x' => 12, 'y' => $y, 'id' => $id]);
                $id++;
                array_push($talon, ['name' => $c . "a", 'x' => 10, 'y' => $y, 'id' => $id]);
                $id++;
                array_push($talon, ['name' => $c . "r", 'x' => 11, 'y' => $y, 'id' => $id]);
                $id++;
            }
        }

        shuffle($talon);

        return $talon;
    }
}
