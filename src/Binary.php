<?php
/**
 * Binary.php
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


class Binary
{
    protected $path;

    protected $names = [
        'unixX86' => 'okname',
        'unixX64' => 'okname_x64',
        'winX86' => 'okname.exe',
        'winX64' => 'oknamex64.exe',
    ];

    protected $winExt = 'exe';

    /**
     * Binary constructor.
     *
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        $this->path = $path ?: realpath(__DIR__ . '/../bin');
    }

    /**
     * @return string
     */
    public function getPathname()
    {
        if ($this->isWin()) {
            $name = $this->isX64() ? $this->names['winX64'] : $this->names['winX86'];
        } else {
            $name = $this->isX64() ? $this->names['unixX64'] : $this->names['unixX86'];
        }

        return $this->path . '/' . $name;
    }

    /**
     * @return bool
     */
    protected function isWin()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * @return bool
     */
    protected function isX64()
    {
        return PHP_INT_MAX !== 2147483647;
    }
}
