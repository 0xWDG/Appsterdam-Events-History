<?php

// Set the timezone to Amsterdam
date_default_timezone_set('Europe/Amsterdam');

// Get the API response
$api_response = json_decode(
    file_get_contents(
        'https://raw.githubusercontent.com/Appsterdam/api/main/events.json'
    )
);

// Set the repo URL
$repoURL = "https://github.com/0xWDG/Appsterdam-Events-History";

// Create an array to store the indexes
$stats = array(
    'events' => array(
        'overall' => array()
    ),
    'attendees' => array(
        'overall' => array()
    )
);

$history_md = "# Appsterdam Events History\n\n";
$history_md .= sprintf(
    "<a href='%s/blob/main/README.md'>See event statistics here</a>\n\n",
    $repoURL
);

$history_md .= "### Navigation\n\nREPLACE_ME_NAV";
$nav = '';

foreach ($api_response as $year) {
    // Create a new year
    if (!file_exists($year->name)) {
        // Create a directory for the current year
        mkdir($year->name);
    }

    if (!isset($indexes[$year->name])) {
        // Create an index for the current year
        $history_md .= sprintf("\n\n## %s", $year->name);

        // Create a table for the current year
        $history_md .= "\n<table><tr><th>Event</th><th>Date</th><th>Location</th><th>Attendees</th></tr>";

        // Add the current year to the indexes array.
        $indexes[$year->name] = true;

        // Add the current year to the navigation.
        $nav .= sprintf(
            "<a href='#%s'>%s</a> | ",
            strtolower($year->name),
            ucfirst($year->name)
        );
    }

    foreach ($year->events as $event) {
        // Split the date into begin and end date.
        $d = explode(':', $event->date);

        // Replace 'Upcoming' with the current year.
        $eventYear = (
            $year->name == 'Upcoming'
            ? date('Y')
            : $year->name
        );

        // Create a month
        $monthDir = $eventYear . '/' . substr($d[0], 4, 2);

        // Create a directory for the current month
        if (!file_exists($monthDir))
            mkdir($monthDir);

        // Replace all non-alphanumeric characters with an underscore.
        $eventFilename = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $event->name);

        // Create an temporary event name for the statistics.
        $tempEventName = $event->name;
        // if the event name contains 'weekend fun:', replace it with 'weekend fun'.
        if (preg_match('/weekend fun:/', strtolower($event->name))) {
            $tempEventName = "Weekend Fun";
        }

        // Add the event to the overall statistics.
        $stats['events']['overall'][$tempEventName] = (
            $stats['events']['overall'][$tempEventName] ?? 0
        ) + 1;

        // Add the event to the current year statistics.
        $stats['events'][$eventYear][$tempEventName] = (
            $stats['events'][$eventYear][$tempEventName] ?? 0
        ) + 1;

        // Add the attendees to the overall statistics.
        $stats['attendees']['overall'][$tempEventName] = (
            $stats['attendees']['overall'][$tempEventName] ?? 0
        ) + $event->attendees;

        // Add the attendees to the current year statistics.
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

        // Replace all | with \| to prevent the table from breaking.
        $event->location_name = str_replace('|', '\|', $event->location_name);

        // if the location name contains 'http', replace it with a link.
        if (preg_match('/http/', $event->location_name)) {
            // Location is online
            $event->location_name = sprintf(
                "<a href='%s'>Online</a>",
                $event->location_name
            );
        } else {
            // Navigation link to the location
            $event->location_name = sprintf(
                "<a href='https://maps.apple.com/?q=%s'>%s</a>",
                urlencode($event->location_address),
                $event->location_name
            );
        }

        // Add the event to the table for the current year.
        $history_md .= sprintf(
            "<tr><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>",
            $repoURL . '/blob/main/' . $monthDir . '/' . $filename,
            $event->name,
            $beginDate,
            $event->location_name,
            $event->attendees
        );
    }

    // Close the table for the current year.
    $history_md .= "</table>";
}

// Add the navigation to the index, and remove the last 3 characters ( | )
$history_md = preg_replace(
    '/REPLACE_ME_NAV/',
    substr($nav, 0, -3),
    $history_md
);

// Add generation date and time to the history.
$history_md .= sprintf("\n\n\nGenerated on %s\n\n", date('Y-m-d H:i:s T'));

// Write the history to the file.
file_put_contents('HISTORY.md', $history_md);

// Create the statistics file.
$statistics_md = "# Appsterdam Events Statistics\n\n";
$statistics_md .= sprintf(
    "<a href='%s/blob/main/HISTORY.md'>See historic events here</a>\n\n",
    $repoURL
);

// Add the navigation to the statistics.
$statistics_md .= "### Navigation\n\nREPLACE_ME_NAV";
$nav = '';

foreach ($stats['events'] as $eventYear => $events) {
    // Add the current year to the navigation.
    $nav .= sprintf(
        "<a href='#%s-statistics'>%s</a> | ",
        strtolower($eventYear),
        ucfirst($eventYear)
    );

    // Add the current year to the statistics.
    $statistics_md .= sprintf("\n\n## %s Statistics\n\n", ucfirst($eventYear));

    // Add the events table to the statistics.
    $statistics_md .= "<table><tr><th>Event</th><th>Count</th><th>Average Attendees</th></tr>";
    foreach ($events as $event => $count) {
        // Calculate the average attendees.
        // Round ( attendees of current year for event / occurences of the event)
        $avg = round($stats['attendees'][$eventYear][$event] / $count);

        // Skip events with only one occurance, or with an average of less than 2 attendees.
        if ($count > 1 || $avg > 2) {
            // Add the event to the statistics.
            $statistics_md .= "<tr><td>{$event}</td><td>{$count}</td><td>{$avg}</td></tr>";
        }
    }

    // Close the table for the current year.
    $statistics_md .= "</table>";
}

// Add the navigation to the statistics_md, and remove the last 3 characters ( | )
$statistics_md = preg_replace(
    '/REPLACE_ME_NAV/',
    substr($nav, 0, -3),
    $statistics_md
);

// Add generation date and time to the statistics.
$statistics_md .= sprintf("\n\n\nGenerated on %s\n\n", date('Y-m-d H:i:s T'));

// Write the statistics to the file.
file_put_contents('README.md', $statistics_md);

// Clean up
if (file_exists('Upcoming'))
    rmdir('Upcoming');
