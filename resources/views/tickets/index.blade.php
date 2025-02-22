@extends('layouts.app')

@section('title', 'Tickets - Ticket Recorder')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Ticket Management</h1>

        <div class="mb-6 flex space-x-4">
            <a href="{{ route('tickets.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">New Ticket</a>
            <a href="{{ route('tickets.statistics') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">View Statistics</a>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to Dashboard</a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('tickets.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Customer</label>
                <input type="text" name="customer" value="{{ request('customer') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Topic</label>
                <input type="text" name="topic" value="{{ request('topic') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Priority</label>
                <select name="priority" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">All</option>
                    <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">All</option>
                    <option value="Open" {{ request('status') === 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Awaiting Reply" {{ request('status') === 'Awaiting Reply' ? 'selected' : '' }}>Awaiting Reply</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">L2 Required</label>
                <select name="l2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('l2') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('l2') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Assigned to Me</label>
                <select name="assigned_to_me" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('assigned_to_me') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('assigned_to_me') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-span-3 flex space-x-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Filter</button>
                <a href="{{ url()->current() }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Clear Filters</a>
            </div>
        </form>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong class="text-gray-700">Total Tickets:</strong> {{ $totalTickets }}
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong class="text-gray-700">Open Tickets:</strong> {{ $openTickets }}
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong class="text-gray-700">In Progress:</strong> {{ $inProgress }}
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong class="text-gray-700">My Assigned Tickets:</strong> {{ $myTickets }}
            </div>
        </div>

        <!-- Tickets Table -->
        <form action="{{ route('tickets.assignToMe') }}" method="POST" class="mt-3" id="assignForm">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Select</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Topic</th>
                            <th class="px-6 py-3">Priority</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">L2</th>
                            <th class="px-6 py-3">Date Arrived</th>
                            <th class="px-6 py-3">Time Arrived</th>
                            <th class="px-6 py-3">Date Answered</th>
                            <th class="px-6 py-3">Time Answered</th>
                            <th class="px-6 py-3">Assigned</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4">{{ $ticket->customer }}</td>
                                <td class="px-6 py-4">{{ $ticket->topic }}</td>
                                <td class="px-6 py-4">{{ $ticket->priority }}</td>
                                <td class="px-6 py-4">{{ $ticket->status }}</td>
                                <td class="px-6 py-4">{{ $ticket->l2 ? 'Yes' : 'No' }}</td>
                                <td class="px-6 py-4">{{ $ticket->date_arrived->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $ticket->time_arrived ? Carbon\Carbon::parse($ticket->time_arrived)->format('H:i:s') : 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $ticket->date_answered ? $ticket->date_answered->format('Y-m-d') : 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $ticket->time_answered ? Carbon\Carbon::parse($ticket->time_answered)->format('H:i:s') : 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $ticket->assigned_to_me ? 'Yes' : 'No' }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">Edit</a>
                                    <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600" onclick="deleteTicket({{ $ticket->id }})">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-4 text-center text-gray-500">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Assign to Me</button>
        </form>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $tickets->appends(request()->query())->links() }}
        </div>

        <script>
            function deleteTicket(ticketId) {
                if (confirm('Are you sure?')) {
                    fetch(`/tickets/${ticketId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload(); // Reload page after deletion
                        }
                    });
                }
            }
        </script>
    </div>
@endsection
