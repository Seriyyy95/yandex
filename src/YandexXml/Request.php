<?php

namespace AntonShevchuk\YandexXml;

use AntonShevchuk\YandexXml\Exceptions\YandexXmlException;
use AntonShevchuk\YandexXml\Exceptions\BadDataException;

/**
 * Class YandexXml for work with Yandex.XML
 *
 * @author   Anton Shevchuk <AntonShevchuk@gmail.com>
 * @author   Mihail Bubnov <bubnov.mihail@gmail.com>
 * @link     http://anton.shevchuk.name
 * @link     http://yandex.hohli.com
 *
 * @package  YandexXml
 */
class Request
{
    /**
     * Base url to service
     */
    protected $baseUrl = 'https://yandex.ru/search/xml';

    /**
     * User
     *
     * @var string
     */
    protected $user;

    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * Query
     *
     * @var string
     */
    protected $query;

    /**
     * Request
     *
     * @var string
     */
    protected $request;

    /**
     * Host
     *
     * @var string
     */
    protected $host;

    /**
     * Site
     *
     * @var string
     */
    protected $site;

    /**
     * Domain
     *
     * @var string
     */
    protected $domain;

    /**
     * Catalog ID
     *
     * @see http://search.yaca.yandex.ru/cat.c2n
     * @var integer
     */
    protected $cat;

    /**
     * Geo ID
     *
     * @see http://search.yaca.yandex.ru/geo.c2n
     * @var integer
     */
    protected $geo;

    /**
     * Theme name
     *
     * @see http://help.yandex.ru/site/?id=1111797
     * @var integer
     */
    protected $theme;

    /**
     * lr
     *
     * @var integer
     */
    protected $lr;

    /**
     * Localization
     *  - ru - russian
     *  - uk - ukrainian
     *  - be - belarusian
     *  - kk - kazakh
     *  - tr - turkish
     *  - en - english
     *
     * @var string
     */
    const L10N_RUSSIAN = 'ru';
    const L10N_UKRAINIAN = 'uk';
    const L10N_BELARUSIAN = 'be';
    const L10N_KAZAKH = 'kk';
    const L10N_TURKISH = 'tr';
    const L10N_ENGLISH = 'en';

    protected $l10n;

    /**
     * Content filter
     *  - strict
     *  - moderate
     *  - none
     *
     * @var string
     */
    const FILTER_STRICT = 'strict';
    const FILTER_MODERATE = 'moderate';
    const FILTER_NONE = 'none';

    protected $filter;

    /**
     * Number of page
     *
     * @var integer
     */
    protected $page = 0;

    /**
     * Number of results per page
     *
     * @var integer
     */
    protected $limit = 10;

    /**
     * Sort By   'rlv' || 'tm'
     *
     * @see https://tech.yandex.ru/xml/doc/dg/concepts/post-request-docpage/
     * @var string
     */
    const SORT_RLV = 'rlv'; // relevance
    const SORT_TM = 'tm';  // time modification

    protected $sortBy = 'rlv';

    /**
     * Group By  '' || 'd'
     *
     * @see https://tech.yandex.ru/xml/doc/dg/concepts/post-request-docpage/
     * @var string
     */
    const GROUP_DEFAULT = '';
    const GROUP_SITE = 'd'; // group by site

    protected $groupBy = '';

    /**
     * Group mode   'flat' || 'deep' || 'wide'
     *
     * @var string
     */
    const GROUP_MODE_FLAT = 'flat';
    const GROUP_MODE_DEEP = 'deep';
    const GROUP_MODE_WIDE = 'wide';

    protected $groupByMode = 'flat';

    /**
     * Options of search
     *
     * @var array
     */
    protected $options = [
        'maxpassages' => 2,    // from 2 to 5
        'max-title-length' => 160, //
        'max-headline-length' => 160, //
        'max-passage-length' => 160, //
        'max-text-length' => 640, //
    ];

    /**
     * Proxy params
     * Default - no proxy
     *
     * @var array
     */
    protected $proxy = [
        'host' => '',
        'port' => 0,
        'user' => '',
        'pass' => ''
    ];

    /**
     * __construct
     *
     * @param  string $user
     * @param  string $key
     */
    public function __construct($user, $key)
    {
        $this->user = $user;
        $this->key = $key;
    }

