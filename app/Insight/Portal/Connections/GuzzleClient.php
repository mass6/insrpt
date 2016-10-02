<?php

namespace Insight\Portal\Connections;

use Guzzle\Http\Client;

class GuzzleClient
{

    /**
     * @var Client
     */
    private $client;

    private $reportServiceUri;
    private $queryServiceUri;
    private $validationReportUri;
    private $ws_key;


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->reportServiceUri = getenv('WS_REPORT_URL');
        $this->queryServiceUri = getenv('WS_QUERY_URL');
        $this->validationReportUri = getenv('WS_VALIDATION_REPORT_URL');
        $this->ws_key = sha1(getenv('WS_KEY'));
    }

    public function getReport($reportName, $dataGroup = '')
    {
        $data = [
            'key' => $this->ws_key,
            'reportName' => $reportName,
            'group' => $dataGroup,
        ];
        $request = $this->client->post($this->reportServiceUri, null, $data);

        return $request->send($request)->json();
    }


    public function getValidationReport($reportName, $store, $dataGroup = '')
    {
        $data = [
            'key' => $this->ws_key,
            'reportName' => $reportName,
            'store' => $store,
            'group' => $dataGroup,
        ];
        $request = $this->client->post($this->validationReportUri, null, $data);

        return $request->send($request)->json();
    }

    public function getQuery($queryName, $search, $dataGroup = '')
    {
        $data = [
            'key' => $this->ws_key,
            'reportName' => ucwords($queryName),
            'group' => $dataGroup,
            'search'     => $search
        ];
        $request = $this->client->post($this->queryServiceUri, null, $data);

        return $request->send($request)->json();
    }
}
