<?php
$email = [
    'subject' => 'Számla készült',
    'body' => '<p><strong>Kedves {{name}}!</strong></p>

<p>Tájékoztatunk, hogy új számla készült a fiókodhoz.</p>

<p><strong>Számla száma:</strong> {{invoice_number}}<br>
<strong>Kibocsátás dátuma:</strong> {{date}}<br>
<strong>Összeg:</strong> {{amount}}</p>

<p>A számlát az <strong>Okapi Ügyfélkapuba</strong> bejelentkezve, vagy az alábbi gombra kattintva töltheted le:</p>

<p>
    <a href="{{url}}" style="display: inline-block; background-color: #FF9E00; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
        📄 Számla letöltése
    </a>
</p>

<p>Amennyiben kérdésed van, keress minket bizalommal az <a href="mailto:info@okapiweb.hu" style="color: #FF9E00; text-decoration: none;">info@okapiweb.hu</a> e-mail címen.</p>

<p><strong>Köszönjük, hogy az Okapi Webdesignt használod!</strong></p>
'
];
