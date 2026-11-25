<?php

declare(strict_types=1);

namespace SuiteCrmExt\ContactLastnameUppercase\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\ContactLastnameUppercase\ContactSave;

require_once __DIR__ . '/fixtures/SugarBean.php';

final class ContactSaveTest extends TestCase
{
    /**
     * The arguments SuiteCRM 8.10 passes to before_save (SugarBean::save), and none.
     *
     * @return iterable<string, array{mixed}>
     */
    public static function arguments(): iterable
    {
        yield 'enregistrement' => [['check_notify' => false]];
        yield 'avec notification' => [['check_notify' => true]];
        yield 'sans arguments' => [null];
    }

    #[DataProvider('arguments')]
    public function testLastNameInCapitals(mixed $arguments): void
    {
        $bean = self::contact('Marie', 'dupont');

        (new ContactSave())->beforeSave($bean, 'before_save', $arguments);

        self::assertSame('DUPONT', self::field($bean, 'last_name'));
        self::assertSame('Marie', self::field($bean, 'first_name'), 'le prénom ne change pas');
    }

    public function testEncodedBeforeSave(): void
    {
        // purify_html has run on the bean before before_save.
        $bean = self::contact('Hélène', 'lef&egrave;vre &amp;amp; o&#039;brien');

        (new ContactSave())->beforeSave($bean, 'before_save', ['check_notify' => false]);

        // Encoded again, as SuiteCRM holds it until from_html writes "LEFÈVRE & O'BRIEN".
        self::assertSame('LEFÈVRE &amp; O&#039;BRIEN', self::field($bean, 'last_name'));
    }

    public function testImportedContact(): void
    {
        $bean = self::contact('jean', 'martin');

        (new ContactSave())->beforeSave($bean, 'before_save');

        self::assertSame('MARTIN', self::field($bean, 'last_name'));
        self::assertSame('jean', self::field($bean, 'first_name'));
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function noName(): iterable
    {
        yield 'vide' => [''];
        yield 'absent' => [null];
        yield 'pas du texte' => [42];
    }

    #[DataProvider('noName')]
    public function testNoNameLeftAsIs(mixed $name): void
    {
        $bean = new \SugarBean();
        if ($name !== null) {
            self::set($bean, 'last_name', $name);
        }

        (new ContactSave())->beforeSave($bean, 'before_save', null);

        self::assertSame($name, self::field($bean, 'last_name'));
    }

    private static function contact(string $firstName, string $lastName): \SugarBean
    {
        $bean = new \SugarBean();
        self::set($bean, 'first_name', $firstName);
        self::set($bean, 'last_name', $lastName);

        return $bean;
    }

    private static function set(\SugarBean $bean, string $field, mixed $value): void
    {
        $bean->{$field} = $value;
    }

    private static function field(\SugarBean $bean, string $field): mixed
    {
        return $bean->{$field} ?? null;
    }
}
