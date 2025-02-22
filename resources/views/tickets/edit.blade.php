@extends('layouts.app')

@section('title', 'Edit Ticket - Ticket Recorder')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Ticket</h1>

        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="customer" class="block text-sm font-medium text-gray-700">Customer Name:</label>
                <input type="text"
                       name="customer"
                       id="customer"
                       value="{{ $ticket->customer }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       required>
                @error('customer')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="topic" class="block text-sm font-medium text-gray-700">Topic:</label>
                <input type="text"
                       name="topic"
                       id="topic"
                       value="{{ $ticket->topic }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       required>
                @error('topic')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority:</label>
                <select name="priority"
                        id="priority"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                    <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                <select name="status"
                        id="status"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                    <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Awaiting Reply" {{ $ticket->status == 'Awaiting Reply' ? 'selected' : '' }}>Awaiting Reply</option>
                    <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 flex items-center">
                <input type="hidden" name="l2" value="0"> <!-- Default value when unchecked -->
                <input type="checkbox"
                       name="l2"
                       id="l2"
                       value="1"
                       {{ $ticket->l2 ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="l2" class="ml-2 block text-sm font-medium text-gray-700">L2 Required</label>
                @error('l2')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 flex items-center">
                <input type="hidden" name="assigned_to_me" value="0"> <!-- Default value when unchecked -->
                <input type="checkbox"
                       name="assigned_to_me"
                       id="assigned_to_me"
                       value="1"
                       {{ $ticket->assigned_to_me ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="assigned_to_me" class="ml-2 block text-sm font-medium text-gray-700">Assign to Me</label>
                @error('assigned_to_me')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="date_answered" class="block text-sm font-medium text-gray-700">Date Answered:</label>
                <input type="date"
                       name="date_answered"
                       id="date_answered"
                       value="{{ \Carbon\Carbon::parse($ticket->date_answered)->format('Y-m-d') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('date_answered')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="time_answered" class="block text-sm font-medium text-gray-700">Time Answered:</label>
                <input type="time"
                       name="time_answered"
                       id="time_answered"
                       value="{{ \Carbon\Carbon::parse($ticket->time_answered)->format('H:i') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('time_answered')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update
                </button>
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
