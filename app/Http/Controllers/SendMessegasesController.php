<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;

class SendMessegasesController extends Controller
{

    public function getIndex()
    {
        return view('send_messages_form');
    }

    public function senMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $subscribers = Subscriber::all();

        if(count($subscribers) > 0)
        {
            $botman = app('botman');

            foreach ($subscribers as $subscriber)
            {
                $botman->say($request['message'], $subscriber->recipient_id);
            }

            return response()->json(['error' => 'false', 'message' => 'Message sent']);
        }
        else
        {
            return response()->json(['error' => 'true', 'message' => 'You do not have any followers']);
        }

    }
}
