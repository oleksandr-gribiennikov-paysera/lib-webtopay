<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class WebToPay_PaymentMethodCountryTest extends TestCase
{
    public function testSetDefaultLanguage(): void
    {
        $paymentMethodCountry = new WebToPay_PaymentMethodCountry('lt', [
            'en' => 'Norway',
            'lt' => 'Norvegija',
        ], 'lt');

        $group = new WebToPay_PaymentMethodGroup('e-banking', [
            'en' => 'E-banking',
            'lt' => 'Elektroninė bankininkystė',
        ], 'lt');

        $paymentMethodCountry->addGroup($group);

        $paymentMethodCountry->setDefaultLanguage('en');

        $this->assertEquals('en', $paymentMethodCountry->getDefaultLanguage());
        $this->assertEquals('en', $paymentMethodCountry->getGroup('e-banking')->getDefaultLanguage());
    }

    public function getDataForGettingTitle(): iterable
    {
        yield 'language key exists' => [
            'translations' => [
                'en' => 'Norway',
                'lt' => 'Norvegija',
            ],
            'languageKey' => 'en',
            'expectedResult' => 'Norway',
        ];

        yield 'language key does not exists; default language translation exists' => [
            'translations' => [
                'en' => 'Norway',
                'lt' => 'Norvegija',
            ],
            'languageKey' => 'non-existent language key',
            'expectedResult' => 'Norvegija',
        ];

        yield 'no language key; no default language translation' => [
            'translations' => [
                'en' => 'Norway',
                'some country code' => 'Norvegija',
            ],
            'languageKey' => 'non-existent language key',
            'expectedResult' => 'lt',
        ];
    }

    /**
     * @dataProvider getDataForGettingTitle
     */
    public function testGetTitle(array $translations, string $languageKey, string $expectedResult): void
    {
        $paymentMethodCountry = new WebToPay_PaymentMethodCountry('lt', $translations, 'lt');

        $this->assertEquals($expectedResult, $paymentMethodCountry->getTitle($languageKey));
    }
}
