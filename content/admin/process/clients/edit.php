<?php
$c = new Client($_POST['id']);
$organization = $c->getType() == 2;

if (!$organization) {
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
} else {
    $name = $_POST['name'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $registration_number = $_POST['registration_number'];
    $tax_number = $_POST['tax_number'];
    $company_form = mb_strtoupper($_POST['company_form']);
    $contact_lastname = $_POST['lastname'];
    $contact_firstname = $_POST['firstname'];
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
$cid = $c->getId();

// Ellenőrizzük az e-mail cím meglétét az adatbázisban
if ($stmt = $con->prepare('SELECT id FROM accounts WHERE email = ? AND client_id != ?')) {
    $stmt->bind_param('si', $email, $cid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        alert_redirect('error', URL . 'admin/ugyfelek', 'Az e-mail cím már foglalt!');
    }
    $stmt->close();
}

// Frissítjük az ügyfelet
if ($stmt = $con->prepare('UPDATE `clients` SET `name` = ?, `address` = ?, `tax_number` = ?, `registration_number` = ?, `company_form` = ?, `contact_lastname` = ?, `contact_firstname` = ?, `contact_email` = ?, `contact_phone` = ? WHERE `id` = ?')) {
    $stmt->bind_param('sssssssssi', $name, $fulladdress, $tax_number, $registration_number, $company_form, $contact_lastname, $contact_firstname, $email, $phone, $cid);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/ugyfelek/adatlap/d/' . $cid);
    }
    $stmt->close();
}

if ($stmt = $con->prepare('UPDATE `accounts` SET `email` = ? WHERE `client_id` = ?')) {
    $stmt->bind_param('si', $email, $cid);
    $stmt->execute();
    $stmt->close();
}

alert_redirect('success', URL . 'admin/ugyfelek/adatlap/d/' . $cid);
