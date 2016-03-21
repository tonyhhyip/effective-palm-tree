<?php
/**
 * Laravel EnRich
 *
 * Copyright (C) Tony Yip 2016.
 *
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software
 * and associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS",
 * WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category Laravel EnRich
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/MIT MIT License
 */

namespace Laravel\Rich\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class CrossDomain
 * @package Laravel\Rich\Middleware
 *
 * A Middleware for CORS
 *
 * @see https://developer.mozilla.org/en-GB/docs/Web/HTTP/Access_control_CORS
 */
class CrossDomain implements MiddlewareInterface
{

    /**
     * Allowed Sites for CORS
     * @var array
     */
    protected $allowSite = [];

    /**
     * Allowed Method for CORS
     * @var array
     */
    protected $allowMethod = ['GET', 'HEAD', 'POST'];

    /**
     * Allowed Header for CORS
     * @var array
     */
    protected $allowHeaders = [];

    /**
     * Value of Access-Control-Max-Age for Pre-flighted Request
     * @var int
     */
    protected $maxAge = 1728000;

    /**
     * Value for Access-Control-Allow-Credentials
     * @var bool
     */
    protected $allowCredentials = true;

    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has('Origin')) {


            if (!$this->authAccess($request)) {
                return Response::make('Unauthorized', 401);
            }

            /**
             * @var \Illuminate\Http\Response
             */
            $response = $next($request);
            $response->header('Access-Control-Allow-Origin', $request->header('Origin'));
            $response->header('Access-Control-Allow-Credentials', $this->allowCredentials);

            if ($request->method() === 'OPTIONS') {
                $methods = implode(',', $this->allowMethod);
                $response->header('Access-Control-Allow-Methods', $methods);

                $headers = implode(',', $this->allowHeaders);
                $response->header('Access-Control-Allow-Headers', $headers);

                $response->header('Access-Control-Max-Age', $this->maxAge);
            }

            return $response;
        } else {
            return $next($request);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function authAccess(Request $request)
    {
        $origin = $request->header('Origin');
        if ($this->allowSite !== '*' && !in_array($origin, $this->allowSite)) {
            return false;
        }

        if (!in_array($request->method(), $this->allowMethod)) {
            return false;
        }

        return true;
    }
}