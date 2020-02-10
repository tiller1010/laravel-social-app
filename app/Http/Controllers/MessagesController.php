<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::where('id', auth()->id())->get()->first();
        $user = Auth::User();

        $sentMessages = Message::where('From_user_id', $user->id)->get();
        $recievedMessages = Message::where('To_user_id', $user->id)->get();
        return view('Messages.index')
            ->with(compact('sentMessages'))
            ->with(compact('recievedMessages'))
            ->with(compact('user'));
        // return view('message', ['From' => 'Tyler', 'Message' => 'Wowowoww']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Messages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = request()->validate([
            'To' => ['required'],
            'Message' => ['required']
        ]);
        // dd(User::where('id', 1)->get()->first()->Name);
        // dd(Message::all()->first()->Message);
        $FromUser = Auth::User();
        // dd(request('To'));
        // dd(User::where('name', request('To'))->get()->first());
        $ToUser = User::where('name', request('To'))->get()->first();

        if($ToUser){
            Message::create($message + [
                'From' => $FromUser->name,
                'From_user_id' => $FromUser->id,
                'To_user_id' => $ToUser->id,
                'Read' => false
            ]);

            return redirect('/messages');
        } else {
            return Redirect::back()->withErrors([]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        return view('Messages.edit', ['message' => $message]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Message $message)
    {
        if(request('read') == 1){
            $message->read = 1;
        } else {
            $message->read = 0;
        }
        $message->save();
        return redirect('/messages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
