<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rejestracja - kaszotti</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="container">
    <h1 class="logo">kaszotti</h1>
    <nav>
      <ul>
        <li><a href="index.html">Strona główna</a></li>
        <li><a href="login.html">Logowanie</a></li>
      </ul>
    </nav>
  </div>
</header>
<main>
  <section id="register">
    <div class="container login-container" align="center">
      <h2>Zarejestruj się</h2>
      <form action="register.php" method="POST">
        <label for="username">Nazwa użytkownika</label>
        <input type="text" id="username" name="username" placeholder="Wpisz nazwę użytkownika" required><br>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Wpisz swój e-mail" required><br>

        <label for="password">Hasło</label>
        <input type="password" id="password" name="password" placeholder="Wpisz hasło" required><br>

        <label for="confirm_password">Potwierdź hasło</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Potwierdź hasło" required><br>

        <button type="submit" class="btn">Zarejestruj się</button>
      </form>
      <p>Masz już konto? <a href="login.html">Zaloguj się</a></p>
    </div>
  </section>
</main>
<footer>
  <div class="container">
    <p>&copy; 2024 Michał Knurowski "kaszotti". Wszystkie prawa zastrzeżone.</p>
  </div>
</footer>
<?php
// Dane połączenia z bazą danych
$host = "localhost"; // Adres serwera bazy danych
$db_user = "root"; // Użytkownik bazy danych
$db_password = ""; // Hasło użytkownika bazy danych
$db_name = "kaszotti"; // Nazwa bazy danych

// Nawiązanie połączenia z bazą danych
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Sprawdzenie połączenia
if ($conn->connect_error) {
die("Połączenie nieudane: " . $conn->connect_error);
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// Walidacja
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
die("Wszystkie pola są wymagane.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
die("Nieprawidłowy adres e-mail.");
}

if ($password !== $confirm_password) {
die("Hasła nie są takie same.");
}

// Hashowanie hasła
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Sprawdzenie, czy użytkownik istnieje
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
die("Nazwa użytkownika lub e-mail są już zajęte.");
}

// Wstawienie danych do bazy
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
echo "Rejestracja zakończona sukcesem. <a href='login.html'>Zaloguj się</a>";
} else {
echo "Wystąpił błąd podczas rejestracji: " . $conn->error;
}

$stmt->close();
}

$conn->close();
?>

</body>
</html>
