<?php namespace Insight\Mailers; 
/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:23 PM
 */

/**
 * Class PortalUpdatesMailer
 * @package Insight\Mailers
 */
class PortalUpdatesMailer extends Mailer
{

    /**
     * @param array $emailRecipients
     * @param array $data
     */
    public function sendContractUpdatesMessageTo(Array $emailRecipients, $data = [])
    {
        $subject = 'Insight Alert: ' . $data['customer'] . ' Contracts Updated';
        $view = 'emails.changelog.contracts';
        $data['url'] = $this->getPortalUrl();
        foreach ($emailRecipients as $email)
        {
            $this->sendTo($email, $subject, $view, $data);
        }

    }

    /**
     * @param array $emailRecipients
     * @param array $data
     */
    public function sendProductUpdatesMessageTo(Array $emailRecipients, $data = [])
    {
        $subject = 'Insight Alert: ' . $data['customer'] . ' Products Updated';
        $view = 'emails.changelog.products';
        $data['url'] = $this->getPortalUrl();
        foreach ($emailRecipients as $email)
        {
            $this->sendTo($email, $subject, $view, $data);
        }
    }

    /**
     * @param array $emailRecipients
     * @param array $data
     */
    public function sendOrderUpdatesMessageTo(Array $emailRecipients, $data = [])
    {
        $subject = 'Insight Alert: Orders Added';
        $view = 'emails.changelog.orders';
        $data['url'] = $this->getPortalUrl();
        foreach ($emailRecipients as $email)
        {
            $this->sendTo($email, $subject, $view, $data);
        }
    }

    /**
     * @param array $emailRecipients
     * @param array $data
     */
    public function sendOrderNotifyMessageTo(Array $emailRecipients, Array $data)
    {
        $subject = 'Insight Alert: ' . $data['customer'] . ' Daily Order Report';
        $view = 'emails.changelog.daily_order_report';
        $data['url'] = $this->getPortalUrl();
        $file = $data['file'];
        foreach ($emailRecipients as $email)
        {
            $this->sendTo($email, $subject, $view, $data, $file['full'],
                $data['report_date'] . '_' . $data['customer'] . 'DailyOrderReport.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }
    }

    /**
     * @param array $emailRecipients
     * @param array $data
     * @param array $file
     */
    public function sendOrdersPendingApprovalReportTo(Array $emailRecipients, Array $data, $file)
    {
        $subject = 'Orders Pending Approval Report for ' . $data['customer_name'];
        $view = 'emails.orders.orders_pending_approval_report';
        $data['url'] = $this->getPortalUrl();
        foreach ($emailRecipients as $email)
        {
            $this->sendTo($email, $subject, $view, $data, $file['full'],
                $data['customer_name'] . '_OrdersPendingApprovalReport.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }
    }

    /**
     * @return string
     */
    public function getPortalUrl(){
        $path = getenv('WS_REPORT_URL');
        $path = parse_url($path);
        $url = $path['scheme'].'://'.$path['host'];

        return $url;
    }
} 
