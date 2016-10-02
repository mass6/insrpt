<?php  namespace Insight\Portal\Connections;
/***
 * Created by:
 * User: gina
 * Date: 3/1/15
 * Time: 9:55 PM
 */
use Illuminate\Support\Facades\Log;
use Insight\Portal\Exceptions\WebservicesUnavailableException;
use \Session;
use SoapClient;
use Exception;
use stdClass;
use DateTimeZone;
use DateTime;
use Illuminate\Support\Facades\Config;

/**
 * Class Webservices
 * @package Insight\Portal\Connections
 */
class Webservices
{

    /**
     * @var string
     */
    protected $webservicesUrl;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $session;

    /** 
     *
     */
    public function __construct()
    {
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => true,
                'allow_self_signed' => true
            )
        ));
        $this->webservicesUrl = getenv('WEBSERVICES_URL');
        $this->username = getenv('WEBSERVICES_USER');
        $this->password = getenv('WEBSERVICES_APIKEY');

        try {
            $this->client = @new SoapClient($this->webservicesUrl,array('stream_context'=>$context, "exceptions" => 1));
            $this->session = $this->client->login($this->username, $this->password);
        } catch (\SoapFault $e) {
            Log::error($e->faultstring);
            throw new WebservicesUnavailableException("Webservice not accessible");
        }

    }



    /** 
     *
     */
    function __destruct()
    {
        if (isset($this->client))
            $this->client->endSession($this->session);
    }

    /**
     * Get full list of sales orders that need to be synced
     *
     * @return array|mixed
     */
    public function getOrders()
    {
         return $this->client->sboeconnectSalesOrderList($this->session, null);
    }

    /**
     * Mark order as synced
     *
     * @return array|mixed
     */
    public function markOrderSynced($increment_id)
    {

         return $this->client->sboeconnectSalesOrderSync($this->session, $increment_id, "1");
    }

    //Get all of categories via API
    public function getAllCategories(){
        $result = null;
        try{
            $result = $this->client->catalogCategoryTree($this->session);
        } catch(Exception $e){
            echo $e->getMessage();
        }
        $list = array();
        return $this->loadCategoryInTree($result,$list);
    }

    //load all categories from tree into an array
    public function loadCategoryInTree($category,$list){
        if($category->children){
            foreach($category->children as $item){
                $sub = array();
                $sub['id'] = $item->category_id;
                $sub['name'] = $item->name;
                array_push($list,$sub);
                if($item->children){
                    $list = $this->loadCategoryInTree($item,$list);
                }
            }
        }
        return $list;
    }

    //get target categories via api
    public function getTargetCategories($parentId){
        $result = null;
        try{
            $result = $this->client->catalogCategoryTree($this->session,$parentId);
        } catch(Exception $e){
            echo $e->getMessage();
        }
        $childrenList = array();
        if($result->children){
            foreach($result->children as $val){
                $sub = array();
                $sub['id'] = $val->category_id;
                $sub['name'] = $val->name;
                array_push($childrenList,$sub);
            }
        }
        return $childrenList;
    }

    public function getOrdersDuringTime($parentId, $startDate, $endDate, $group_id){
        $filters = new stdClass();
        $filters->filter = array(
            array(
                'key' => 'parent_category_id',
                'value' => $parentId
            ),
            array(
                'key' => 'from_date',
                'value' => $startDate
            ),
            array(
                'key' => 'to_date',
                'value' => $endDate
            ),
            array(
               'key' =>'group_id',
                'value' => $group_id
            )
        );

        return $this->client->salesOrderSpendByCategories($this->session,$filters);
    }

    public function getCustomerGroups(){
        return $this->client->customerGroupList($this->session);
    }

    public function getDailyOrders($filter_day = null, $customer_code){

        $filters = new stdClass();
        if($filter_day){
            $times = $this->getFilterTimesForOneDay($filter_day);
        } else {
            $times = $this->getFilterTimesForOneDay(date('Y-m-d'));
        }


        $filters->filter = array(
            array(
                'key' => 'start_time',
                'value' => $times['start_time']
            ),
            array(
                'key' => 'end_time',
                'value' => $times['end_time']
            ),
            array(
                'key' => 'customer_code',
                'value' => $customer_code
            )
        );

        return $this->client->salesOrderList($this->session, $filters);
    }

    public function get_timezone_offset($remote_tz, $origin_tz = null) {
        if($origin_tz === null) {
            if(!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }

    function getFilterTimesForOneDay($date){
        $offset = $this->get_timezone_offset('UTC',getenv('APP_TIMEZONE'));
        $offsetHour = $offset/3600;
        $startTime = date('Y-m-d '.(24 - $offsetHour).':00:00',strtotime($date.'-1 day'));
        $endTime = date('Y-m-d '.(24 - $offsetHour).':00:00', strtotime($date));
        return array(
            'start_time' => $startTime,
            'end_time'   => $endTime
        );
    }
} 
