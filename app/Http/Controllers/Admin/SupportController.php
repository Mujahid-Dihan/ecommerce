<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');
        if ($request->filled('status')) { $query->where('status', $request->status); }
        $tickets = $query->latest()->paginate(20)->withQueryString();
        return Inertia::render('Admin/Support/Index', ['tickets' => $tickets, 'filters' => $request->only(['status'])]);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'replies.user']);
        return Inertia::render('Admin/Support/Show', ['ticket' => $ticket]);
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate(['message' => 'required|string']);
        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        return redirect()->back()->with('success', 'Reply sent.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate(['status' => 'required|in:open,in_progress,closed']);
        $ticket->update(['status' => $validated['status']]);
        return redirect()->back()->with('success', 'Status updated.');
    }
}
