<?
include __DIR__ . '/settings.php';

// connessione al db
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

// var_dump($conn); exit();

// verifico che non ci siano degli errori di connessione
if ($conn && $conn->connect_error) {
	echo "Connection failed: {$conn->connect_error}";
	exit();
}

$q_name = $_GET['student_name'] ?? '';

// se arrivo qui, la connessione è andata a buon fine
$sql = "SELECT `name`, COUNT(*) AS 'conteggio'
			FROM `students`
			WHERE `name` LIKE ?
			GROUP BY `name`
		";

$stmt = $conn->prepare($sql);

$stmt->bind_param('s', $q_name);

$stmt->execute();

$result = $stmt->get_result();

// var_dump($result); exit(); ?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>University</title>
</head>
<body>
	<h1>Ricerca universitaria</h1>

	<form action="" method="get">
		<label for="student_name">Nome studente</label>
		<input type="text" name="student_name" id="student_name" value="<?= $q_name ?>">
		<button>Cerca</button>
	</form>
	<br>
	<br>

	<?php

	if ($result && $result->num_rows > 0) {
		// la query è andata a buon fine e ci sono delle righe di risultati
		// finché ci sono righe di risultati
		while($row = $result->fetch_assoc()) {
			// recupero una riga di risultati alla volta e ne stampo i valori
			// echo "ID: {$row['id']} - nome: {$row['name']} - cognome: {$row['surname']} - email: {$row['email']}";
			echo "{$row['name']} - {$row['conteggio']}";
			echo '<br>';
		}
	} elseif ($result) {
		// la query è andata a buon fine ma non ci sono righe di risultati
		echo "0 results";
	} else {
		// si è verificato un errore nella query (es: nome tabella sbagliato)
		echo "query error";
	}

	// chiudo la connessione al db
	$conn->close(); ?>

</body>
</html>