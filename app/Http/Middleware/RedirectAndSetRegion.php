<?php

namespace App\Http\Middleware;

use Canopy\Ecommerce\Models\Currency;
use Closure;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Http\Request;
use MaxMind\Db\Reader\InvalidDatabaseException;

class RedirectAndSetRegion
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = 'customer')
    {
        $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');
        //$record = $reader->country($request->getClientIp());

        $matchedCountries = [
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

        try {
            $record = $reader->country($request->getClientIp());
            $country = $record->country->isoCode ?? 'GB';
            $currency = $matchedCountries[$country] ?? 'GBP';
        } catch (AddressNotFoundException|InvalidDatabaseException $e) {
            $currency = 'GBP';
        }
        if (!session('currency')) {
            session(['currency' => $currency]);
        }


        return $next($request);
    }
}
