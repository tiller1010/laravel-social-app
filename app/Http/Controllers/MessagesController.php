<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::User();

        if($user){
            $sentMessages = Message::where('From_user_id', $user->id)->get()->sortBy('created_at')->reverse();
            // Get most recent and unique to_user messages
            $uniqueSentToUserMessages = new Collection;
            foreach($sentMessages as $m){
                if(!$uniqueSentToUserMessages->pluck('To_user_id')->contains($m->To_user_id)){
                    $uniqueSentToUserMessages->push($m);
                }
            }
            // dd($uniqueSentToUserMessages->pluck('Message'));

            $recievedMessages = Message::where('To_user_id', $user->id)->get()->sortBy('created_at')->reverse();
            // Get most recent and unique from_user messages
            $uniqueReceivedFromUserMessages = new Collection;
            foreach($recievedMessages as $m){
                if(!$uniqueReceivedFromUserMessages->pluck('From_user_id')->contains($m->From_user_id)){
                    $uniqueReceivedFromUserMessages->push($m);
                }
            }
            // dd($uniqueReceivedFromUserMessages->pluck('Message'));

            // Add messages where connectedUsers created_at is newer
            $mostRecentConversationMessages = new Collection;
            $allRecents = $uniqueSentToUserMessages
                ->merge($uniqueReceivedFromUserMessages)
                ->sortBy('created_at')
                ->reverse();
            // dd($allRecents->pluck('Message'));
            foreach($allRecents as $m){
                if(!$mostRecentConversationMessages->pluck('From_user_id')->contains($m->To_user_id) ||
                   !$mostRecentConversationMessages->pluck('To_user_id')->contains($m->From_user_id)
                ){
                    $mostRecentConversationMessages->push($m);
                }
            }
            // dd($mostRecentConversationMessages->pluck('Message'));

            return view('Messages.index')
                ->with(compact('sentMessages'))
                ->with(compact('recievedMessages'))
                ->with(compact('mostRecentConversationMessages'))
                ->with(compact('user'));
        }

        return redirect('login');
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
    public function show(Request $request)
    {
        $user = Auth::User();

        if($user){
            $connectedUser = $request->connectedUser;
            $sentMessages = Message::where('From_user_id', $user->id)
                ->where('To_user_id', $connectedUser)
                ->get();
            $recievedMessages = Message::where('To_user_id', $user->id)
                ->where('From_user_id', $connectedUser)
                ->get();
            $allMessages = $sentMessages->merge($recievedMessages)->sortBy('created_at');
            return view('Messages.show')
                ->with(compact('sentMessages'))
                ->with(compact('recievedMessages'))
                ->with(compact('allMessages'))
                ->with(compact('connectedUser'))
                ->with(compact('user'));
        }

        return redirect('login');
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