    /**
     * Set Base URL
     *
     * @param string $baseUrl
     *
     * @return Request|string
     */
    public function baseUrl($baseUrl = null)
    {
        return is_null($baseUrl) ? $this->baseUrl : $this->setBaseUrl($baseUrl);
    }

    /**
     * Set Base URL
     *
     * @param string $baseUrl
     *
     * @return Request
     */
    protected function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Query string
     *
     * @param  string $query
     *
     * @return Request|string
     */
    public function query($query = null)
    {
        return is_null($query) ? $this->query : $this->setQuery($query);
    }

    /**
     * Set query
     *
     * @param  string $query
     *
     * @return Request
     */
    protected function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Page
     *
     * @param  integer $page
     *
     * @return Request|integer
     */
    public function page($page = null)
    {
        return is_null($page) ? $this->page : $this->setPage($page);
    }

    /**
     * Page number
     *
     * @param  integer $page
     *
     * @return Request
     */
    protected function setPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }

    /**
     * Limit
     *
     * @param  integer $limit
     *
     * @return Request|integer
     */
    public function limit($limit = null)
    {
        return is_null($limit) ? $this->limit : $this->setLimit($limit);
    }

    /**
     * Set limit
     *
     * @param  integer $limit
     *
     * @return Request
     */
    protected function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * Host
     *
     * @param  string $host
     *
     * @return Request|string
     */
    public function host($host = null)
    {
        return is_null($host) ? $this->host : $this->setHost($host);
    }

    /**
     * Set host
     *
     * @param  string $host
     *
     * @return Request
     */
    protected function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Site
     *
     * @param  string $site
     *
     * @return Request|string
     */
    public function site($site = null)
    {
        return is_null($site) ? $this->site : $this->setSite($site);
    }

    /**
     * Set site
     *
     * @param  string $site
     *
     * @return Request
     */
    protected function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * Domain
     *
     * @param  string $domain
     *
     * @return Request|string
     */
    public function domain($domain = null)
    {
        return is_null($domain) ? $this->domain : $this->setDomain($domain);
    }

    /**
     * Set domain
     *
     * @param  string $domain
     *
     * @return Request
     */
    protected function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Cat
     *
     * @param  integer $cat
     *
     * @return Request|integer
     */
    public function cat($cat = null)
    {
        return is_null($cat) ? $this->cat : $this->setCat($cat);
    }

    /**
     * Set cat
     *
     * @param  integer $cat
     *
     * @return Request
     */
    protected function setCat($cat)
    {
        $this->cat = (int)$cat;
        return $this;
    }

    /**
     * Geo
     *
     * @param  integer $geo
     *
     * @return Request|integer
     */
    public function geo($geo = null)
    {
        return is_null($geo) ? $this->geo : $this->setGeo($geo);
    }

    /**
     * Set geo
     *
     * @param  integer $geo
     *
     * @return Request
     */
    protected function setGeo($geo)
    {
        $this->geo = (int)$geo;
        return $this;
    }

    /**
     * Theme
     *
     * @param  string $theme
     *
     * @return Request|string
     */
    public function theme($theme = null)
    {
        return is_null($theme) ? $this->theme : $this->setTheme($theme);
    }

    /**
     * Set theme
     *
     * @param  integer $theme
     *
     * @return Request
     */
    protected function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * lr
     *
     * @param  integer $lr
     *
     * @return integer|Request
     */
    public function lr($lr = null)
    {
        return is_null($lr) ? $this->lr : $this->setLr($lr);
    }

    /**
     * Set lr
     *
     * @param  integer $lr
     *
     * @return Request
     */
    protected function setLr($lr)
    {
        $this->lr = $lr;
        return $this;
    }

    /**
     * Set/Get Localization
     *
     * @param  string $l10n
     *
     * @return Request
     */
    public function l10n($l10n = null)
    {
        return is_null($l10n) ? $this->l10n : $this->setL10n($l10n);
    }

    /**
     * Set localization
     *
     * @param  string $l10n
     *
     * @return Request
     */
    protected function setL10n($l10n)
    {
        $this->l10n = $l10n;
        return $this;
    }

    /**
     * Set/Get Filter
     *
     * @param  string $filter
     *
     * @return Request
     */
    public function filter($filter = null)
    {
        return is_null($filter) ? $this->filter : $this->setFilter($filter);
    }

    /**
     * Set Filter
     *
     * @param  string $filter
     *
     * @return Request
     */
    protected function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Sort by ..
     *
     * @param  string $sortBy
     *
     * @return Request|string
     */
    public function sortBy($sortBy = null)
    {
        return is_null($sortBy) ? $this->sortBy : $this->setSortBy($sortBy);
    }

    /**
     * Set sort by
     *
     * @param  string $sortBy
     *
     * @return Request
     */
    protected function setSortBy($sortBy)
    {
        if ($sortBy === self::SORT_RLV || $sortBy === self::SORT_TM) {
            $this->sortBy = $sortBy;
            return $this;
        }
        throw new \InvalidArgumentException();
    }


    /**
     * Setup group by
     *
     * @param  string $groupBy
     * @param  string $mode
     *
     * @return Request|string
     */
    public function groupBy($groupBy = null, $mode = self::GROUP_MODE_FLAT)
    {
        return is_null($groupBy) ? $this->groupBy : $this->setGroupBy($groupBy, $mode);
    }

    /**
     * Set group by
     *
     * @param  string $groupBy
     * @param  string $mode
     *
     * @return Request
     */
    protected function setGroupBy($groupBy, $mode = self::GROUP_MODE_FLAT)
    {
        if ($groupBy === self::GROUP_DEFAULT || $groupBy === self::GROUP_SITE) {
            $this->groupBy = $groupBy;
            if ($groupBy === self::GROUP_DEFAULT) {
                $this->groupByMode = self::GROUP_MODE_FLAT;
            } else {
                $this->groupByMode = $mode;
            }
            return $this;
        }
        throw new \InvalidArgumentException('Invalid group params');
    }

    /**
     * Set option
     *
     * @param  string $option
     * @param  mixed  $value
     *
     * @return Request|mixed
     */
    public function option($option = null, $value = null)
    {
        return is_null($option) ? $this->getOption($option) : $this->setOption($option, $value);
    }

    /**
     * Set option
     *
     * @param  string $option
     * @param  mixed  $value
     *
     * @return Request
     */
    protected function setOption($option, $value = null)
    {
        $this->options[$option] = $value;
        return $this;
    }

    /**
     * Get option
     *
     * @param string $option
     *
     * @return mixed
     */
    protected function getOption($option)
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }

    /**
     * Set/Get proxy fo request
     *
     * @param  string  $host
     * @param  integer $port
     * @param  string  $user
     * @param  string  $pass
     *
     * @return Request|array
     */
    public function proxy($host = '', $port = 80, $user = null, $pass = null)
    {
        return is_null($host) ? $this->getProxy() : $this->setProxy($host, $port, $user, $pass);
    }

    /**
     * Set proxy for request
     *
     * @param  string  $host
     * @param  integer $port
     * @param  string  $user
     * @param  string  $pass
     *
     * @return Request
     */
    protected function setProxy($host = '', $port = 80, $user = null, $pass = null)
    {
        $this->proxy = array(
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pass' => $pass,
        );
        return $this;
    }

    /**
     * Get proxy settings
     *
     * @return array
     */
    protected function getProxy()
    {
        return $this->proxy;
    }

    /**
     * Apply proxy before each request
     *
     * @param resource $ch
     */
    protected function applyProxy($ch)
    {
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_PROXY => $this->proxy['host'],
                CURLOPT_PROXYPORT => $this->proxy['port'],
                CURLOPT_PROXYUSERPWD => $this->proxy['user'] . ':' . $this->proxy['pass']
            )
        );
    }

    /**
     * Send request
     *
     * @throws YandexXmlException
     * @return Response
     */
    public function send()
    {
        if (empty($this->query)
            && empty($this->host)
        ) {
            throw new YandexXmlException(YandexXmlException::EMPTY_QUERY);
        }

        $this->request = $this->prepareXmlRequest();

        // build GET data
        $getData = array(
            'user' => $this->user,
            'key' => $this->key,
        );

        if ($this->lr) {
            $getData['lr'] = $this->lr;
        }
        if ($this->l10n) {
            $getData['l10n'] = $this->l10n;
        }

        $url = $this->baseUrl . '?' . http_build_query($getData);


        $data = $this->sendCurl($url, $this->request->asXML());

        /** @var \SimpleXMLElement $simpleXML */
        try {
            $simpleXML = new \SimpleXMLElement($data);
        } catch (\Exception $e) {
            $exception = new BadDataException($e->getMessage(), $e->getCode(), $e->getPrevious());
            $exception->setData($data);
            throw $exception;
        }

        $simpleXML = $simpleXML->response;

        // check response error
        if (isset($simpleXML->error)) {
            $code = (int)$simpleXML->error->attributes()->code[0];
            $message = (string)$simpleXML->error;

            throw new YandexXmlException($message, $code);
        }

        $response = new Response();

        // results
        $results = array();
        foreach ($simpleXML->results->grouping->group as $group) {
            $res = new \stdClass();
            $res->url = (string)$group->doc->url;
            $res->domain = (string)$group->doc->domain;
            $res->title = isset($group->doc->title) ? Client::highlight($group->doc->title) : $res->url;
            $res->headline = isset($group->doc->headline) ? Client::highlight($group->doc->headline) : null;

            $passages = array();
            if (isset($group->doc->passages->passage)) {
                foreach ($group->doc->passages->passage as $passage) {
                    $passages[] = Client::highlight($passage);
                }
            }
            $res->passages = $passages;

            $res->sitelinks = isset($group->doc->snippets->sitelinks->link) ? Client::highlight(
                $group->doc->snippets->sitelinks->link
            ) : null;

            $results[] = $res;
        }
        $response->results($results);


        // total results
        $res = $simpleXML->xpath('found[attribute::priority="all"]');
        $total = (int)$res[0];
        $response->total($total);

        // total in human text
        $res = $simpleXML->xpath('found-human');
        $totalHuman = $res[0];
        $response->totalHuman($totalHuman);

        // pages
        $response->pages(floor($total / $this->limit()));

        return $response;
    }

    /**
     * prepareRequest
     *
     * @return \SimpleXMLElement
     */
    protected function prepareXmlRequest()
    {
        $xml = new \SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><request></request>");

        // add query to request
        $query = $this->query();

        // if isset "host"
        if ($this->host) {
            if (is_array($this->host)) {
                $host_query = '(host:"' . implode('" | host:"', $this->host) . '")';
            } else {
                $host_query = 'host:"' . $this->host . '"';
            }

            if (!empty($query) && $this->host) {
                $query .= ' ' . $host_query;
            } elseif (empty($query) && $this->host) {
                $query .= $host_query;
            }
        }

        // if isset "site"
        if ($this->site) {
            if (is_array($this->site)) {
                $site_query = '(site:"' . implode('" | site:"', $this->site) . '")';
            } else {
                $site_query = 'site:"' . $this->site . '"';
            }

            if (!empty($query) && $this->site) {
                $query .= ' ' . $site_query;
            } elseif (empty($query) && $this->site) {
                $query .= $site_query;
            }
        }

        // if isset "domain"
        if ($this->domain) {
            if (is_array($this->domain)) {
                $domain_query = '(domain:' . implode(' | domain:', $this->domain) . ')';
            } else {
                $domain_query = 'domain:' . $this->domain;
            }
            if (!empty($query) && $this->domain) {
                $query .= ' ' . $domain_query;
            } elseif (empty($query) && $this->domain) {
                $query .= $domain_query;
            }
        }

        // if isset "cat"
        if ($this->cat) {
            $query .= ' cat:' . ($this->cat + 9000000);
        }

        // if isset "theme"
        if ($this->theme) {
            $query .= ' cat:' . ($this->theme + 4000000);
        }

        // if isset "geo"
        if ($this->geo) {
            $query .= ' cat:' . ($this->geo + 11000000);
        }

        $xml->addChild('query', $query);
        $xml->addChild('page', $this->page);

        $groupings = $xml->addChild('groupings');
        $groupby = $groupings->addChild('groupby');
        $groupby->addAttribute('attr', $this->groupBy);
        $groupby->addAttribute('mode', $this->groupByMode);
        $groupby->addAttribute('groups-on-page', $this->limit);
        $groupby->addAttribute('docs-in-group', 1);

        $xml->addChild('sortby', $this->sortBy);
        $xml->addChild('maxpassages', $this->options['maxpassages']);
        $xml->addChild('max-title-length', $this->options['max-title-length']);
        $xml->addChild('max-headline-length', $this->options['max-headline-length']);
        $xml->addChild('max-passage-length', $this->options['max-passage-length']);
        $xml->addChild('max-text-length', $this->options['max-text-length']);

        return $xml;
    }

    /**
     * Send Curl request
     *
     * @param string $url
     * @param string $data
     *
     * @return string
     */
    protected function sendCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

        if (!empty($this->proxy['host'])) {
            $this->applyProxy($ch);
        }

        return curl_exec($ch);
    }
}
