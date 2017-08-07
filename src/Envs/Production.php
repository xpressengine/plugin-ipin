<?php
/**
 * Production.php
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


class Production extends Env
{
    protected $code;

    protected $idpUrl = 'https://ipin.ok-name.co.kr/tis/ti/POTI90B_SendCertInfo.jsp';

    protected $endpoint = 'http://www.ok-name.co.kr/KcbWebService/OkNameService';

    protected $action = 'https://ipin.ok-name.co.kr/tis/ti/POTI01A_LoginRP.jsp';

    public function __construct($code)
    {
        $this->code = $code;
    }
}
