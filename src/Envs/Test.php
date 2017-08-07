<?php
/**
 * Test.php
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

class Test extends Env
{
    protected $code = 'P00000000000';

    protected $idpUrl = 'https://tmpin.ok-name.co.kr:5443/tis/ti/POTI90B_SendCertInfo.jsp';

    protected $endpoint = 'http://twww.ok-name.co.kr:8888/KcbWebService/OkNameService';

    protected $action = 'https://tmpin.ok-name.co.kr:5443/tis/ti/POTI01A_LoginRP.jsp';
}
