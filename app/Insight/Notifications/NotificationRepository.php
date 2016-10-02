<?php

namespace Insight\Notifications;

class NotificationRepository
{
    public function getList()
    {
        return Notification::lists('name', 'id');
    }
}
