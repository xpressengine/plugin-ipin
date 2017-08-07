<?php
/**
 * Env.php
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

namespace Xpressengine\Plugins\Ipin\Envs;

abstract class Env
{
    protected $code;

    protected $idpUrl;

    protected $endpoint;

    protected $action;

    public function getCode()
    {
        return $this->code;
    }

    public function getIdpUrl()
    {
        return $this->idpUrl;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getAction()
    {
        return $this->action;
    }
}
