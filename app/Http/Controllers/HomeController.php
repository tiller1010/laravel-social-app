<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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

            $recievedMessages = Message::where('To_user_id', $user->id)->get()->sortBy('created_at')->reverse();
            // Get most recent and unique from_user messages
            $uniqueReceivedFromUserMessages = new Collection;
            foreach($recievedMessages as $m){
                if(!$uniqueReceivedFromUserMessages->pluck('From_user_id')->contains($m->From_user_id)){
                    $uniqueReceivedFromUserMessages->push($m);
                }
            }

            // Add messages where connectedUsers created_at is newer
            $mostRecentConversationMessages = new Collection;
            $allRecents = $uniqueSentToUserMessages
                ->merge($uniqueReceivedFromUserMessages)
                ->sortBy('created_at')
                ->reverse();
            foreach($allRecents as $m){
                if(!$mostRecentConversationMessages->pluck('From_user_id')->contains($m->To_user_id) ||
                   !$mostRecentConversationMessages->pluck('To_user_id')->contains($m->From_user_id)
                ){
                    $mostRecentConversationMessages->push($m);
                }
            }

            return view('Messages.index')
                ->with(compact('sentMessages'))
                ->with(compact('recievedMessages'))
                ->with(compact('mostRecentConversationMessages'))
                ->with(compact('user'));
        }

        return redirect('login');
    }
}
