<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayrollCompletedMail;
use App\Traits\Notificator;
use App\Models\Payroll;

class PayrollCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Notificator;

    public $payroll;

    /**
     * Create a new event instance.
     */
    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;

        Mail::to($this->payroll->employee->email)->send(new PayrollCompletedMail($this->payroll));

        $this->sendNotification('Payroll email successfully sent to: ' . $payroll->employee->email, 'HTML');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
