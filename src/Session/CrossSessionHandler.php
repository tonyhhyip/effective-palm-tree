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

namespace Laravel\Rich\Session;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Redis\Database;

class CrossSessionHandler implements \SessionHandlerInterface
{

    protected $redis;

    /**
     * CrossSessionHandler constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app, Database $database)
    {
        $this->redis = $database->connection($app['config']['session.common.connection']);
    }
    
    public function open($savePath, $sessionName)
    {
        return true;
    }


    public function close()
    {
        return true;
    }

    public function read($sessionId)
    {

    }

    public function write($sessionId, $data)
    {
        $this->redis->set($this->getKey($sessionId), $data);
    }

    public function destroy($sessionId)
    {
        $this->redis->del($this->getKey($sessionId));
    }

    public function gc($lifetime)
    {
        return true;
    }

    protected function getKey($id)
    {
        return 'session:' . $id;
    }
}