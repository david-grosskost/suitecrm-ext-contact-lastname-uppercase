<?php

declare(strict_types=1);

namespace SuiteCrmExt\ContactLastnameUppercase;

/**
 * The last name of a contact in capitals: "lefèvre" → "LEFÈVRE",
 * "de la Fontaine" → "DE LA FONTAINE".
 */
final class LastName
{
    /**
     * Before save, SuiteCRM holds the value HTML-encoded, up to twice
     * (purify_html: "è" → "&egrave;", "&" → "&amp;amp;"); it decodes it
     * again when it writes the row (from_html). A few rounds are enough.
     */
    private const DECODE_ROUNDS = 3;

    /**
     * The name in capitals, accents kept, as plain text: what the database
     * stores. Takes the value plain (the column) or HTML-encoded (the bean
     * before save): an entity left as it is would keep its letter in lower
     * case ("&egrave;"), or no longer read as one ("&AMP;").
     */
    public static function upper(string $name): string
    {
        for ($round = 0; $round < self::DECODE_ROUNDS; ++$round) {
            $decoded = html_entity_decode($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($decoded === $name) {
                break;
            }
            $name = $decoded;
        }

        return mb_strtoupper($name, 'UTF-8');
    }
}
