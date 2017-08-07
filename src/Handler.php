<?php

/**
 * Handler.php
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

use Symfony\Component\Process\Process;
use Xpressengine\Plugins\Ipin\Envs\Env;

class Handler
{
    protected $bin;

    protected $env;

    protected $keyDir;

    protected $keyName = 'okname.key';

    protected $logDir;

    /**
     * Handler constructor.
     *
     * @param Env $env
     * @param string $keyDir
     * @param string $logDir
     */
    public function __construct(Env $env, $keyDir, $logDir)
    {
        $this->env = $env;
        $this->keyDir = $keyDir;
        $this->logDir = $logDir;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function begin()
    {
        $env = $this->getEnv();
        $cmd = [
            $this->bin()->getPathname(),
            $this->getKeyPathname(),
            $env->getCode(),
            '"0"',
            '"0"',
            $env->getEndpoint(),
            $this->logDir,
            'C'
        ];

        $output = $this->exec(implode(' ', $cmd));

        if ($output->fail()) {
            // status 127: 모듈실행 파일이 존재하지 않습니다.
            // status 126: 모듈실행 파일의 실행권한이 없습니다.
            // status -1: 모듈실행 파일의 실행권한이 없습니다. ncmd.exe의 IUSER 실행권한이 있는지 확인하여 주십시오.
            throw new \Exception($output->code() . ':' . implode('||', $output->errors()));
        }

        return [
            'pubkey' => $output->data(0),
            'signature' => $output->data(1),
            'current_time' => $output->data(2)
        ];
    }

    /**
     * @param string $pubkey
     * @param string $signature
     * @param string $encData
     * @return array
     * @throws \Exception
     */
    public function certify($pubkey, $signature, $encData)
    {
        $env = $this->getEnv();
        $cmd = [
            $this->bin()->getPathname(),
            $this->getKeyPathname(),
            $env->getCode(),
            $env->getEndpoint(),
            $pubkey,
            $signature,
            $encData,
            $this->logDir,
            'SU'
        ];

        $output = $this->exec(implode(' ', $cmd));

        if ($output->fail()) {
            $errCode = $output->code() <= 200 ? sprintf('B%03d', $output->code()) : sprintf('S%03d', $output->code());
            throw new \Exception('아이핀 본인확인 중 오류가 발생했습니다. 오류코드 : '.$errCode.'\\n\\n문의는 코리아크레딧뷰로 고객센터 02-708-1000 로 해주십시오.');
        }

        return array_combine([
            'dupInfo',	    //0
            'coinfo1',	    //1
            'coinfo2',	    //2
            'ciupdate',	    //3
            'virtualNo',	//4
            'cpCode',	    //5
            'realName', 	//6
            'cpReqNo',	    //7
            'age',	        //8
            'sex',	        //9
            'nationalInfo',	//10
            'birthDate',	//11
            'authInfo',	    //12
        ], array_slice($output->data(), 0, 13));
    }

    /**
     * @return Binary
     */
    protected function bin()
    {
        if (!$this->bin) {
            $this->bin = new Binary();
        }

        return $this->bin;
    }

    /**
     * @return string
     */
    protected function getKeyPathname()
    {
        return $this->keyDir . DIRECTORY_SEPARATOR . $this->keyName;
    }

    /**
     * @param string $cmd
     * @return Output
     */
    protected function exec($cmd)
    {
        $process = new Process($cmd);
        $process->run();

        return new Output($process);
    }

    /**
     * @return Env
     */
    public function getEnv()
    {
        return $this->env;
    }
}
