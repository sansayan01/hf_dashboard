<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessengerController extends Controller
{
    /**
     * Display the list of conversations.
     */
    public function index()
    {
        $user = Auth::user();

        // Get conversations sorted by last message
        $conversations = $user->conversations()
            ->with([
                'users' => function ($q) use ($user) {
                    $q->where('users.id', '!=', $user->id);
                },
                'lastMessage'
            ])
            ->orderByDesc('last_message_at')
            ->get();

        return view('messenger.index', compact('conversations'));
    }

    /**
     * Display a specific conversation.
     * Note: This will be loaded via AJAX/fetch for the premium feel, but we need a route for the initial load too.
     */
    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $conversation->load(['users', 'messages.sender']);

        // Mark as read
        $user = Auth::user();
        $conversation->users()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);

        // Mark all messages as read (optional, or just use the pivot timestamp)
        // Better to use the pivot timestamp to calculate unread counts dynamically.

        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages,
            'other_user' => $conversation->users->where('id', '!=', $user->id)->first(),
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => $message->load('sender'),
            'status' => 'success'
        ]);
    }

    /**
     * Start a conversation with a specific user (or find existing).
     */
    public function start(User $user)
    {
        $myself = Auth::user();

        if ($myself->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot message yourself.');
        }

        // Check for existing direct conversation
        // This query finds a conversation where both users are participants and it's a 'direct' type
        $existingConversation = Conversation::where('type', 'direct')
            ->whereHas('users', function ($q) use ($myself) {
                $q->where('users.id', $myself->id);
            })
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->first();

        if ($existingConversation) {
            return redirect()->route('messenger.index', ['conversation' => $existingConversation->id]);
        }

        // Create new
        DB::transaction(function () use ($myself, $user, &$existingConversation) {
            $existingConversation = Conversation::create([
                'type' => 'direct',
                'last_message_at' => now(),
            ]);

            $existingConversation->users()->attach([$myself->id, $user->id]);
        });

        return redirect()->route('messenger.index', ['conversation' => $existingConversation->id]);
    }

    private function authorizeConversation(Conversation $conversation)
    {
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'Unauthorized');
        }
    }
}
