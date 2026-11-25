<?php

declare(strict_types=1);

namespace SuiteCrmExt\ContactLastnameUppercase\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\ContactLastnameUppercase\LastName;

final class LastNameTest extends TestCase
{
    /**
     * Names as typed or as SuiteCRM holds them before save, and in capitals.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function names(): iterable
    {
        yield 'tapé en minuscules' => ['dupont', 'DUPONT'];
        yield 'accents conservés' => ['lefèvre', 'LEFÈVRE'];
        yield 'autres accents' => ['éléonore çàùôï', 'ÉLÉONORE ÇÀÙÔÏ'];
        yield 'particules' => ['de la Fontaine', 'DE LA FONTAINE'];
        yield 'nom composé' => ['martin-durand', 'MARTIN-DURAND'];
        yield 'déjà en majuscules' => ['DURAND', 'DURAND'];
        yield 'apostrophe' => ["o'brien", "O'BRIEN"];
        yield 'esperluette' => ['dupont & fils', 'DUPONT & FILS'];
        // As purify_html leaves them before save (SugarBean::cleanBean).
        yield 'accent encodé avant enregistrement' => ['lef&egrave;vre', 'LEFÈVRE'];
        yield 'apostrophe encodée avant enregistrement' => ['o&#039;connor', "O'CONNOR"];
        yield 'esperluette encodée deux fois' => ['martin &amp;amp; fils', 'MARTIN & FILS'];
        yield 'entité hexadécimale' => ['d&#x27;arc', "D'ARC"];
        yield 'vide' => ['', ''];
        yield 'espaces gardés' => ['  dupont ', '  DUPONT '];
        yield 'chiffres' => ['louis 14', 'LOUIS 14'];
    }

    #[DataProvider('names')]
    public function testUpper(string $name, string $expected): void
    {
        self::assertSame($expected, LastName::upper($name));
    }

    public function testTwiceIsOnce(): void
    {
        $upper = LastName::upper('o&#039;brien &amp;amp; l&egrave;vre');

        self::assertSame($upper, LastName::upper($upper));
    }
}
