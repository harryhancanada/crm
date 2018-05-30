<?php
namespace App\Http\Controllers;

use Gate;
use Carbon;
use Datatables;
use App\Models\Task;
use App\Http\Requests;
use App\Models\Integration;
use Illuminate\Http\Request;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Repositories\Task\TaskRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\Repositories\Client\ClientRepositoryContract;
use App\Repositories\Setting\SettingRepositoryContract;
use App\Repositories\Invoice\InvoiceRepositoryContract;

class TasksController extends Controller
{

    protected $request;
    protected $tasks;
    protected $clients;
    protected $settings;
    protected $users;
    protected $invoices;

    public function __construct(
        TaskRepositoryContract $tasks,
        UserRepositoryContract $users,
        ClientRepositoryContract $clients,
        InvoiceRepositoryContract $invoices,
        SettingRepositoryContract $settings
    )
    {
        $this->tasks = $tasks;
        $this->users = $users;
        $this->clients = $clients;
        $this->invoices = $invoices;
        $this->settings = $settings;

        $this->middleware('task.create', ['only' => ['create']]);
        $this->middleware('task.update.status', ['only' => ['updateStatus']]);
        $this->middleware('task.assigned', ['only' => ['updateAssign', 'updateTime']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('tasks.index');
    }

    public function anyData()
    {
        if ((!auth()->user()->hasRole('administrator'))&(!auth()->user()->hasRole('manager'))){
            $user_id=auth()->user()->id;
        $tasks = Task::select(
            ['id', 'title', 'created_at', 'deadline', 'user_assigned_id', 'status']
        )->where('user_assigned_id',$user_id);
        }
        else $tasks = Task::select(
            ['id', 'title', 'created_at', 'deadline', 'user_assigned_id', 'status']
        );
            //->where('status', 1)->get();
        return Datatables::of($tasks)
            ->addColumn('titlelink', function ($tasks) {
                return '<a href="tasks/' . $tasks->id . '" ">' . $tasks->title . '</a>';
            })
            ->editColumn('created_at', function ($tasks) {
                return $tasks->created_at ? with(new Carbon($tasks->created_at))
                    ->format('d/m/Y') : '';
            })
            ->editColumn('deadline', function ($tasks) {
                return $tasks->created_at ? with(new Carbon($tasks->deadline))
                    ->format('d/m/Y') : '';
            })
            ->editColumn('user_assigned_id', function ($tasks) {
                return $tasks->user->name;
            })
            ->editColumn('status', function ($tasks) {
                switch ($tasks->status){
                    case '1':
                        return '<span class="label label-primary">进行中</span>';
                        break;
                    case '2':
                        return '<span class="label label-success">已完成</span>';
                        break;
                    case '3':
                        return '<span class="label label-danger">无效</span>';
                        break;
                    case '4':
                        return '<span class="label label-warning">暂停中</span>';
                        break;

                }

            })
            ->addColumn('edit', function ($tasks) {
                return '<a href="' . route("tasks.show", $tasks->id) . '" class="btn btn-success"> Edit</a>';
            })
            ->add_column('delete', '
                <form action="{{ route(\'tasks.destroy\', $id) }}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" name="submit" value="Delete" class="btn btn-danger" onClick="return confirm(\'你确定要删除该条申请吗（不可恢复）？\')"">

            {{csrf_field()}}
            </form>')
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return view('tasks.create')
            ->withUsers($this->users->getAllUsersWithDepartments())
            ->withClients($this->clients->listAllClients());
    }

    /**
     * @param StoreTaskRequest $request
     * @return mixed
     */
    public function store(StoreTaskRequest $request) // uses __contrust request
    {
        $getInsertedId = $this->tasks->create($request);
        return redirect()->route("tasks.show", $getInsertedId);
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function show(Request $request, $id)
    {
        if(Task::find($id)){
        return view('tasks.show')
            ->withTasks($this->tasks->find($id))
            ->withUsers($this->users->getAllUsersWithDepartments())
            ->withInvoiceLines($this->tasks->getInvoiceLines($id))
            ->withCompanyname($this->settings->getCompanyName());
        
        }
        else 
        {
            Session()->flash('flash_message_warning', '该条申请不存在或已删除');
            return redirect()->back();
        
        }
    }


    /**
     * Sees if the Settings from backend allows all to complete taks
     * or only assigned user. if only assigned user:
     * @param $id
     * @param Request $request
     * @return
     * @internal param $ [Auth]  $id Checks Logged in users id
     * @internal param $ [Model] $task->user_assigned_id Checks the id of the user assigned to the task
     * If Auth and user_id allow complete else redirect back if all allowed excute
     * else stmt
     */
    public function updateStatus($id, Request $request)
    {
        $this->tasks->updateStatus($id, $request);
        Session()->flash('flash_message', '状态已改变');
        return redirect()->back();
    }


    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateAssign($id, Request $request)
    {
        $clientId = $this->tasks->getAssignedClient($id)->id;


        $this->tasks->updateAssign($id, $request);
        Session()->flash('flash_message', '顾问已更改');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateTime($id, Request $request)
    {
        $this->tasks->updateTime($id, $request);
        Session()->flash('flash_message', 'Time has been updated');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function invoice($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $clientId = $task->client()->first()->id;
        $timeTaskId = $task->time()->get();
        $integrationCheck = Integration::first();

        if ($integrationCheck) {
            $this->tasks->invoice($id, $request);
        }
        $this->invoices->create($clientId, $timeTaskId, $request->all());
        Session()->flash('flash_message', 'Invoice created');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @return mixed
     * @internal param int $id
     */
    public function marked()
    {
        Notifynder::readAll(\Auth::id());
        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->tasks->destroy($id);

        return redirect()->route('tasks.index');
    }
}
