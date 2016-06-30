<?php

namespace FileStorage\Services;

/**
 * Class BaseCoder
 * @package FileStorage\Services
 */
class BaseCoder
{

    const USE_UPPERCASE = 1;
    const USE_LOWERCASE = 2;
    const USE_NUMS = 4;
    const USE_NUMS_WO_ZERO = 8;
    const USE_SYMBOLS = 16;

    /**
     * @var array
     */
    private $map = [];

    /**
     * @var int
     */
    private $mapSize = 0;

    /**
     * BaseCoder constructor.
     * @param int $mode
     */
    public function __construct($mode = 0)
    {

        $this->map = array(-1);

        if ($mode & self::USE_NUMS) {
            $this->map = array_merge($this->map, range('0', '9'));
        } else if ($mode & self::USE_NUMS_WO_ZERO) {
            $this->map = array_merge($this->map, range('1', '9'));
        }

        if ($mode & self::USE_LOWERCASE) {
            $this->map = array_merge($this->map, range('a', 'z'));
        }

        if ($mode & self::USE_UPPERCASE) {
            $this->map = array_merge($this->map, range('A', 'Z'));
        }

        if ($mode & self::USE_SYMBOLS) {
            $this->map = array_merge($this->map, array('_', '-'));
        }

        unset($this->map[0]);

        $this->map = array_map('strval', $this->map);

        $this->mapSize = count($this->map);
    }

    /**
     * @return static
     */
    static public function instance()
    {
        static $instance;
        if (!$instance) $instance = new static(
            static::USE_LOWERCASE | static::USE_UPPERCASE | static::USE_NUMS
        );
        return $instance;
    }

    /**
     * @param array $customData
     * @return $this
     */
    public function addCustom(array $customData = array())
    {
        $this->map = array_merge($this->map, $customData);
        $this->mapSize = count($this->map);

        return $this;
    }

    /**
     * @return float
     */
    public function getRatio()
    {
        return round($this->mapSize / 10, 4);
    }

    /**
     * @param int $intData
     * @return int|string
     */
    public function encode($intData = 0)
    {
        $mapStr = join('', $this->map);
        $result = '';

        if (0 >= $intData) return 0;

        do {
            $result = $mapStr[bcmod($intData, $this->mapSize)] . $result;
            $intData = bcdiv($intData, $this->mapSize, 0);
            if ($intData == 0) break;
        } while (true);

        return $result;
    }

    /**
     * @param string $data
     * @return int|string
     */
    public function decode($data = '')
    {
        $length = strlen($data) - 1;
        $resultInt = 0;
        $mapStr = join('', $this->map);

        if (0 > $length) return $resultInt;

        $data = strrev($data);

        for ($i = 0; $i <= $length; $i++) {
            $symbol = $data[$i];
            $position = mb_strpos($mapStr, $symbol);
            $resultInt = bcadd($resultInt, bcmul($position, bcpow($this->mapSize, $i, 0), 0), 0);
        }

        return $resultInt;
    }

}