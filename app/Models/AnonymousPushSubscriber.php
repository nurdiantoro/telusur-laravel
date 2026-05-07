<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\WebPush\HasPushSubscriptions;

class AnonymousPushSubscriber extends Model
{
    use Notifiable;
    use HasPushSubscriptions;

    protected $guarded = [];
}
