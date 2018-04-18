<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('client.login');
    }

    public function store(Request $request)
    {
        //create new user
        $body['email'] = $request->input('email');
        $body['password'] = $request->input('password');

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'https://hitchyapi.herokuapp.com/api/login', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)
            ]);
        }catch (\Exception $e) {
            Session::flash('Error', $e->getMessage());
            return Redirect::to('/index');
        }

        $token = json_decode($response->getBody());

        //dd($token);

        //save in session logged user email to user in created new point e.g. created by radoszszymon@gmail.com
        $loggedInUser = $token->email;
        $userID = $token->userID;
        $token = $token->success->token;

        Session::put('token', $token);
        Session::put('userID', $userID);
        Session::put('loggedInUser', $loggedInUser);

        //dd($token);

        Session::flash('Success', "You sign in successfully.");

        return Redirect::to('/index');
    }

    public function logout()
    {
        Session::forget('token');
        Session::forget('userID');
        Session::forget('loggedInUser');
        Session::flash('Success', "You log out successfully.");

        return Redirect::to('/index');
    }
}
