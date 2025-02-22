<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker
        $faker = Faker::create();

        // Define support hours (09:00 - 17:00)
        $supportStartHour = 9;
        $supportEndHour = 17;

        // Define possible statuses
        $statuses = ['Open', 'In Progress', 'Resolved', 'Awaiting for Reply', 'Closed'];
        // Define possible priorities
        $priorities = ['Low', 'Medium', 'High', 'Critical', 'Request for Change', 'Other'];

         // Create tickets assigned to you (better performance)
         for ($i = 0; $i < 50; $i++) {
            // Generate random date arrived (within the last 30 days)
            $dateArrived = Carbon::now()->subDays(rand(0, 30))->format('Y-m-d');

            // Generate random time arrived (within support hours)
            $timeArrived = Carbon::createFromTime(
                rand($supportStartHour, $supportEndHour - 1), // Hour
                rand(0, 59), // Minute
                rand(0, 59)  // Second
            )->format('H:i:s');

            // Date answered is within 1 to 24 hours after date arrived (better performance)
            $dateAnswered = Carbon::parse($dateArrived)->addHours(rand(1, 24))->format('Y-m-d');

            // Time answered is within support hours and after time arrived
            $timeAnswered = Carbon::createFromTime(
                rand($supportStartHour, $supportEndHour - 1), // Hour
                rand(0, 59), // Minute
                rand(0, 59)  // Second
            )->format('H:i:s');

            // Create the ticket
            Ticket::create([
                'customer' => $faker->name, // Generate a random customer name
                'topic' => $faker->sentence, // Generate a random topic
                'status' => $statuses[array_rand($statuses)], // Randomly assign a status
                'priority' => $priorities[array_rand($priorities)], // Randomly assign a priority
                'l2' => rand(0, 1), // Randomly assign L2 escalation
                'assigned_to_me' => true, // Ticket is assigned to you
                'date_arrived' => $dateArrived,
                'time_arrived' => $timeArrived,
                'date_answered' => $dateAnswered,
                'time_answered' => $timeAnswered,
            ]);
        }

        // Create tickets not assigned to you (worse performance)
        for ($i = 0; $i < 50; $i++) {
            // Generate random date arrived (within the last 30 days)
            $dateArrived = Carbon::now()->subDays(rand(0, 30))->format('Y-m-d');

            // Generate random time arrived (within support hours)
            $timeArrived = Carbon::createFromTime(
                rand($supportStartHour, $supportEndHour - 1), // Hour
                rand(0, 59), // Minute
                rand(0, 59)  // Second
            )->format('H:i:s');

            // Date answered is within 24 to 72 hours after date arrived (worse performance)
            $dateAnswered = Carbon::parse($dateArrived)->addHours(rand(24, 72))->format('Y-m-d');

            // Time answered is within support hours and after time arrived
            $timeAnswered = Carbon::createFromTime(
                rand($supportStartHour, $supportEndHour - 1), // Hour
                rand(0, 59), // Minute
                rand(0, 59)  // Second
            )->format('H:i:s');

            // Create the ticket
            Ticket::create([
                'customer' => $faker->name, // Generate a random customer name
                'topic' => $faker->sentence, // Generate a random topic
                'status' => $statuses[array_rand($statuses)], // Randomly assign a status
                'priority' => $priorities[array_rand($priorities)], // Randomly assign a priority
                'l2' => rand(0, 1), // Randomly assign L2 escalation
                'assigned_to_me' => false, // Ticket is not assigned to you
                'date_arrived' => $dateArrived,
                'time_arrived' => $timeArrived,
                'date_answered' => $dateAnswered,
                'time_answered' => $timeAnswered,
            ]);
        }
    }
}
