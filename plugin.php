<?php
/**
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Ipin;

use DB;
use Route;
use Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Validator;
use XeFrontend;
use XeRegister;
use Xpressengine\Plugin\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    protected $table = 'user_ipin';

    public function boot()
    {
        $this->routes();
        $this->hooks();
        $this->extendValidation();
    }

    public function register()
    {
        app()->singleton('xe.plugin.ipin', function ($app) {
            return new Manager($app);
        });
    }

    protected function routes()
    {
        Route::fixed($this->getId(), function () {
            Route::get('pop', ['as' => 'plugin.ipin.pop', 'uses' => 'UserController@pop']);
            Route::get('callback', ['as' => 'plugin.ipin.callback', 'uses' => 'UserController@callback']);
        }, ['namespace' => 'Xpressengine\\Plugins\\Ipin\\Controllers']);

        Route::settings($this->getId(), function () {
            Route::get('/', ['as' => 'setting.plugin.ipin', 'uses' => 'SettingController@index']);
            Route::post('/', ['as' => 'setting.plugin.ipin', 'uses' => 'SettingController@store']);
        }, ['namespace' => 'Xpressengine\\Plugins\\Ipin\\Controllers']);
    }

    protected function hooks()
    {
        // 회원 가입 항목 추가
        intercept('XeUser@getRegisterForms', 'ipin::inject', function ($method, $token) {

            XeRegister::push(
                'user/register/form',
                'ipin',
                function ($data) {
                    XeFrontend::html('ipin.script')->content("
                    <script>
                        $('#__xe_btn-ipin').click(function () {
                            window.open('".route('plugin.ipin.pop')."', 'ipin', 'width=450,height=550');
                        });
                    </script>
                    ")->load();
                    return view('ipin::views.button');
                }
            );

            return $method($token);
        });

        // 회원 등록
        intercept('XeUser@create', 'ipin::certify', function ($method, $data, $token) {

            $ipinToken = app('session')->get('ipin_token');
            $dupInfo = app('session')->get('ipin_dup');

            if ($ipinToken !== request('ipin_token')) {
                throw new HttpException(400, '인증정보가 잘못되었습니다.');
            }
            if ($row = DB::table($this->table)->where('dupInfo', $dupInfo)->first()) {
                throw new HttpException(400, '본인 확인 정보로 가입된 내역이 존재합니다.');
            }

            $user = $method($data, $token);

            DB::table($this->table)->insert([
                'userId' => $user->getkey(),
                'dupInfo' => $dupInfo,
            ]);

            app('session')->forget(['ipin_token', 'ipin_dup']);

            return $user;
        });

        // 회원 탈퇴
        intercept('XeUser@leave', 'ipin::delete', function ($method, $userIds) {
            $userIds = (array)$userIds;

            DB::table($this->table)->whereIn('userId', $userIds)->delete();

            return $method($userIds);
        });
    }

    protected function extendValidation()
    {
        Validator::extend('ipin_cb_txt', function ($attribute, $value) {
            return preg_match('#[^0-9a-zA-Z+/=]#', $value);
        });
        Validator::replacer('ipin_cb_txt', function ($message, $attribute, $rule, $parameters) {
            return '입력값 확인이 필요합니다.';
//            return xe_trans('validation.mimes', ['attribute' => $attribute, 'values' => 'json']);
        });
    }

    public function install()
    {
        Schema::create($this->table, function ($table) {
            $table->increment('id');
            $table->string('userId', 36);
            $table->string('dupInfo');

            $table->index('userId');
        });

        app('files')->makeDirectory(app('xe.plugin.ipin')->getKeyDir(), 0755, true);
        app('xe.config')->set('ipin', ['code' => '']);
    }

    public function uninstall()
    {
        Schema::dropIfExists($this->table);

        app('files')->deleteDirectory(app('xe.plugin.ipin')->getKeyDir());
        app('xe.config')->removeByName('ipin');
    }

    public function getSettingsURI()
    {
        return route('setting.plugin.ipin');
    }
}
