<?php

namespace Canopy\Ecommerce\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;

class CustomerLocationService
{
    public const DEFAULT_COUNTRY = 'GB';
    public const DEFAULT_CURRENCY = 'GBP';
    public const ALLOWED_COUNTRIES = [
        'US' => 'USD',
        'RU' => 'USD',
        'CN' => 'USD',
        'NZ' => 'USD',
        'AU' => 'USD',
        'CA' => 'USD',
        'GB' => 'GBP',
        'IE' => 'GBP',
        'DE' => 'EUR',
        'NL' => 'EUR',
        'FR' => 'EUR',
        'GE' => 'EUR',
        'ES' => 'EUR',
        'SK' => 'EUR',
        'CZ' => 'EUR',
        'PL' => 'EUR',
        'MK' => 'EUR',
        'HU' => 'EUR',
        'BD' => 'BDT'
    ];

    private $geoIp;

    public function __construct()
    {
        $this->geoIp = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');
    }

    public function getLocationFromIp(string $ip): string
    {
        try {
            $record = $this->geoIp->country($ip);
            $country = $record->country->isoCode ?? self::DEFAULT_COUNTRY;
            return self::ALLOWED_COUNTRIES[$country] ?? self::DEFAULT_CURRENCY;
        } catch (AddressNotFoundException|InvalidDatabaseException $e) {
            return self::DEFAULT_CURRENCY;
        }
    }
}
