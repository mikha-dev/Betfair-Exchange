<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

class Betting
{
    const ENDPOINT = 'https://api.betfair.com/exchange/betting/rest/v1.0/';

    protected $httpClient;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function listMarketcatalogue($filter = [], $marketProjection = [], $sort = null, $maxResults = 100, $locale = null)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listMarketCatalogue/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setFilter($filter)
            ->setMarketProjection($marketProjection)
            ->setSort($sort)
            ->setMaxResults($maxResults)
            ->setLocale($locale)
            ->send();
    }

    /**
     * Six Exchange methods have an identical API, so we bundle them into a single magic call e.g.
     * @method listCompetitions(array $filters, string $locale)
     * @return array
     */
    public function __call($method, $params)
    {
        if (in_array($method, [ 'listCompetitions', 'listCountries', 'listEvents', 'listEventTypes', 'listMarketTypes', 'listVenues' ])) {

            $filter = isset($params[ 0 ]) ? $params[ 0 ] : [ ];
            $locale = isset($params[ 1 ]) ? $params[ 1 ] : [ ];

            return $this->httpClient
                ->setMethod('post')
                ->setEndPoint(self::ENDPOINT.$method.'/')
                ->authHeaders()
                ->addHeader([ 'Content-Type' => 'application/json' ])
                ->setFilter($filter)
                ->setLocale($locale)
                ->send();
        }
    }
}
