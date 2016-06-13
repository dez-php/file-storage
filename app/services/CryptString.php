<?php

namespace FileStorage\Services;

class CryptString
{

    private $baseCoder = null;

    private $setting = -1;

    /**
     * CryptString constructor.
     * @param BaseCoder $baseCoder
     * @param int $setting
     */
    public function __construct(BaseCoder $baseCoder, $setting = 0)
    {
        if (! extension_loaded('bcmath')) {
            throw new \RuntimeException('BCMath extension not loaded. Require for ' . CryptString::class);
        }

        $this->setBaseCoder($baseCoder)->setSetting($setting);
    }

    /**
     * @param null $data
     * @param null $secretKey
     * @return string
     */
    public function encode($data = null, $secretKey = null)
    {
        $secretKey = str_pad('', strlen($data), $this->hash($secretKey));
        $data = $data ^ $secretKey;

        $output = [];

        foreach (str_split($data, 3) as $chunk) {
            $tmp = unpack('H*', $this->randomSymbol() . $chunk);
            $tmp = $this->getBaseCoder()->encode(base_convert($tmp[1], 16, 10));
            $output[] = str_pad($tmp, 7, '0', STR_PAD_LEFT);
            unset($tmp);
        }

        return join('', $output);
    }

    /**
     * @param null $data
     * @return string
     */
    private function hash($data = null)
    {
        return md5($data);
    }

    /**
     * @return string
     */
    private function randomSymbol()
    {
        return chr(rand(ord('A'), ord('z')));
    }

    /**
     * @return BaseCoder
     */
    public function getBaseCoder()
    {
        return $this->baseCoder;
    }

    /**
     * @param BaseCoder $baseCoder
     * @return $this
     */
    public function setBaseCoder(BaseCoder $baseCoder)
    {
        $this->baseCoder = $baseCoder;
        return $this;
    }

    /**
     * @param null $data
     * @param null $secretKey
     * @return int|null|string
     */
    public function decode($data = null, $secretKey = null)
    {
        $output = null;

        foreach (str_split($data, 7) as $chunk) {
            $chunk = base_convert($this->getBaseCoder()->decode(ltrim($chunk, '0')), 10, 16);
            $output .= substr(pack('H*', $chunk), 1);
        }

        $secretKey = $this->hash($secretKey);
        $output = $output ^ str_pad('', mb_strlen($output) * 2, $secretKey);

        return $output;
    }

    /**
     * @return int
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param int $setting
     * @return static
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
        return $this;
    }

}