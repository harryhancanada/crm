<?php

namespace App\Listeners;

use App\Events\TaskAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Activity;
use Lang;
use App\Models\Task;

class TaskActionLog
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  TaskAction  $event
     * @return void
     */
    public function handle(TaskAction $event)
    {
        switch ($event->getAction()) {
            case 'created':
                $text = __(':title 由 :creator 建立并且属于 :assignee', [
                        'title' => $event->getTask()->title,
                        'creator' => $event->getTask()->creator->name,
                        'assignee' => $event->getTask()->user->name
                    ]);
                break;
            case 'updated_status':
                switch($event->getTask()->status) {
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
                        'username' => Auth()->user()->name,
                        'title' => $event->getTask()->title,
                        'status' => $status1
                    ]);
                break;
            case 'updated_time':
                $text = __(':username 给此申请添加了一条新进度', [
                        'username' => Auth()->user()->name,
                    ]);
                ;
                break;
            case 'updated_assign':
                $text = __(':username 将此申请重新分配给 :assignee', [
                        'username' => Auth()->user()->name,
                        'assignee' => $event->getTask()->user->name
                    ]);
                break;
            default:
                break;
        }

        $activityinput = array_merge(
            [
                'text' => $text,
                'user_id' => Auth()->id(),
                'source_type' =>  Task::class,
                'source_id' =>  $event->getTask()->id,
                'action' => $event->getAction()
            ]
        );
        
        Activity::create($activityinput);
    }
}
