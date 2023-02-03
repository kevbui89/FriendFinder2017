<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays the friend with his status
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
        foreach ($friendStatus as $friend)
            $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();

        $searchNames = $this->searchFriends($request);

        if(isset($friendNames) && count($friendNames) > 0 && isset($friendStatus) && count($friendStatus) > 0)
            return view('manageFriends', ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'searchNames' => $searchNames]);
        return view('manageFriends',['searchNames' => $searchNames]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchSaveUpdateFriends(Request $request)
    {
        if ($request->get('submitFriendSearch'))
        {
            $searchNames = $this->searchFriends($request);

            $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
            foreach ($friendStatus as $friend)
                $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();

            if(isset($friendNames) && count($friendNames) > 0 && isset($friendStatus) && count($friendStatus) > 0)
                return view('manageFriends', ['searchNames' => $searchNames, 'friendNames' => $friendNames,
                    'friendStatus' => $friendStatus]);
            return view('manageFriends', ['searchNames' => $searchNames]);
        }

        else if ($request->get('addFriendBtn'))
        {
            $this->saveFriend($request);

            $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
            foreach ($friendStatus as $friend)
                $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();

            return view('manageFriends', ['friendNames' => $friendNames, 'friendStatus' => $friendStatus]);
        }

        else if ($request->get('acceptRequest'))
        {
            $this->acceptFriendRequest($request);

            $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
            foreach ($friendStatus as $friend)
                $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();

            if(isset($friendNames) && count($friendNames) > 0 && isset($friendStatus) && count($friendStatus) > 0)
                return view('manageFriends', ['friendNames' => $friendNames, 'friendStatus' => $friendStatus]);
            return view('manageFriends');
        }
        else if ($request->get('declineRequest'))
        {
            $this->declineFriendRequest($request);

            $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
            foreach ($friendStatus as $friend)
                $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();

            if(isset($friendNames) && count($friendNames) > 0 && isset($friendStatus) && count($friendStatus) > 0)
                return view('manageFriends', ['friendNames' => $friendNames, 'friendStatus' => $friendStatus]);
            return view('manageFriends');
        }
    }

    /**
     * Searches for friends
     * @param Request $request
     * @return mixed
     */
    private function searchFriends(Request $request)
    {
        $searchNames = User::where(('name'), 'ilike', '%' . $request->get('name') . '%')->
            where('name', '!=', Auth::user()->name)->
            whereNotIn('email', function($q){ $q->select('friendEmail')->from('friends')->
            where('email', '=', Auth::user()->email); })->paginate(10);

        return $searchNames;
    }

    /**
     * Sends a friend request and displays a received message
     * @param Request $request
     */
    private function saveFriend(Request $request)
    {
        $checkExists = Friend::where('email', '=', Auth::user()->email)->
            where('friendEmail', '=', $request->get('addFriendBtn'))->get();

        if (count($checkExists) <= 0)
        {
            $friend = new Friend();
            $friend->email = Auth::user()->email;
            $friend->user_id = Auth::user()->id;
            $friend->status = 'Request Sent';
            $friend->friendEmail = $request->get('addFriendBtn');
            $friend->save();

            $friend = new Friend();
            $friend->email = $request->get('addFriendBtn');
            $friend->user_id = Auth::user()->id;
            $friend->status = 'Request Received';
            $friend->friendEmail = Auth::user()->email;
            $friend->save();
        }
    }

    /**
     * Adds a friend to the database
     * @param Request $request
     */
    private function acceptFriendRequest(Request $request)
    {
        Friend::where('email', '=', Auth::user()->email)->
        where('friendEmail', '=', $request->get('acceptRequest'))->update(['status' => 'Confirmed']);

        Friend::where('email', '=', $request->get('acceptRequest'))->
        where('friendEmail', '=', Auth::user()->email)->update(['status' => 'Confirmed']);
    }

    /**
     * Removes a friend request from the database
     * @param Request $request
     */
    private function declineFriendRequest(Request $request)
    {
        Friend::where('email', '=', Auth::user()->email)->
            where('friendEmail', '=', $request->get('declineRequest'))->delete();

        Friend::where('email', '=', $request->get('declineRequest'))->
            where('friendEmail', '=', Auth::user()->email)->delete();
    }
}
