<?php
date_default_timezone_set('Europe/Amsterdam');

// Simple Event paser
$api_response = json_decode(
    file_get_contents(
        'https://raw.githubusercontent.com/Appsterdam/api/main/events.json'
    )
);

$index = "# Appsterdam Events History";

foreach ($api_response as $year) {
    // Create a new year
    if (!file_exists($year->name)) {
        mkdir($year->name);
    }

    if (!isset($indexes[$year->name])) {
        $index .= "\n\n## {$year->name}";
        $index .= "\n|Event|Date|Location|Attendees|\n|---|---|---|---|\n";
        $indexes[$year->name] = true;
    }

    foreach ($year->events as $event) {
        $d = explode(':', $event->date);

        // Create a month
        $monthDir = ($year->name == 'Upcoming' ? date('Y') : $year->name) . '/' . substr($d[0], 4, 2);
        if (!file_exists($monthDir)) mkdir($monthDir);

        $eventFilename = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $event->name);

        // Set the file name (dd - eventname.md)
        $filename = substr($d[0], 6, 2) . ' ' . $eventFilename . '.md';

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

        $event->location_name = str_replace('|', '\|', $event->location_name);
        if (preg_match('/http/', $event->location_name)) {
            $event->location_name = "<a href='{$event->location_name}'>Online</a>";
        } else {
            $event->location_name = "<a href='https://maps.apple.com/?q={$event->location_address}'>{$event->location_name}</a>";
        }

        $index .= "|<a href='{$monthDir}/{$filename}'>{$event->name}</a>|{$beginDate}|{$event->location_name}|{$event->attendees}|\n";
    }
}

$index .= "\n\n\nGenerated on " . date('Y-m-d H:i:s T') . "\n\n";

if (file_exists('Upcoming')) rmdir('Upcoming');
file_put_contents('README.md', $index);
