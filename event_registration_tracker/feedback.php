<?php
session_start();
include 'db.php';

// Fetch feedback data from database
$stmt = $pdo->query("SELECT * FROM feedback");
$feedbackData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch participants and events for dropdowns
$participantsData = $pdo->query("SELECT * FROM participants")->fetchAll(PDO::FETCH_ASSOC);
$eventsData = $pdo->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO feedback (feedback_id, event_id, participant_id, rating, comments) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['feedback_id'], $_POST['event_id'], $_POST['participant_id'], $_POST['rating'], $_POST['comments']]);
    header("Location: feedback.php");
    exit;
}

// Handle deletion of feedback
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM feedback WHERE feedback_id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: feedback.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Manage Feedback</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="participants.php">Manage Participants</a></li>
                <li><a href="events.php">Manage Events</a></li>
                <li><a href="registrations.php">Manage Registrations</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add New Feedback</h2>
        <form method="POST">
            <input type="text" name="feedback_id" placeholder="Feedback ID" required>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($eventsData as $event): ?>
                    <option value="<?php echo htmlspecialchars($event['event_id']); ?>"><?php echo htmlspecialchars($event['event_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="participant_id" required>
                <option value="">Select Participant</option>
                <?php foreach ($participantsData as $participant): ?>
                    <option value="<?php echo htmlspecialchars($participant['participant_id']); ?>"><?php echo htmlspecialchars($participant['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
            <textarea name="comments" placeholder="Comments"></textarea>
            <button type="submit">Add Feedback</button>
        </form>

        <?php // Load JSON data from file
$json_data = file_get_contents('feedback.json');
$feedback = json_decode($json_data, true);?>

        <h2>Existing Feedback</h2>
        <ul>
            <?php foreach ($feedbackData as $feedback): ?>
                <li>
                    <?php echo htmlspecialchars($feedback['comments']); ?> 
                    (<a href="?delete=<?php echo urlencode($feedback['feedback_id']); ?>">Delete</a>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Event Registration Tracker</p>
    </footer>
</body>
</html>
