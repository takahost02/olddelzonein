<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Lib\PusherFactory;
use App\Model\Message;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index() {
		$users = User::whereNotIn('user_type', ['C'])->where('id', '!=', Auth::user()->id)->get();
		return view('chat.home', compact('users'));
	}
	public function getLoadLatestMessages(Request $request) {
		if (!$request->user_id) {
			return;
		}
		$messages = Message::where(function ($query) use ($request) {
			$query->where('from_user', Auth::user()->id)->where('to_user', $request->user_id);
		})->orWhere(function ($query) use ($request) {
			$query->where('from_user', $request->user_id)->where('to_user', Auth::user()->id);
		})->orderBy('created_at', 'ASC')
			->with('fromUser.metas')
			->get();
		$return = [];
		foreach ($messages as $message) {
			$return[] = view('chat.message-line')->with('message', $message)->render();
		}
		return response()->json(['state' => 1, 'messages' => $return]);
	}
	public function postSendMessage(Request $request) {
		if (!$request->to_user || !$request->message) {
			return;
		}
		$message = new Message();
		$message->from_user = Auth::user()->id;
		$message->to_user = $request->to_user;
		$message->content = $request->message;
		$message->save();
		// prepare some data to send with the response
		$message->dateTimeStr = date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString()));
		$message->dateHumanReadable = $message->created_at->diffForHumans();
		$message->fromUserName = $message->fromUser->name;
		$message->from_user_id = Auth::user()->id;
		$message->toUserName = $message->toUser->name;
		$message->to_user_id = $request->to_user;
		PusherFactory::make()->trigger('chat', 'send', ['data' => $message]);
		return response()->json(['state' => 1, 'data' => $message]);
	}
	public function getOldMessages(Request $request) {
		if (!$request->old_message_id || !$request->to_user) {
			return;
		}
		$message = Message::find($request->old_message_id);
		$lastMessages = Message::where(function ($query) use ($request, $message) {
			$query->where('from_user', Auth::user()->id)
				->where('to_user', $request->to_user)
				->where('created_at', '<', $message->created_at);
		})
			->orWhere(function ($query) use ($request, $message) {
				$query->where('from_user', $request->to_user)
					->where('to_user', Auth::user()->id)
					->where('created_at', '<', $message->created_at);
			})
			->orderBy('created_at', 'ASC')->limit(10)
			->with('metas')
			->get();
		$return = [];
		if ($lastMessages->count() > 0) {
			foreach ($lastMessages as $message) {
				$return[] = view('chat.message-line')->with('message', $message)->render();
			}
			PusherFactory::make()->trigger('chat', 'oldMsgs', ['to_user' => $request->to_user, 'data' => $return]);
		}
		return response()->json(['state' => 1, 'data' => $return]);
	}
}
