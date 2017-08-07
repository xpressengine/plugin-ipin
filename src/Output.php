<?php
/**
 * Output.php
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

class Output
{
    protected $process;

    /**
     * Output constructor.
     *
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->process->isSuccessful();
    }

    /**
     * @return bool
     */
    public function fail()
    {
        return !$this->success();
    }

    /**
     * @return int|null
     */
    public function code()
    {
        return $this->process->getExitCode();
    }

    /**
     * @param int|null $line
     * @return array|mixed
     */
    public function data($line = null)
    {
        $lines = explode("\n", $this->process->getOutput());

        return $this->outputLine($lines, $line);
    }

    /**
     * @param int|null $line
     * @return array|mixed
     */
    public function errors($line = null)
    {
        $lines = explode("\n", $this->process->getErrorOutput());

        return $this->outputLine($lines, $line);
    }

    /**
     * @param array $lines
     * @param int|null $line
     * @return array|mixed
     */
    protected function outputLine(array $lines, $line)
    {
        if ($line === null) {
            return $lines;
        }

        return $lines[$line];
    }
}
