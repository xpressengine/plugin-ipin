<?php

/**
 * UserController.php
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

class UserController extends Controller
{
    public function pop()
    {
        $ipin = app('xe.plugin.ipin');
        $data = $ipin->begin();

        XePresenter::htmlRenderPopup();
        return XePresenter::make('ipin::views.form', [
            'env' => $ipin->getEnv(),
            'data' => $data,
        ]);
    }

    public function callback(Request $request)
    {
        $this->extendValidation();
        // require???
        $this->validate($request, [
            'encPsnlInfo' => 'ipin_cb_txt',
            'WEBPUBKEY' => 'ipin_cb_txt',
            'WEBSIGNATURE' => 'ipin_cb_txt',
        ]);

        $data = app('xe.plugin.ipin')->certify(
            $request->get('WEBPUBKEY'),
            $request->get('WEBSIGNATURE'),
            $request->get('encPsnlInfo')
        );

        app('session')->put('ipin_dup', md5($data['dupInfo']));
        app('session')->put('ipin_token', $ipin_token = md5($data['cpReqNo']));

        return XePresenter::make('ipin::views.callback', [
            'ipin_token' => $ipin_token,
        ]);
    }

    protected function extendValidation()
    {
        $this->getValidationFactory()->extend('ipin_cb_txt', function ($attribute, $value) {
            return preg_match('#[^0-9a-zA-Z+/=]#', $value);
        });
        $this->getValidationFactory()->replacer('ipin_cb_txt', function ($message, $attribute, $rule, $parameters) {
            return '입력값 확인이 필요합니다.';
//            return xe_trans('validation.mimes', ['attribute' => $attribute, 'values' => 'json']);
        });
    }
}
