<?php

// Install script of contact-lastname-uppercase: puts the last name of the
// contacts already there in capitals, once, at the first installation (post_install
// of the Module Loader package, see suitecrm-devkit, make package@build), in
// SuiteCRM, as the administrator. From then on the hook before_save does it
// at each save.
// Written straight to the table, not through save(): the date of
// modification, the history and the workflows of the contacts are left alone.

// Its message is returned, not printed (echo is refused by the scanner of
// the Module Loader): the caller shows it.

declare(strict_types=1);

use SuiteCrmExt\ContactLastnameUppercase\LastName;

require_once __DIR__ . '/../legacy/include/contact-lastname-uppercase/LastName.php';

$db = DBManagerFactory::getInstance();
// Deleted contacts too: restored, they come back in capitals.
$result = $db->query("SELECT id, last_name FROM contacts WHERE last_name IS NOT NULL AND last_name <> ''");

$changed = 0;
while (is_array($row = $db->fetchByAssoc($result, false))) {
    $name = (string) $row['last_name'];
    $upper = LastName::upper($name);
    if ($upper === $name) {
        continue;
    }
    $db->query('UPDATE contacts SET last_name = ' . $db->quoted($upper) . ' WHERE id = ' . $db->quoted((string) $row['id']));
    ++$changed;
}

return "contact-lastname-uppercase : {$changed} nom(s) de contact mis en majuscules.";
