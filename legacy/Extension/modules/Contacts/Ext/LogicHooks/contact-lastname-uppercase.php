<?php

// Nom de famille des contacts en majuscules (extension
// contact-lastname-uppercase): puts the last name of a contact in capitals
// before it is saved.

$hook_version = 1;

/** @var array<string, list<array{int, string, string, string, string}>> $hook_array Set by SuiteCRM */
$hook_array['before_save'][] = [
    10,
    'contact-lastname-uppercase : nom de famille en majuscules',
    'custom/include/contact-lastname-uppercase/ContactSave.php',
    'SuiteCrmExt\ContactLastnameUppercase\ContactSave',
    'beforeSave',
];
