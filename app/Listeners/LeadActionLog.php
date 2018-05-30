<?php

namespace App\Listeners;

use App\Events\LeadAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Activity;
use Lang;
use App\Models\Lead;

class LeadActionLog
{
    /**
     * Action the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LeadAction  $event
     * @return void
     */
    public function handle(LeadAction $event)
    {
        switch ($event->getAction()) {
            case 'created':
                $text = __(':title 由 :creator 建立被分配给 :assignee', [
                    'title' => $event->getLead()->title,
                    'creator' => $event->getLead()->creator->name,
                    'assignee' => $event->getLead()->user->name
                ]);
                break;
            case 'updated_status':
                switch($event->getLead()->status) {
                    case '1':
                        $status1='进行中';
                        break;
                    case '2':
                        $status1='已完成';
                        break;
                    case '3':
                        $status1='无兴趣';
                        break;
                }

                $text = __('此咨询已被 :username 更改状态为 < :status >', [
                    'username' => Auth()->user()->name,
                    'status' => $status1
                ]);
                break;
            case 'updated_deadline':
                $text = __(':username 更新了此咨询的下一次跟进时间', [
                    'username' => Auth()->user()->name,
                ]);
                break;
            case 'updated_assign':
                $text = __(':username 将此咨询重新分配给 :assignee', [
                    'username' => Auth()->user()->name,
                    'assignee' => $event->getLead()->user->name
                ]);
                break;
            default:
                break;
        }

        $activityinput = array_merge(
            [
                'text' => $text,
                'user_id' => Auth()->id(),
                'source_type' => Lead::class,
                'source_id' =>  $event->getLead()->id,
                'action' => $event->getAction()
            ]
        );
        
        Activity::create($activityinput);
    }
}
