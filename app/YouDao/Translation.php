<?php


namespace App\YouDao;


class Translation
{

    protected $word;

    public function __construct($word)
    {
        $this->word = $word;
    }

    public function downloadSpeakFile($wordBasic)
    {
        $word = $this->word;
        $ukSpeechPath = public_path('WordAudio/' . $word . '-uk.mp3');
        $usSpeechPath = public_path('WordAudio/' . $word . '-us.mp3');
        file_put_contents($ukSpeechPath, file_get_contents($wordBasic['uk-speech']));
        file_put_contents($usSpeechPath, file_get_contents($wordBasic['us-speech']));
        $ukSpeechUrl = '/WordAudio/' . $word . '-uk.mp3';
        $usSpeechUrl = '/WordAudio/' . $word . '-us.mp3';
        return [$ukSpeechUrl,$usSpeechUrl];
    }

    public function getWordInfo()
    {
        $q = $this->word;
        $appKey = '17e503d131b1b61d';
        $secKey = 'CDVMpsQUFYbfBNURcVfdQEY02j6oOoVt';

        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://openapi.youdao.com',
            // You can set any number of default request options.
            'timeout' => 5.0,
            'verify' => false
        ]);
        $salt = $this->create_guid();
        $curtime = strtotime("now");
        $signStr = $appKey . $this->truncate($q) . $salt . $curtime . $secKey;
        $sign = hash('sha256', $signStr);
        $args['sign'] = hash("sha256", $signStr);
        $ret = $client->post('/api', [
            'form_params' => [
                'q' => $q,
                'form' => 'en',
                'to' => 'zh-CHS',
                'appKey' => $appKey,
                'salt' => $salt,
                'sign' => $sign,
                'signType' => 'v3',
                'curtime' => $curtime
            ]
        ]);

        return $ret->getBody()->getContents();

    }

    public function truncate($q)
    {
        $len = $this->abslength($q);
        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }


    // uuid generator
    public function create_guid()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }

    public function create_guid_section($characters)
    {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }

    public function abslength($str)
    {
        if (empty($str)) {
            return 0;
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, 'utf-8');
        } else {
            preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }
    }


    public function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }


    public function convert(&$args)
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data .= "$key=" . rawurlencode($val) . "&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}