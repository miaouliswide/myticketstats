<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function dashboard(Request $request)
    {
        // Build the base query
        $query = Ticket::query();

        // Apply filters based on request input
        $this->applyFilters($query, $request);

        // Paginate the filtered results (e.g., 10 per page)
        $tickets = $query->paginate(10);

        // Calculate counts (without pagination)
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'Open')->count();
        $inProgress = Ticket::where('status', 'In Progress')->count();
        $myTickets = Ticket::where('assigned_to_me', true)->count();

        return view('dashboard', compact('tickets', 'totalTickets', 'openTickets', 'inProgress', 'myTickets'));
    }

    public function index(Request $request)
    {
        // Build the base query
        $query = Ticket::query();

        // Apply filters based on request input
        $this->applyFilters($query, $request);

        // Paginate the filtered results (e.g., 10 per page)
        $tickets = $query->paginate(10);

        // Calculate counts (without pagination)
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'Open')->count();
        $inProgress = Ticket::where('status', 'In Progress')->count();
        $myTickets = Ticket::where('assigned_to_me', true)->count();

        return view('tickets.index', compact('tickets', 'totalTickets', 'openTickets', 'inProgress', 'myTickets'));
    }


    private function applyFilters($query, Request $request)
    {
        // Filter by customer (partial match)
        if ($request->filled('customer')) {
            $query->where('customer', 'like', '%' . $request->input('customer') . '%');
        }

        // Filter by topic (partial match)
        if ($request->filled('topic')) {
            $query->where('topic', 'like', '%' . $request->input('topic') . '%');
        }

        // Filter by priority (exact match)
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter by status (exact match)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by L2 (boolean)
        if ($request->has('l2')) {
            $query->where('l2', filter_var($request->input('l2'), FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by assigned_to_me (boolean)
        if ($request->has('assigned_to_me')) {
            $query->where('assigned_to_me', filter_var($request->input('assigned_to_me'), FILTER_VALIDATE_BOOLEAN));
        }
    }


    public function create()
    {
        return view('tickets.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,Awaiting Reply,In Progress,Closed',
            'l2' => 'sometimes|boolean', // Ensures 'l2' is optional but must be a boolean if present
            'date_answered' => 'nullable|date',
            'time_answered' => 'nullable|date_format:H:i',
        ]);

        Ticket::create([
            'customer' => $request->customer,
            'topic' => $request->topic,
            'priority' => $request->priority,
            'status' => $request->status,
            'l2' => (bool) $request->l2, // Explicitly cast to boolean to avoid null issues
            'date_arrived' => Carbon::today()->toDateString(), // Ensuring consistent date format
            'time_arrived' => Carbon::now()->format('H:i:s'),
            'date_answered' => $request->date_answered,
            'time_answered' => $request->time_answered ? $request->time_answered . ':00' : null,
            'assigned_to_me' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'customer' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,Awaiting Reply,In Progress,Closed',
            'l2' => 'sometimes|boolean', // Ensures that 'l2' is treated as optional but must be a boolean if present
            'date_answered' => 'nullable|date',
            'time_answered' => 'nullable|date_format:H:i',
        ]);

        $ticket->update([
            'customer' => $request->customer,
            'topic' => $request->topic,
            'priority' => $request->priority,
            'status' => $request->status,
            'l2' => (bool) $request->l2, // Explicitly cast to boolean to avoid null issues
            'date_answered' => $request->date_answered,
            'time_answered' => $request->time_answered ? $request->time_answered . ':00' : null, // Ensuring proper format
        ]);

        return redirect()->route('dashboard')->with('success', 'Ticket updated successfully.');
    }


    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('dashboard'); // Redirect to dashboard
    }

    public function assignToMe(Request $request)
    {
        logger('assignToMe method reached'); // Check logs to see if this runs

        $ticketIds = $request->input('ticket_ids', []);
        Ticket::whereIn('id', $ticketIds)->update(['assigned_to_me' => true]);

        return redirect()->route('dashboard');
    }

    public function statistics()
    {
        // Fetch all tickets assigned to me
        $myTickets = Ticket::where('assigned_to_me', true)->get();
        $myAnsweredTickets = $myTickets->whereNotNull('date_answered');

        // 1. Total Answered by Me
        $totalAnsweredByMe = $myAnsweredTickets->count();

        $myResponseTimes = Ticket::where('assigned_to_me', true)
        ->whereNotNull('date_answered')
        ->get()
        ->map(function ($ticket) {
            // Ensure values are properly formatted
            $dateArrived = $ticket->date_arrived->format('Y-m-d'); // Format as date-only string
            $dateAnswered = $ticket->date_answered->format('Y-m-d'); // Format as date-only string
            $timeArrived = $ticket->time_arrived ? Carbon::parse($ticket->time_arrived)->format('H:i:s') : '00:00:00';
            $timeAnswered = $ticket->time_answered ? Carbon::parse($ticket->time_answered)->format('H:i:s') : '00:00:00';


            // Skip invalid records
            if (!strtotime("$dateArrived $timeArrived") || !strtotime("$dateAnswered $timeAnswered")) {
                return null;
            }

            // Combine date and time into a single datetime string
            $arrived = Carbon::createFromFormat('Y-m-d H:i:s', "$dateArrived $timeArrived");
            $answered = Carbon::createFromFormat('Y-m-d H:i:s', "$dateAnswered $timeAnswered");

            // Calculate response time in hours
            $responseTime = $arrived->diffInHours($answered);

            return $responseTime;
        })->filter(); // Remove null values

        $myAvgResponseTime = $myResponseTimes->count() > 0 ? $myResponseTimes->avg() : null;

        $resolutionDistribution = [
            'less_than_1h' => $myResponseTimes->filter(fn($time) => $time < 1)->count(),
            '1_to_24h' => $myResponseTimes->filter(fn($time) => $time >= 1 && $time <= 24)->count(),
            'more_than_24h' => $myResponseTimes->filter(fn($time) => $time > 24)->count(),
        ];

        $myEscalatedToL2 = Ticket::where('assigned_to_me', true)
            ->where('l2', true)
            ->count();

        $myAnsweredPerDay = Ticket::where('assigned_to_me', true)
            ->whereDate('date_answered', Carbon::today())
            ->count();

        $myAnsweredPerWeek = Ticket::where('assigned_to_me', true)
            ->whereBetween('date_answered', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $myAnsweredPerMonth = Ticket::where('assigned_to_me', true)
            ->whereMonth('date_answered', Carbon::now()->month)
            ->whereYear('date_answered', Carbon::now()->year)
            ->count();

        $allResponseTimes = Ticket::whereNotNull('date_answered')
        ->get()
        ->map(function ($ticket) {
            // Ensure values are properly formatted
            $dateArrived = $ticket->date_arrived->format('Y-m-d'); // Format as date-only string
            $dateAnswered = $ticket->date_answered->format('Y-m-d'); // Format as date-only string
            $timeArrived = $ticket->time_arrived ? Carbon::parse($ticket->time_arrived)->format('H:i:s') : '00:00:00';
            $timeAnswered = $ticket->time_answered ? Carbon::parse($ticket->time_answered)->format('H:i:s') : '00:00:00';

            // Skip invalid records
            if (!strtotime("$dateArrived $timeArrived") || !strtotime("$dateAnswered $timeAnswered")) {
                return null;
            }

            // Combine date and time into a single datetime string
            $arrived = Carbon::createFromFormat('Y-m-d H:i:s', "$dateArrived $timeArrived");
            $answered = Carbon::createFromFormat('Y-m-d H:i:s', "$dateAnswered $timeAnswered");

            // Calculate response time in hours
            return $arrived->diffInHours($answered);
        })->filter(); // Remove null values




        $overallAvgResponseTime = $allResponseTimes->count() > 0 ? $allResponseTimes->avg() : 0;

        $overallAvgResponseTime = $allResponseTimes->count() > 0 ? $allResponseTimes->avg() : 0;


        // Calculate efficiency score
        $efficiencyScore = ($myAvgResponseTime !== null && $overallAvgResponseTime > 0)
            ? ($overallAvgResponseTime - $myAvgResponseTime) / $overallAvgResponseTime * 100
            : null;


        $totalAssignedToMe = Ticket::where('assigned_to_me', true)->count();
        $resolutionRate = $totalAssignedToMe > 0 ? ($totalAnsweredByMe / $totalAssignedToMe) * 100 : 0;

        $priorityBreakdown = Ticket::where('assigned_to_me', true)
            ->whereNotNull('date_answered')
            ->select('priority')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, CONCAT(date_arrived, " ", time_arrived), CONCAT(date_answered, " ", COALESCE(time_answered, "00:00:00")))) as avg_time')
            ->groupBy('priority')
            ->get();

        $hourlyBreakdown = Ticket::where('assigned_to_me', true)
            ->whereNotNull('date_answered')
            ->selectRaw('HOUR(time_answered) as hour')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $backlog = Ticket::where('assigned_to_me', true)
            ->whereNull('date_answered')
            ->count();

        $firstResponseTime = $myAvgResponseTime;


        return view('tickets.statistics', compact(
            'totalAnsweredByMe',
            'myAvgResponseTime',
            'resolutionDistribution',
            'myEscalatedToL2',
            'myAnsweredPerDay',
            'myAnsweredPerWeek',
            'myAnsweredPerMonth',
            'efficiencyScore',
            'resolutionRate',
            'priorityBreakdown',
            'hourlyBreakdown',
            'backlog',
            'firstResponseTime'
        ));
    }


    public function show(Ticket $ticket)
    {
        return view('tickets.statistics', compact('ticket'));
    }
}
