<?php
session_start();
include 'db.php';

// Fetch registrations data from database
$stmt = $pdo->query("SELECT * FROM registrations");
$registrationsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch participants and events for dropdowns
$participantsData = $pdo->query("SELECT * FROM participants")->fetchAll(PDO::FETCH_ASSOC);
$eventsData = $pdo->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a new registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO registrations (registration_id, participant_id, event_id, registration_date, comments) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['registration_id'], $_POST['participant_id'], $_POST['event_id'], date('Y-m-d'), $_POST['comments']]);
    header("Location: registrations.php");
    exit;
}

// Handle deletion of a registration
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE registration_id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: registrations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registrations</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Manage Registrations</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="participants.php">Manage Participants</a></li>
                <li><a href="events.php">Manage Events</a></li>
                <li><a href="feedback.php">Manage Feedback</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add New Registration</h2>
        <form method="POST">
            <input type="text" name="registration_id" placeholder="Registration ID" required>
            <select name="participant_id" required>
                <option value="">Select Participant</option>
                <?php foreach ($participantsData as $participant): ?>
                    <option value="<?php echo htmlspecialchars($participant['participant_id']); ?>"><?php echo htmlspecialchars($participant['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($eventsData as $event): ?>
                    <option value="<?php echo htmlspecialchars($event['event_id']); ?>"><?php echo htmlspecialchars($event['event_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="comments" placeholder="Comments"></textarea>
            <button type="submit">Add Registration</button>
        </form>

        <h2>Existing Registrations</h2>
        <ul>
            <?php foreach ($registrationsData as $registration): ?>
                <li>
                    <?php echo htmlspecialchars($registration['registration_id']); ?>
                    (<a href="?delete=<?php echo urlencode($registration['registration_id']); ?>">Delete</a>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Event Registration Tracker</p>
    </footer>
</body>

</html>
