<?php
// ===============================
// KONFIGURACJA
// ===============================

// ADRES, NA KTÓRY IDZIE ZGŁOSZENIE
$to = "rejestracja@twojadomena.pl";

// TEMAT WIADOMOŚCI
$subject = "Nowe zgłoszenie – Konferencja NOWOCZESNY SZPITAL 2026";

// ADRES NADAWCY (WAŻNE – TEN SAM DOMENOWO!)
$fromEmail = "no-reply@twojadomena.pl";

// ===============================
// SPRAWDZENIE METODY
// ===============================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Niedozwolona metoda.");
}

// ===============================
// POBRANIE I SANITYZACJA DANYCH
// ===============================
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$organization = trim($_POST["organization"] ?? "");

// ===============================
// WALIDACJA
// ===============================
if ($name === "" || $email === "" || $organization === "") {
    http_response_code(400);
    exit("Wszystkie pola są wymagane.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Nieprawidłowy adres e-mail.");
}

// ===============================
// TREŚĆ WIADOMOŚCI
// ===============================
$message  = "NOWE ZGŁOSZENIE NA KONFERENCJĘ\n";
$message .= "---------------------------------\n";
$message .= "Imię i nazwisko: {$name}\n";
$message .= "E-mail: {$email}\n";
$message .= "Organizacja: {$organization}\n";
$message .= "---------------------------------\n";
$message .= "Data zgłoszenia: " . date("Y-m-d H:i:s") . "\n";
$message .= "IP: " . $_SERVER["REMOTE_ADDR"] . "\n";

// ===============================
// NAGŁÓWKI
// ===============================
$headers = [];
$headers[] = "From: Konferencja OSSP <{$fromEmail}>";
$headers[] = "Reply-To: {$email}";
$headers[] = "Content-Type: text/plain; charset=UTF-8";

// ===============================
// WYSYŁKA
// ===============================
if (mail($to, $subject, $message, implode("\r\n", $headers))) {
    // powrót na stronę po wysłaniu
    header("Location: index.html#rejestracja");
    exit;
} else {
    http_response_code(500);
    exit("Błąd podczas wysyłania formularza.");
}