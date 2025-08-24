<?php
header('Content-Type: text/calendar; charset=utf-8');

$vCAL = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//wesleydegroot/projects//Appsterdam Event Calendar//EN
URL:https://0xwdg.github.io/Appsterdam-Events-History/calendar.ical
NAME:Appsterdam Event Calendar
X-WR-CALNAME:Appsterdam Event Calendar
DESCRIPTION:Appsterdam Event Calendar
X-WR-CALDESC:Appsterdam Event Calendar
CALSCALE:GREGORIAN
METHOD:PUBLISH
TIMEZONE-ID:Europe/Amsterdam
X-WR-TIMEZONE:Europe/Amsterdam
REFRESH-INTERVAL;VALUE=DURATION:PT24H
X-PUBLISHED-TTL:PT24H
";

// if safari then return as plain text
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false && PHP_OS == 'Darwin') {
    header('Content-Type: text/plain; charset=utf-8');
}

// Set the timezone to Amsterdam
date_default_timezone_set('Europe/Amsterdam');

// Get the API response
$api_response = json_decode(
    file_get_contents(
        'https://raw.githubusercontent.com/Appsterdam/api/main/events.json'
    )
);

$events=[];

foreach ($api_response as $year) {
    foreach ($year->events as $event) {
        // Add the event to the events array
        $date = substr($event->date, 0, 8); // YYYYMMDD
        $start_time = substr($event->date, 8, 6); // HHMMSS
        $events[] = array(
            "name" => $event->name,
            "description" => $event->description,
            "date" => $date,
            "start_time" => substr($event->date, 8, 6),
            "end_time" => substr($event->date, -6),
            "location" => $event->location_address,
            "url" => "https://meetup.com/appsterdam/events/" . $event->id,
            "organizer" => $event->organizer,
            "attendees" => $event->attendees
        );
    }
}

foreach ($events as $event) {
    /*
     "id": "307005832",
     "organizer": "D\u00e1niel Varga, Maike Warner",
     "date": "20250419093000:20250419113000",
     "name": "Coffee Coding",
     "description": "BACK TO COFFEE ROOM AGAIN!\n\nWelcome to Appsterdam.\n\nWhether you are a seasoned programmer or learning to code Apps, wouldn\u2019t it be cool to run by your challenge with another programmer when you get stuck? Or you have questions, or simply want to be in the environment of other like-minded friends while you work on your project?\n\nWelcome to Appsterdam.\n\nBring your laptop and get feedback, encouragement and support, no matter what level you are at.\n\nShare and contribute. Get guidance. Feel good.\n\nAll levels are welcome, so don\u2019t be shy.\n\nIn the future we plan to have break-out sessions where we will have short talks on valuable topics and also conduct mini-hackathons where all of us get to participate and learn together.\n\nMaike and Daniel are super committed to have an inspired group.\n\nWe start at 9:30 and end at 11:30 sharp, so some a bit closer to 9:30 to make sure you get the help you desire.\n\nJust ask and maybe we can help you with:\n\n\u2022 Android Development\n\n\u2022 iOS Development\n\n\u2022 Flutter Development\n\n\u2022 C++\n\n\u2022 JavaScript\n\n\u2022 Python\n\n\u2022 Rust\n\n\u2022 UX\/UI Design (Adobe XD, Balsamiq, Keynote, Sketch, Figma)",
     "price": "0",
     "location_name": "The Coffee Room",
     "location_address": "Kinkerstraat 110, Amsterdam",
     "icon": "note.text",
     "attendees": "10",
     "latitude": "0",
     "longitude": "0"
     */
    if (PHP_OS == 'Darwin') {
        $event['name'] = $event['name'] . " (Local)";
    }

    $vEVENT = sprintf("BEGIN:VEVENT\n");
    $vEVENT .= sprintf(
        "UID:%s@events.appsterdam.rs\n",
        md5($event['name'] . $event['date'] . $event['start_time'] . $event['end_time'])
    );
    $vEVENT .= sprintf("SUMMARY:%s\n", $event['name']);
    $description = str_replace("\n", "\\n", $event['description']);
    $description = str_replace("\r", "\\r", $description);
    $vEVENT .= sprintf("DESCRIPTION:%s\n", $description);
    if ($event['start_time'] == '000000') {
        $vEVENT .= sprintf("DTSTART:%s\n", $event['date']);
    } else {
        $vEVENT .= sprintf("DTSTART:%s\n", $event['date'] . 'T' . $event['start_time']);
        $vEVENT .= sprintf("DTEND:%s\n", $event['date'] . 'T' . $event['end_time']);
    }

    $vEVENT .= sprintf("LOCATION:%s\n", isset($event['location']) ? $event['location'] : 'Online');
    $vEVENT .= sprintf("CLASS:PUBLIC\n");
    $vEVENT .= sprintf("STATUS:CONFIRMED\n");
    $organizerCount = 0;
    foreach(explode(", ", $event['organizer']) as $organizer) {
        if ($organizerCount == 0) {
           $vEVENT .= sprintf("ORGANIZER;CN=\"%s\":MAILTO:%s@appsterdam.rs\n", $organizer, explode(" ", $organizer)[0]);
        }
        $vEVENT .= sprintf("ATTENDEE;PARTSTAT=ACCEPTED;RSVP=TRUE;CN=\"%s\":MAILTO:%s@appsterdam.rs\n", $organizer, explode(" ", $organizer)[0]);
        $organizerCount++;
    }
    // Attendees.
    for($i=0; $i<($event['attendees']-2); $i++) {
        $vEVENT .= sprintf("ATTENDEE;PARTSTAT=ACCEPTED;RSVP=TRUE;CN=\"%s\":MAILTO:%s@attendee.appsterdam.rs\n", "Attendee $i", $i);
    }
    $vEVENT .= sprintf("CATEGORIES:Appsterdam Events\n");
    $vEVENT .= sprintf("URL:%s\n", $event['url']);
    $vEVENT .= sprintf("END:VEVENT\n");
    $vCAL .= $vEVENT;
}
$vCAL .= "END:VCALENDAR";

echo $vCAL;

file_put_contents(
    __DIR__ . '/calendar.ics',
    $vCAL
);
?>
