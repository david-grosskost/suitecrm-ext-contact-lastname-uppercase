<?php

declare(strict_types=1);

namespace SuiteCrmExt\ContactLastnameUppercase;

// SuiteCRM loads only this file for the hook: the rule it applies comes along.
require_once __DIR__ . '/LastName.php';

/**
 * Before a contact is saved, whatever saves it (record view, import, API,
 * workflow, mass update): its last name in capitals.
 */
final class ContactSave
{
    /** Hook before_save of Contacts (legacy/Extension/…/LogicHooks/contact-lastname-uppercase.php). */
    public function beforeSave(\SugarBean $bean, string $event, mixed $arguments = null): void
    {
        $name = self::text($bean, 'last_name');
        if ($name === '') {
            return;
        }

        self::write($bean, 'last_name', LastName::upper($name));
    }

    private static function write(\SugarBean $bean, string $field, string $value): void
    {
        $bean->{$field} = $value;
    }

    private static function text(\SugarBean $bean, string $field): string
    {
        $value = $bean->{$field} ?? '';

        return is_string($value) ? $value : '';
    }
}
