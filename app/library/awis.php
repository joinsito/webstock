<?php
namespace App\library;
/**
 * Makes a request to AWIS for site info.
 */
class awis {

    private $ActionName        = 'UrlInfo';
    private $ResponseGroupName = 'ContentData';
    private $ServiceHost      = 'awis.amazonaws.com';
    private $NumReturn         = 10;
    private $StartNum          = 1;
    protected static $SigVersion        = '2';
    protected static $HashAlgorithm     = 'HmacSHA256';

    public function __construct($accessKeyId, $secretAccessKey, $site = null) {
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
        $this->site = $site;
    }

    /**
     * Get site info from AWIS.
     */
    public function getUrlInfo() {
        $queryParams = $this->buildQueryParams();
        $sig = $this->generateSignature($queryParams);
        $url = 'http://' . $this->ServiceHost . '/?' . $queryParams .
            '&Signature=' . $sig;
        $ret = self::makeRequest($url);
        $info=self::parseResponse($ret);
        return $info;
    }

    /**
     * Get top sites from ATS
     */
    public function getTopSites($startNum, $numReturn, $countryCode) {
        // TODO
        $this->ActionName = "TopSites";
        $this->ResponseGroupName = "Country";
        $this->ServiceHost = "ats.amazonaws.com";
        $this->StartNum = $startNum;
        $this->NumReturn = $numReturn;
        $this->countryCode = $countryCode;
        $queryParams = $this->buildQueryParams();
        $sig = $this->generateSignature($queryParams);
        $url = 'http://' . $this->ServiceHost . '/?' . $queryParams .
            '&Signature=' . $sig;
        $ret = self::makeRequest($url);
        $sites = self::parseResponseTopSites($ret);
        return $sites;
    }

    /**
     * Builds current ISO8601 timestamp.
     */
    protected static function getTimestamp() {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * Builds query parameters for the request to AWIS.
     * Parameter names will be in alphabetical order and
     * parameter values will be urlencoded per RFC 3986.
     * @return String query parameters for the request
     */
    protected function buildQueryParams() {
        $params = array(
            'Action'            => $this->ActionName,
            'ResponseGroup'     => $this->ResponseGroupName,
            'AWSAccessKeyId'    => $this->accessKeyId,
            'Timestamp'         => self::getTimestamp(),
            'Count'             => $this->NumReturn,
            'Start'             => $this->StartNum,
            'SignatureVersion'  => self::$SigVersion,
            'SignatureMethod'   => self::$HashAlgorithm,
            'Url'               => $this->site
        );
        if (isset($this->countryCode)) {
            $params['CountryCode'] = $this->countryCode;
        }
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            $keyvalue[] = $k . '=' . rawurlencode($v);
        }
        return implode('&',$keyvalue);
    }

    /**
     * Makes request to AWIS
     * @param String $url   URL to make request to
     * @return String       Result of request
     */
    protected static function makeRequest($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Parses XML response from AWIS and displays selected data
     * @param String $response    xml response from AWIS
     */
    public static function parseResponse($response) {
        $xml = new \SimpleXMLElement($response,null,false,
            'http://awis.amazonaws.com/doc/2005-07-11');
        if($xml->count() && $xml->Response->UrlInfoResult->Alexa->count()) {
            $info = $xml->Response->UrlInfoResult->Alexa;
            $nice_array = array(
                'links' => (int)$info->ContentData->LinksInCount,
                'rank'           => (int)$info->TrafficData->Rank,
                'description'   => (string)$info->ContentData->SiteData->Description
            );
        }
        return $nice_array;
    }

    /**
     * Parses XML response from AWIS and returns data
     * @param String $response    xml response from AWIS
     */
    public static function parseResponseTopSites($response) {
        $xml = new \SimpleXMLElement($response,null, false,
            'http://ats.amazonaws.com/doc/2005-11-21');
        if($xml->count() && $xml->Response->TopSitesResult->Alexa->TopSites->Country->Sites->count()) {
            foreach ($xml->Response->TopSitesResult->Alexa->TopSites->Country->Sites->Site as $site) {
                $sites[] =  ['url' => (string)$site->DataUrl,
                            'rank' => (int)$site->Country->Rank,
                            'globalrank' => (int)$site->Global->Rank,
                            'reach' => (float)$site->Country->Reach->PerMillion,
                            'pageviewsmillion' => (float)$site->Country->PageViews->PerMillion,
                            'pageviewsuser' => (float)$site->Country->PageViews->PerUser
                            ];
            }
        }
        return $sites;
    }


    /**
     * Generates an HMAC signature per RFC 2104.
     *
     * @param String $url       URL to use in createing signature
     */
    protected function generateSignature($url) {
        $sign = "GET\n" . strtolower($this->ServiceHost) . "\n/\n". $url;
        $sig = base64_encode(hash_hmac('sha256', $sign, $this->secretAccessKey, true));
        return rawurlencode($sig);
    }

}