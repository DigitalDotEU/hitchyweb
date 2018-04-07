<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;
use Validator;
use DB; 

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

    public function newEvent(){
        return view('events.new');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'startPlaceLattitude' => 'required|max:255',
            'startPlaceLongitude' => 'required|max:255',
            'stopPlaceLattitude' => 'required|max:255',
            'stopPlaceLongitude' => 'required|max:255',
            'startDate' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            Session::flash('Error', "You put wrong data. Please check your fields.");
            return Redirect::to('/newEvent');
        }
        
        //get info from /index add new point and send that data with post request
        $body['name'] = $request->input('name');
        $body['description'] = $request->input('description');
        $body['startPlaceLattitude'] = $request->input('startPlaceLattitude');
        $body['startPlaceLongitude'] = $request->input('startPlaceLongitude');
        $body['stopPlaceLattitude'] = $request->input('stopPlaceLattitude');
        $body['stopPlaceLongitude'] = $request->input('stopPlaceLongitude');
        $body['startDate'] = $request->input('startDate');
        $body['author'] = $request->input('author');
        $body['joinedUser'] = $request->input('joinedUser');

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'http://127.0.0.1:8080/api/events', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/events');
        }

        Session::flash('Success', "Thank you. If you added new event it must be accept by our team. Nice job!");

        return Redirect::to('/events');
    }

    public function joinEvent(Request $request){

    }
}
