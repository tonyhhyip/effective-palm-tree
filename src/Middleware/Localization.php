<?php
/**
 * HHVM
 *
 * Copyright (C) Tony Yip 2015.
 *
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Laravel\Rich\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Localization implements MiddlewareInterface
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        $default = $request->session()->get('locale', 'en');
        $locale = $request->input('locale', $default);
        $locale = Str::substr($locale, 0, 2);
        $request->session()->put('locale', $locale);
        $this->app->setLocale($locale);

        return $next($request);
    }
}