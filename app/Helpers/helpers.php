<?php

if (!function_exists('setting')) {
    /**
     * Get or set setting values
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\Akaunting\Setting\Setting::class);
        }

        if (is_array($key)) {
            return app(\Akaunting\Setting\Setting::class)->set($key);
        }

        return app(\Akaunting\Setting\Setting::class)->get($key, $default);
    }
}

if (!function_exists('active_route')) {
    /**
     * Check if current route matches pattern
     *
     * @param string|array $routes
     * @return bool
     */
    function active_route($routes)
    {
        if (!is_array($routes)) {
            $routes = [$routes];
        }

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format number as currency
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    function format_currency($amount, $currency = 'VND')
    {
        return number_format($amount, 0, ',', '.') . ' ' . $currency;
    }
}
