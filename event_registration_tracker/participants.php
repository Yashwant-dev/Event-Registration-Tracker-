<?php
session_start();
include 'db.php';

$participantsFile = 'participants.json';
$participantsData = readJSONFile($participantsFile);

// Handle form submission for adding participants
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newParticipant = [
        'participant_id' => $_POST['participant_id'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'preference' => $_POST['preference'],
    ];
    
    $participantsData[] = $newParticipant; // Add new participant
    writeJSONFile($participantsFile, $participantsData); // Save to JSON
    header("Location: participants.php");
    exit;
}

// Handle deletion of a participant
if (isset($_GET['delete'])) {
    $participantsData = array_filter($participantsData, function($participant) {
        return $participant['participant_id'] !== $_GET['delete'];
    });
    writeJSONFile($participantsFile, $participantsData);
    header("Location: participants.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participants</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Manage Participants</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="events.php">Manage Events</a></li>
                <li><a href="registrations.php">Manage Registrations</a></li>
                <li><a href="feedback.php">Manage Feedback</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add New Participant</h2>
        <form method="POST">
            <input type="text" name="participant_id" placeholder="Participant ID" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="text" name="preference" placeholder="Preference">
            <button type="submit">Add Participant</button>
        </form>

        <h2>Existing Participants</h2>
        <ul>
            <?php foreach ($participantsData as $participant): ?>
                <li>
                    <?php echo htmlspecialchars($participant['name']); ?> 
                    (<a href="?delete=<?php echo urlencode($participant['participant_id']); ?>" style="color: red;">Delete</a>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Event Registration Tracker</p>
    </footer>
</body>
</html>
