<?php
/**
 * Manager.php
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Ipin;

use Illuminate\Support\Manager as AbstractManager;
use Xpressengine\Plugins\Ipin\Envs\Env;
use Xpressengine\Plugins\Ipin\Envs\Production;
use Xpressengine\Plugins\Ipin\Envs\Test;

class Manager extends AbstractManager
{
    protected $name = 'ipin';
    /**
     * @return Handler
     */
    public function createTestDriver()
    {
        return $this->handler(new Test);
    }

    /**
     * @return Handler
     */
    public function createProductionDriver()
    {
        return $this->handler(new Production($this->getConfig()->get('code')));
    }

    /**
     * @param Env $env
     * @return Handler
     */
    public function handler(Env $env)
    {
        return new Handler($env, $this->getKeyDir(), $this->getLogDir());
    }

    /**
     * @return string
     */
    public function getKeyDir()
    {
        return storage_path('app/ipin');
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return storage_path('logs');
    }

    /**
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->getConfig()->get('test') === true ? 'test' : 'production';
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->app['xe.config']->getOrNew($this->name);
    }

    /**
     * @param array $data
     * @return void
     */
    public function setConfigData($data)
    {
        $this->app['xe.config']->set($this->name, $data);
    }
}