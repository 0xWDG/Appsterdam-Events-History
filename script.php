<?php
date_default_timezone_set('Europe/Amsterdam');

// Simple Event paser
$api_response = json_decode(
    file_get_contents(
        'https://raw.githubusercontent.com/Appsterdam/api/main/events.json'
    )
);

$stats = array(
    'events' => array(
        'overall' => array()
    ),
    'attendees' => array(
        'overall' => array()
    )
);

$index = "# Appsterdam Events History";

foreach ($api_response as $year) {
    // Create a new year
    if (!file_exists($year->name)) {
        mkdir($year->name);
    }

    if (!isset($indexes[$year->name])) {
        $index .= sprintf("\n\n## %s", $year->name);
        $index .= "\n|Event|Date|Location|Attendees|\n|---|---|---|---|\n";
        $indexes[$year->name] = true;
    }

    foreach ($year->events as $event) {
        $d = explode(':', $event->date);

        $eventYear = (
            $year->name == 'Upcoming'
            ? date('Y')
            : $year->name
        );

        // Create a month
        $monthDir = $eventYear . '/' . substr($d[0], 4, 2);

        if (!file_exists($monthDir))
            mkdir($monthDir);

        $eventFilename = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $event->name);

        $tempEventName = $event->name;
        if (preg_match('/weekend fun:/', strtolower($event->name))) {
            $tempEventName = "Weekend Fun";
        }

        $stats['events']['overall'][$tempEventName] = (
            $stats['events']['overall'][$tempEventName] ?? 0
        ) + 1;

        $stats['events'][$eventYear][$tempEventName] = (
            $stats['events'][$eventYear][$tempEventName] ?? 0
        ) + 1;

        $stats['attendees']['overall'][$tempEventName] = (
            $stats['attendees']['overall'][$tempEventName] ?? 0
        ) + $event->attendees;

        $stats['attendees'][$eventYear][$tempEventName] = (
            $stats['attendees'][$eventYear][$tempEventName] ?? 0
        ) + $event->attendees;

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
            $event->location_name = sprintf(
                "<a href='%s'>Online</a>",
                $event->location_name
            );
        } else {
            $event->location_name = sprintf(
                "<a href='https://maps.apple.com/?q=%s'>%s</a>",
                urlencode($event->location_address),
                $event->location_name
            );
        }

        $index .= sprintf(
            "|<a href='%s'>%s</a>|%s|%s|%s|\n",
            urlencode($monthDir . '/' . $filename),
            $event->name,
            $beginDate,
            $event->location_name,
            $event->attendees
        );
    }
}

foreach ($stats['events'] as $eventYear => $events) {
    $index .= sprintf("\n\n## %s Statistics\n\n", ucfirst($eventYear));
    $index .= "|Event|Count|Average Attendees\n|---|---|---|\n";
    foreach ($events as $event => $count) {
        $avg = round($stats['attendees'][$eventYear][$event] / $count);

        // Skip events with only one occurance, or with an average of less than 2 attendees.
        if ($count > 1 || $avg > 2) {
            $index .= "|{$event}|{$count}|{$avg}|\n";
        }
    }
}

$index .= sprintf("\n\n\nGenerated on %s\n\n", date('Y-m-d H:i:s T'));

if (file_exists('Upcoming'))
    rmdir('Upcoming');

file_put_contents('README.md', $index);
