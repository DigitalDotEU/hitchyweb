<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class AdminEventController extends Controller
{
    public function index()
    {
        //list of all events
        $client = new Client();
        $res = $client->request('GET', 'http://127.0.0.1:8080/api/events');
        $res = json_decode($res->getBody(), true);

        //dd($res);

        return view('admin.events')->with('res', $res);
    }

    public function submitEvent($id)
    {
        //dd($id);

        //post request to api sending event id to increment(submit) that event in api
        $client = new Client();
        $client->request('POST', 'http://127.0.0.1:8080/api/adminEventSubmit/' . $id);

        //return again events list
        $client2 = new Client();
        $res = $client2->request('GET', 'http://127.0.0.1:8080/api/events');
        $res = json_decode($res->getBody(), true);

        Session::flash('Success', "You sumbitted event wth id " . $id);
        return view('admin.events')->with('res', $res);
    }
}
