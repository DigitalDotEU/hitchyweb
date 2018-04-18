<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class AdminController extends Controller
{
    public function index()
    {
        //list of all points
        $client = new Client();
        $res = $client->request('GET', 'https://hitchyapi.herokuapp.com/api/points');
        $res = json_decode($res->getBody(), true);

        //dd($res);

        return view('admin.index')->with('res', $res);
    }

    public function submitPoint($id)
    {
        //dd($id);

        //post request to api sending point id to increment(submit) that point in api
        $client = new Client();
        $client->request('POST', 'https://hitchyapi.herokuapp.com/api/adminSubmit/' . $id);

        //return again points list
        $client2 = new Client();
        $res = $client2->request('GET', 'https://hitchyapi.herokuapp.com/api/points');
        $res = json_decode($res->getBody(), true);

        Session::flash('Success', "You sumbitted point wth id " . $id);
        return view('admin.index')->with('res', $res);
    }
}
