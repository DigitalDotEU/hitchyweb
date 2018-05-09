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
        $address = $this->apiAddress . '/api/points';
        $res = $client->request('GET', $address);
        $res = json_decode($res->getBody(), true);

        //dd($res);

        return view('admin.index')->with('res', $res);
    }

    public function submitPoint($id)
    {
        //dd($id);

        //post request to api sending point id to increment(submit) that point in api
        $client = new Client();
        $address = $this->apiAddress . '/api/adminSubmit/';
        $client->request('POST', $address . $id);

        //return again points list
        $client2 = new Client();
        $address2 = $this->apiAddress . '/api/points';
        $res = $client2->request('GET', $address2);
        $res = json_decode($res->getBody(), true);

        Session::flash('Success', "You sumbitted point wth id " . $id);
        return view('admin.index')->with('res', $res);
    }
}
