<?php
/**
 * SettingController.php
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

namespace Xpressengine\Plugins\Ipin\Controllers;

use App\Http\Controllers\Controller;
use XePresenter;
use Xpressengine\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return XePresenter::make('ipin::views.setting', [
            'config' => app('xe.plugin.ipin')->getConfig()
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->get('test')) {
            $this->validate($request, ['code' => 'required']);
        }

        app('xe.plugin.ipin')->setConfigData($request->only(['test', 'code']));

        return redirect()->back();
    }
}
