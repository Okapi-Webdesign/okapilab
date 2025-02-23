<?php
$type = $_POST['type'];

if ($_POST['type'] == 1) {
    $contact_lastname = $_POST['lastname'];
    $contact_firstname = $_POST['firstname'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $registration_number = $_POST['registration_number'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $name = null;
    $tax_number = null;
    $company_form = null;
} elseif ($_POST['type'] == 2) {
    $name = $_POST['name'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $registration_number = $_POST['registration_number'];
    $tax_number = $_POST['tax_number'];
    $company_form = mb_strtoupper($_POST['company_form']);
    $contact_lastname = $_POST['contact_lastname'];
    $contact_firstname = $_POST['contact_firstname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
}

$fulladdress = [
    'zip' => $zip,
    'city' => $city,
    'address' => $address,
    'address2' => $address2
];

$fulladdress = json_encode($fulladdress);

// Ellenőrizzük az e-mail cím meglétét az adatbázisban
if ($stmt = $con->prepare('SELECT id FROM accounts WHERE email = ?')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        alert_redirect('error', URL . 'admin/ugyfelek', 'Az e-mail cím már foglalt!');
    }
    $stmt->close();
}

// Regisztráljuk az ügyfelet
if ($stmt = $con->prepare('INSERT INTO `clients`(`id`, `type`, `name`, `address`, `tax_number`, `registration_number`, `company_form`, `contact_lastname`, `contact_firstname`, `contact_email`, `contact_phone`, `create_date`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())')) {
    $stmt->bind_param('isssssssss', $type, $name, $fulladdress, $tax_number, $registration_number, $company_form, $contact_lastname, $contact_firstname, $email, $phone);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/ugyfelek');
    }
    $id = $stmt->insert_id;
    $stmt->close();
}

// Jelszó generálása
$password = generatePassword();
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Felhasználó regisztrálása
if ($stmt = $con->prepare('INSERT INTO `accounts`(`id`, `lastname`, `firstname`, `email`, `password`, `lastlogin_date`, `role`, `client_id`) VALUES (NULL, ?, ?, ?, ?, NULL, 1, ?)')) {
    $stmt->bind_param('ssssi', $contact_lastname, $contact_firstname, $email, $password_hash, $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/ugyfelek');
    }
    $stmt->close();
}

// Üdvözlő email küldése
if (!mail_send_template(
    $email,
    'client_created',
    [
        'name' => $contact_lastname . ' ' . $contact_firstname,
        'email' => $email,
        'password' => $password,
        'url' => URL
    ]
)) {
    alert_redirect('warning', URL . 'admin/ugyfelek', 'Az üdvözlő e-mail küldése sikertelen!');
}

alert_redirect('success', URL . 'admin/ugyfelek');
