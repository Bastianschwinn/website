<?php
// send_mail.php - DSGVO-konforme E-Mail-Verarbeitung
// Nur für deutsche Server mit PHP-Unterstützung

// Sicherheitsheader
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Nur POST-Anfragen erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Eingabedaten validieren und bereinigen
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// E-Mail-Adresse validieren
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Formular-Daten erfassen
$firstName = sanitize_input($_POST['firstName'] ?? '');
$lastName = sanitize_input($_POST['lastName'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$clientType = sanitize_input($_POST['clientType'] ?? '');
$urgency = sanitize_input($_POST['urgency'] ?? '');
$insurance = sanitize_input($_POST['insurance'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');

// Pflichtfelder prüfen
if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Pflichtfelder sind nicht ausgefüllt']);
    exit;
}

// E-Mail-Adresse validieren
if (!validate_email($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ungültige E-Mail-Adresse']);
    exit;
}

// Spam-Schutz: Rate Limiting (einfach)
session_start();
$now = time();
$last_submit = $_SESSION['last_submit'] ?? 0;
if ($now - $last_submit < 30) { // Mindestens 30 Sekunden zwischen Anfragen
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Bitte warten Sie 30 Sekunden zwischen Anfragen']);
    exit;
}
$_SESSION['last_submit'] = $now;

// E-Mail-Konfiguration
$to = 'coaching-schwinn@gmx.de'; // Ihre E-Mail-Adresse
$subject = 'Kontaktanfrage von ' . $firstName . ' ' . $lastName;

// E-Mail-Inhalt erstellen
$email_body = "Neue Kontaktanfrage über die Website\n\n";
$email_body .= "=== KONTAKTDATEN ===\n";
$email_body .= "Name: " . $firstName . " " . $lastName . "\n";
$email_body .= "E-Mail: " . $email . "\n";
$email_body .= "Telefon: " . ($phone ?: 'Nicht angegeben') . "\n\n";

$email_body .= "=== DETAILS ===\n";

// Client Type
$clientTypes = [
    'kind' => 'Für ein Kind (unter 12 Jahre)',
    'jugendlicher' => 'Für einen Jugendlichen (12-18 Jahre)',
    'junger-erwachsener' => 'Für einen jungen Erwachsenen (18-21 Jahre)'
];
$email_body .= "Unterstützung für: " . ($clientTypes[$clientType] ?? 'Nicht angegeben') . "\n";

// Urgency
$urgencies = [
    'niedrig' => 'Niedrig - Ich informiere mich erstmal',
    'mittel' => 'Mittel - Ich würde gerne bald einen Termin',
    'hoch' => 'Hoch - Ich benötige zeitnah Unterstützung'
];
$email_body .= "Dringlichkeit: " . ($urgencies[$urgency] ?? 'Nicht angegeben') . "\n";

// Insurance
$insurances = [
    'gesetzlich' => 'Gesetzlich versichert',
    'privat' => 'Privat versichert',
    'beihilfe' => 'Beihilfe',
    'selbstzahler' => 'Selbstzahler'
];
$email_body .= "Versicherung: " . ($insurances[$insurance] ?? 'Nicht angegeben') . "\n\n";

$email_body .= "=== NACHRICHT ===\n";
$email_body .= $message . "\n\n";

$email_body .= "=== TECHNISCHE DATEN ===\n";
$email_body .= "IP-Adresse: " . $_SERVER['REMOTE_ADDR'] . "\n";
$email_body .= "User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unbekannt') . "\n";
$email_body .= "Zeitstempel: " . date('d.m.Y H:i:s') . "\n";

// E-Mail-Header
$headers = [
    'From: website@bastian-schwinn.de',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'Content-Type: text/plain; charset=UTF-8'
];

// E-Mail senden
if (mail($to, $subject, $email_body, implode("\r\n", $headers))) {
    // Erfolgreich gesendet
    echo json_encode([
        'success' => true, 
        'message' => 'Ihre Nachricht wurde erfolgreich gesendet! Ich melde mich bald bei Ihnen zurück.'
    ]);
    
    // Optional: Log für DSGVO-Dokumentation
    error_log("Kontaktformular: E-Mail von $email gesendet");
    
} else {
    // Fehler beim Senden
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Fehler beim Senden der Nachricht. Bitte versuchen Sie es später erneut.'
    ]);
    
    // Log-Eintrag für Debugging
    error_log("Kontaktformular: Fehler beim Senden der E-Mail von $email");
}
?>
