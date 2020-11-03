<?php

namespace App\Console\Commands;

use App\Mail\NotificationEmail;
use App\Models\TradeBotNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send trading bot notifications in cases of errors';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unsent_notifications = TradeBotNotification::whereNull('user_notified_on')->get();

        foreach ($unsent_notifications as $notification) {
            $user = $notification->tradeBot->user;

            if ($user->notify_by === 'email') {
                $this->sendEmail($user, $notification);

            } else {
                $this->sendText($user, $notification);
            }

            $notification->update([
                'user_notified_on' => Carbon::now()
            ]);
        }
    }

    /**
     * Send notification over email
     */
    private function sendEmail(User $user, TradeBotNotification $notification)
    {
        Mail::to($user->email)->send(new NotificationEmail($notification->message, $notification->tradeBot->formattedId()));
    }

    /**
     * Send notification over text
     */
    private function sendText(User $user, TradeBotNotification $notification)
    {
        $twilio_account_sid = config('services.twilio')['TWILIO_ACCOUNT_SID'];
        $twilio_auth_token  = config('services.twilio')['TWILIO_AUTH_TOKEN'];
        $twilio_phone_number  = config('services.twilio')['TWILIO_PHONE_NUMBER'];

        $client = new Client($twilio_account_sid, $twilio_auth_token);

        try
        {
            $client->messages->create(
                $user->phone_number,
                array(
                    'from' => $twilio_phone_number,
                    'body' => $notification->message
                )
            );
        }
        catch (\Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }
}
