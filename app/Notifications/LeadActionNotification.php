<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;
use Lang;
use App\Models\Lead;

class LeadActionNotification extends Notification
{
    use Queueable;

    private $lead;
    private $action;

    /**
     * Create a new notification instance.
     * LeadActionNotification constructor.
     * @param $lead
     * @param $action
     */
    public function __construct($lead, $action)
    {
        $this->lead = $lead;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /*return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!'); */
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        switch ($this->action) {
            case 'created':
                $text = __('咨询 < :title > 由 :creator 建立并且分配给你。', [
                'title' => $this->lead->title,
                'creator' => $this->lead->creator->name
                ]);
                break;
            case 'updated_status':
                switch($this->lead->status) {
                    case '1':
                        $status1='咨询中';
                        break;
                    case '2':
                        $status1='已完成';
                        break;
                    case '3':
                        $status1='无兴趣';
                        break;
                }
                $text = __('咨询 < :title > 的状态被 :username 更改成 :status', [
                'title' => $this->lead->title,
                'username' =>  Auth()->user()->name,
                'status'=> $status1
                ]);
                break;
            case 'updated_deadline':
                $text = __(':username 更新了 < :title > 的下一次跟进时间', [
                'title' => $this->lead->title,
                'username' =>  Auth()->user()->name
                ]);
                break;
            case 'updated_assign':
                $text = __(':username 给你分配了个新咨询。', [
                'username' =>  Auth()->user()->name
                ]);
                break;
            default:
                break;
        }
        return [
            'assigned_user' => $notifiable->id, //Assigned user ID
            'created_user' => $this->lead->creator->id,
            'message' => $text,
            'type' => Lead::class,
            'type_id' =>  $this->lead->id,
            'url' => url('leads/' . $this->lead->id),
            'action' => $this->action
        ];
    }
}
