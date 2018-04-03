<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;

class EventController extends Controller
{
    public function index(){
        
        //get all events which author==joinedUser
        $client = new Client();
        $res = $client->request('GET', 'http://127.0.0.1:8080/api/events');
        $res = json_decode($res->getBody(), true);

        //get all events
        $client2 = new Client();
        $res2 = $client2->request('GET', 'http://127.0.0.1:8080/api/allEvents');
        $res2 = json_decode($res2->getBody(), true);

        //dd($res2);
        $joinedUsers = array();

        //find joined users to specific event name and return key value pairs test[0]=>[0]eventName, [1]user
        foreach($res2 as $singleEvent){
            foreach($res as $singleUniqueEvent){
                if($singleUniqueEvent['name'] == $singleEvent['name'] && $singleUniqueEvent['joinedUser'] != $singleEvent['joinedUser']){
                    array_push($joinedUsers, array($singleUniqueEvent['name'], $singleEvent['joinedUser']));
                }
            }
        }

        //dd($joinedUsers);

        return view('events.index')->with(['res' => $res, 'joinedUsers' => $joinedUsers]);
    }
}
