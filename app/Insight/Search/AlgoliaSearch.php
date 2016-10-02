<?php

namespace Insight\Search;

use AlgoliaSearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class AlgoliaSearch
{

    private $client;

    private $index;


    public function __construct()
    {
        try {
            $config = Config::get('algolia');
            $this->client = new Client($config['id'], $config['key']);
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setSettings($settings)
    {
        $this->index->setSettings($settings);
    }

    public function setIndex($index)
    {
        if ($this->client) {
            $this->index = $this->client->initIndex($index);
        }

    }

    public function search($query)
    {
        return $this->index->search($query);
    }

    public function save($record)
    {
        if ($record instanceof Model) {
            $record->objectID = $record->id;
        }
        if (is_array($record)) {
            $record['objectID'] = $record['id'];
        }

        $this->index->saveObject($record);
    }

    public function saveMany($records)
    {
        if ($records instanceof Collection) {
            $records = $records->toArray();
        }

        foreach ($records as &$record){
            $record['objectID'] = $record['id'];
        };

        $this->index->saveObjects($records);
    }

    public function deleteMany($records)
    {
        if ($records instanceof Collection) {
            $records = $records->toArray();
        }

        foreach ($records as $record){
            $this->delete($record);
        };

    }

    public function delete($record)
    {
        if ($record instanceof Model) {
            $objectID = $record->id;
        }
        if (is_array($record)) {
            $objectID = $record['id'];
        }

        $this->index->deleteObject($objectID);
    }
}
