<?php
session_start();
include 'db.php';

$eventsFile = 'events.json';
$eventsData = readJSONFile($eventsFile);

// Handle form submission for adding events
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEvent = [
        'event_id' => $_POST['event_id'],
        'event_name' => $_POST['event_name'],
        'event_type' => $_POST['event_type'],
        'event_date' => $_POST['event_date'],
        'organizer' => $_POST['organizer'],
    ];
    
    $eventsData[] = $newEvent; // Add new event
    writeJSONFile($eventsFile, $eventsData); // Save to JSON
    header("Location: events.php");
    exit;
}

// Handle deletion of an event
if (isset($_GET['delete'])) {
    $eventsData = array_filter($eventsData, function($event) {
        return $event['event_id'] !== $_GET['delete'];
    });
    writeJSONFile($eventsFile, $eventsData);
    header("Location: events.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Manage Events</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="participants.php">Manage Participants</a></li>
                <li><a href="registrations.php">Manage Registrations</a></li>
                <li><a href="feedback.php">Manage Feedback</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add New Event</h2>
        <form method="POST">
            <input type="text" name="event_id" placeholder="Event ID" required>
            <input type="text" name="event_name" placeholder="Event Name" required>
            <input type="text" name="event_type" placeholder="Event Type" required>
            <input type="date" name="event_date" required>
            <input type="text" name="organizer" placeholder="Organizer" required>
            <button type="submit">Add Event</button>
        </form>

        <h2>Existing Events</h2>
        <ul>
            <?php foreach ($eventsData as $event): ?>
                <li>
                    <?php echo htmlspecialchars($event['event_name']); ?> 
                    (<a href="?delete=<?php echo urlencode($event['event_id']); ?>">Delete</a>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Event Registration Tracker</p>
    </footer>
</body>
</html>
