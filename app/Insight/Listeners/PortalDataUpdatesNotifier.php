<?php namespace Insight\Listeners;

/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:50 PM
 */
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Insight\Companies\Company;
use Insight\Portal\Connections\Webservices;
use Insight\Portal\Contracts\Events\ContractsWereUpdated;
use Insight\Portal\Orders\Events\DailyOrderReportWasRequested;
use Insight\Portal\Orders\Events\OrdersPendingApprovalReportWasCreated;
use Insight\Portal\Products\Events\ProductsWereUpdated;
use Insight\Portal\Orders\Events\OrdersWereUpdated;
use Insight\Notifications\Notification;
use Insight\Mailers\PortalUpdatesMailer;
use Insight\Settings\SettingRepository;
use Insight\Users\User;

/**
 * Class PortalDataUpdatesNotifier
 * @package Insight\Listeners
 */
class PortalDataUpdatesNotifier extends EventListener
{

    /**
     * @var \Insight\Mailers\VerificationMailer
     */
    private $mailer;

    /**
     * @var SettingRepository
     */
    protected $setting;

    /**
     * @var
     */
    protected $sendNotifications;

    /**
     * @var \Insight\Users\UserRepository
     */
    private $userRepository;

    /**
     * @param PortalUpdatesMailer $mailer
     * @param SettingRepository $setting
     */
    public function __construct(PortalUpdatesMailer $mailer, SettingRepository $setting)
    {
        $this->mailer = $mailer;
        $this->setting = $setting;
        $this->sendNotifications = settings('notifications.send_portal_data_update_notifications');
    }

    /**
     * @param ContractsWereUpdated $event
     */
    public function whenContractsWereUpdated(ContractsWereUpdated $event)
    {
        $log = $event->changeLog;

        $customerGroups = $this->getCustomerGroups();

        foreach ($log as $customer => $contractUpdates) {

            $customerGroupCode = array_search($customer, $customerGroups);

            if (! Company::where('magento_customer_group_id', $customerGroupCode)->first()) {
                Log::info('Customer ' . $customer . ' is not configured to receive contract updates.');
                continue;
            }

            $data = ['customer' => $customer, 'data' => $contractUpdates];

            $emailRecipients = $this->getEmailRecipients('ContractsUpdated', $customer);


            if ($this->sendNotifications && $emailRecipients) {
                $this->mailer->sendContractUpdatesMessageTo($emailRecipients, $data);
            }

        }

    }

    /**
     * @param ProductsWereUpdated $event
     */
    public function whenProductsWereUpdated(ProductsWereUpdated $event)
    {
        $log = $event->changeLog;

        $customerGroups = $this->getCustomerGroups();

        foreach ($log as $customer => $productUpdates) {

            $customerGroupCode = array_search($customer, $customerGroups);

            if (! Company::where('magento_customer_group_id', $customerGroupCode)->first()) {
                Log::info('Customer ' . $customer . ' is not configured to receive product updates.');
                continue;
            }

            $data = ['customer' => $customer, 'data' => $productUpdates];
            $emailRecipients = $this->getEmailRecipients('ProductsUpdated', $customer);

            if ($this->sendNotifications && $emailRecipients) {
                $this->mailer->sendProductUpdatesMessageTo($emailRecipients, $data);
            }

        }
    }

    /**
     * @param OrdersWereUpdated $event
     */
    public function whenOrdersWereUpdated(OrdersWereUpdated $event)
    {
        $orderUpdates = $event->changeLog;
        $data = ['customer' => 'All', 'data' => $orderUpdates];
        $emailRecipients = $this->getEmailRecipients('OrdersUpdated');
        if ($this->sendNotifications && $emailRecipients) {
            $this->mailer->sendOrderUpdatesMessageTo($emailRecipients, $data);
        }
    }

    /**
     * @param DailyOrderReportWasRequested $event
     * @return bool
     */
    public function whenDailyOrderReportWasRequested(DailyOrderReportWasRequested $event)
    {
        $data = $event->orderReport;

        if (! Company::where('magento_customer_group_id', $data['customer_group_id'])->first()) {

            Log::info('Customer ' . $data['customer'] . ' is not configured to receive Daily Order reports.');

            // delete the file
            File::delete($data['file']['full']);
            return false;
        }

        $emailRecipients = $this->getEmailRecipients('DailyOrderReportRequests', $data['customer']);
        if ($this->sendNotifications && $emailRecipients) {
            $this->mailer->sendOrderNotifyMessageTo($emailRecipients, $data);
        }
        // TODO: Create cleanup job to delete old report files
        // delete the file
        //File::delete($data['file']['full']);

    }

    /**
     * @param OrdersPendingApprovalReportWasCreated $event
     * @return bool
     */
    public function whenOrdersPendingApprovalReportWasCreated(OrdersPendingApprovalReportWasCreated $event)
    {
        $emailRecipients = [];
        foreach ($event->data['recipients'] as $recipient) {
            if (User::find($recipient)) {
                $emailRecipients[] = User::find($recipient)->email;
            }
        }
        if ($this->sendNotifications && $emailRecipients) {
            $this->mailer->sendOrdersPendingApprovalReportTo($emailRecipients, $event->data, $event->report);
        }
    }

    /**
     * @param $notification
     * @param null $customer
     * @return array
     */
    private function getEmailRecipients($notification, $customer = null)
    {
        // General recipients
        $generalNotification = Notification::where('name', $notification)->first();
        if (! $generalNotification) {
            return false;
        }
        $emailRecipients = $generalNotification->users->lists('email');

        // Customer specific recipients
        if (isset($customer)) {
            $notificationName = $customer . $notification;
            $customerNotification = Notification::where('name', $notificationName)->first();


            if ($customerNotification) {
                $emails = $customerNotification->users->lists('email');

                return array_unique(array_merge($emailRecipients, $emails));
            }

        }

        return $emailRecipients;
    }

    //get the Customer Groups
    private function getCustomerGroups(){
        $result = array();
        $webservice = new Webservices();
        $customerGroups = $webservice->getCustomerGroups();
        foreach($customerGroups as $group){
            $result[$group->customer_group_id] = $group->customer_group_code;
        }
        return $result;
    }
}
