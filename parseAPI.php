<?php

// Simple Event paser
$api_response = json_decode(
    file_get_contents(
        'https://raw.githubusercontent.com/Appsterdam/api/main/events.json'
    )
);

foreach ($api_response as $year) {
    // Create a new year
    if (!file_exists($year->name)) mkdir($year->name);

    foreach ($year->events as $event) {
        $d = explode(':', $event->date);

        // Create a month
        $monthDir = ($year->name == 'Upcoming' ? date('Y') : $year->name) . '/' . substr($d[0], 4, 2);
        if (!file_exists($monthDir)) mkdir($monthDir);

        $newEventName = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $event->name);

        // Set the file name (dd - eventname.md)
        $filename = substr($d[0], 6, 2) . ' ' . $newEventName . '.md';

        // Bad way to generate begin/end date.
        $beginDate = substr($d[0], 0, 4) . '-' . substr($d[0], 4, 2) . '-' . substr($d[0], 6, 2);
        $endDate = substr($d[1], 0, 4) . '-' . substr($d[1], 4, 2) . '-' . substr($d[1], 6, 2);

        // Create the markdown file.
        $markdown = "# {$event->name}
Held at {$beginDate} at {$event->location_name} with {$event->attendees} Appsterdammers.
        
|Key|Value
|---|---|
|id|[{$event->id}](https://www.meetup.com/appsterdam/events/{$event->id}/)|
|name|{$event->name}|
|organizer|{$event->organizer}|
|attendees|{$event->attendees}|
|begin date|{$beginDate}|
|end date|{$endDate}|
|price|{$event->price}|
|location name|{$event->location_name}|
|location address|{$event->location_address}|
|latitude|{$event->latitude}|
|longitude|{$event->longitude}|
|(in-app) icon|{$event->icon}|

---

{$event->description}
";

        // Save the markdown file.
        file_put_contents(
            $monthDir . '/' . $filename, 
            $markdown
        );
    }
}

if (file_exists('Upcoming')) rmdir('Upcoming');