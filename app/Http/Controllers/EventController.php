<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;
use Mapper;
use Validator;
use DB; 

class EventController extends Controller
{
    public function index(){
        //get all events which author==joinedUser
        $client = new Client();
        $res = $client->request('GET', 'https://hitchyapi.herokuapp.com/api/events');
        $res = json_decode($res->getBody(), true);

        //get all events
        $client2 = new Client();
        $res2 = $client2->request('GET', 'https://hitchyapi.herokuapp.com/api/allEvents');
        $res2 = json_decode($res2->getBody(), true);

        $joinedUsers = array();

        //find joined users to specific event name and return key value pairs test[0]=>[0]eventName, [1]user
        foreach($res2 as $singleEvent){
            foreach($res as $singleUniqueEvent){
                if($singleUniqueEvent['name'] == $singleEvent['name'] && $singleUniqueEvent['joinedUser'] != $singleEvent['joinedUser']){
                    array_push($joinedUsers, array($singleUniqueEvent['name'], $singleEvent['joinedUser']));
                }
            }
        }

        return view('events.index')->with(['res' => $res, 'joinedUsers' => $joinedUsers]);
    }

    public function newEvent(){
        Mapper::map(52.22977, 21.01178,
            [
                'eventAfterLoad' => '',
                'eventBeforeLoad' => 'newEvent(map);',
                'zoom' => 5,
                'marker' => false
            ]
        );

        return view('events.new');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'startPlaceLattitude' => 'required|numeric|between:0,99.99',
            'startPlaceLongitude' => 'required|numeric|between:0,99.99',
            'stopPlaceLattitude' => 'required|numeric|between:0,99.99',
            'stopPlaceLongitude' => 'required|numeric|between:0,99.99',
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
            $response = $client->request('POST', 'https://hitchyapi.herokuapp.com/api/events', [
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

    public function eventCommentStore(Request $request){
        $validator = Validator::make($request->all(), [
            'body' => 'required|max:255',
            'user_id' => 'required|max:255',
            'user_email' => 'required|max:255',
            'event_id' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            Session::flash('Error', "You put wrong data. Please check your fields.");
            return Redirect::to('/newEvent');
        }
        
        //get info from /index add new point and send that data with post request
        $body['commentBody'] = $request->input('body');
        $body['user_id'] = $request->input('user_id');
        $body['user_email'] = $request->input('user_email');
        $body['event_id'] = $request->input('event_id');
       
        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'https://hitchyapi.herokuapp.com/api/eventComments', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/events');
        }

        Session::flash('Success', "Thank you for comment.");
        return Redirect::back();
    }

    public function show($id){
        //get event with $id = id
        $client = new Client();
        $res = $client->request('GET', 'https://hitchyapi.herokuapp.com/api/events/' . $id);
        $res = json_decode($res->getBody(), true);

        //get all events
        $client2 = new Client();
        $res2 = $client2->request('GET', 'https://hitchyapi.herokuapp.com/api/allEvents');
        $res2 = json_decode($res2->getBody(), true);

        //get comments for event
        $client3 = new Client();
        $res3 = $client3->request('GET', 'https://hitchyapi.herokuapp.com/api/eventComments/' . $id);
        $res3 = json_decode($res3->getBody(), true);
        
        $joinedUsers = array();

        //find joined users to specific event name and return key value pairs test[0]=>[0]eventName, [1]user
        foreach($res2 as $singleEvent){
            if($res['name'] == $singleEvent['name'] && $res['joinedUser'] != $singleEvent['joinedUser']){
                array_push($joinedUsers, array($res['name'], $singleEvent['joinedUser']));
            }
        }

        Mapper::map($res['startPlaceLattitude'], $res['startPlaceLongitude'],
            [
                'eventAfterLoad' => '',
                'eventBeforeLoad' => 'eventPoint(map);',
                'zoom' => 8
            ]
        );

        return view('events.single')->with(['res' => $res, 'joinedUsers' => $joinedUsers, 'res3' => $res3]);
    }
}
