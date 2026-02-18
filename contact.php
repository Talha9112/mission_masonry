<?php

// Simple form handler for CTA form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

function field(string $key): string {
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

$name     = field('name');
$phone    = field('phone');
$email    = field('email');
$services = isset($_POST['services']) && is_array($_POST['services']) ? $_POST['services'] : [];
$message  = field('message');

$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if (empty($services)) {
    $errors[] = 'Please select at least one service.';
}
if ($message === '') {
    $errors[] = 'Please include a short description of your project.';
}

// Basic sanitisation for services list
$servicesClean = array_map(function ($s) {
    return trim(strip_tags((string)$s));
}, $services);
$serviceList = implode(', ', $servicesClean);

// If there are errors, show them to the user
if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Mission Masonry – Form Error</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body{font-family:Arial,sans-serif;background:#0C0800;color:#FAF6EC;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
            .card{background:#160D01;border:1px solid rgba(200,169,81,.4);padding:2rem 2.5rem;border-radius:8px;max-width:480px;width:90%;}
            h1{font-size:1.4rem;margin:0 0 1rem;}
            ul{margin:.5rem 0 1.5rem 1.2rem;padding:0;}
            li{margin-bottom:.3rem;font-size:.9rem;}
            a{color:#C8A951;text-decoration:none;font-size:.9rem;}
            a:hover{text-decoration:underline;}
        </style>
    </head>
    <body>
        <div class="card">
            <h1>We couldn't submit your request</h1>
            <p>Please fix the following and try again:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="index.html#contact">← Go back to the form</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Send email (replace with your real inbox address)
$to      = 'you@example.com'; // TODO: change to your email address
$subject = 'New Mission Masonry inquiry';

$body  = "New inquiry from the website CTA form:\n\n";
$body .= "Name: {$name}\n";
$body .= "Phone: {$phone}\n";
$body .= "Email: {$email}\n";
$body .= "Services: {$serviceList}\n\n";
$body .= "Description:\n{$message}\n";

$headers   = [];
$headers[] = 'From: ' . sprintf('"%s" <%s>', addslashes($name), $email);
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

@mail($to, $subject, $body, implode("\r\n", $headers));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You – Mission Masonry</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{font-family:Arial,sans-serif;background:#0C0800;color:#FAF6EC;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
        .card{background:#160D01;border:1px solid rgba(200,169,81,.4);padding:2rem 2.5rem;border-radius:8px;max-width:480px;width:90%;text-align:center;}
        h1{font-size:1.5rem;margin:0 0 1rem;}
        p{font-size:.95rem;margin:.4rem 0;}
        a{color:#C8A951;text-decoration:none;font-size:.9rem;}
        a:hover{text-decoration:underline;}
    </style>
</head>
<body>
    <div class="card">
        <h1>Thank you for reaching out.</h1>
        <p>We've received your request and will contact you as soon as possible.</p>
        <p><a href="index.html">← Back to Mission Masonry</a></p>
    </div>
</body>
</html>

