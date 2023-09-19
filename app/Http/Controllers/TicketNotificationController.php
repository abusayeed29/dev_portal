<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TicketNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function newTicket(){
        

        $ticket  = new Ticket();
        $ticket->user_id       = Auth::user()->id;;
        $ticket->company_id    = '100';
        $ticket->supporter_uid = Auth::id();
        $ticket->description   = 'This is ticket from auto crate for testing';
        $ticket->save();

        //$user = User::where('id', '!=',Auth::user()->id)->get();
        $user = User::where('id', '=',Auth::user()->reportto_id)->get();
        
        if(Notification::send($user, new NewTicketNotification(Ticket::latest('id')->first() )))
        {
            return back();
        }
    }


    public function ticketNotification(){
        return auth()->user()->unreadNotifications->take(10);
    }


    public function markAsRead(Request $request){

        auth()->user()->unreadNotifications->find($request->not_id)->markAsRead();

    }

    public function readTicket($ticket_id){
        //$ticket = Ticket::find([$ticket_id]);
        $ticket = Ticket::find($ticket_id);
        return view('notification.single', compact('ticket'));
    }


    public function allMarkAsRead(){
       auth()->user()->unreadNotifications->markAsRead();
    }

    public function readAllTicket(){

        $tickets = auth()->user()->readNotifications;

        return view('notification.all', compact('tickets'));

    }


}
