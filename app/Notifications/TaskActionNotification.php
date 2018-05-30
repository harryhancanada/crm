<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Lang;
use App\Models\Task;

class TaskActionNotification extends Notification
{
    use Queueable;


    private $task;
    private $action;

    /**
     * Create a new notification instance.
     * TaskActionNotification constructor.
     * @param $task
     * @param $action
     */
    public function __construct($task, $action)
    {
        $this->task = $task;
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
       /* return (new MailMessage)
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
                $text = __('< :title > 由 < :creator > 建立, 并且分配给你', [
                    'title' =>  $this->task->title,
                    'creator' => $this->task->creator->name,
                    ]);
                break;
            case 'updated_status':

            switch($this->task->status) {
                case '1':
                    $status1='进行中';
                    break;
                case '2':
                    $status1='已完成';
                    break;
                case '3':
                    $status1='无效';
                    break;
                case '4':
                    $status1 ='暂停';
                    break;
            }
                $text = __('申请 < :title > 的状态被 :username 更改成 < :status >', [
                    'title' =>  $this->task->title,
                    'username' =>  Auth()->user()->name,
                    'status' =>  $status1,
                    ]);
                break;
            case 'updated_time':
                $text = __('< :username > 给 申请 < :title > 添加了一条新的进度', [
                    'title' =>  $this->task->title,
                    'username' =>  Auth()->user()->name,
                    ]);
                break;
            case 'updated_assign':
                $text = __(':username 给你分配了一个新申请任务。', [
                    'title' =>  $this->task->title,
                    'username' =>  Auth()->user()->name,
                    ]);
                break;
            default:
                break;
        }
        return [
            'assigned_user' => $notifiable->id, //Assigned user ID
            'created_user' => $this->task->creator->id,
            'message' => $text,
            'type' =>  Task::class,
            'type_id' =>  $this->task->id,
            'url' => url('tasks/' . $this->task->id),
            'action' => $this->action
        ];
    }
}
