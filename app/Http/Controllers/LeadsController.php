<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon;
use Session;
use Datatables;
use App\Models\Lead;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Repositories\Lead\LeadRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\Http\Requests\Lead\UpdateLeadFollowUpRequest;
use App\Repositories\Client\ClientRepositoryContract;
use App\Repositories\Setting\SettingRepositoryContract;

class LeadsController extends Controller
{
    protected $leads;
    protected $clients;
    protected $settings;
    protected $users;

    public function __construct(
        LeadRepositoryContract $leads,
        UserRepositoryContract $users,
        ClientRepositoryContract $clients,
        SettingRepositoryContract $settings
    )
    {
        $this->users = $users;
        $this->settings = $settings;
        $this->clients = $clients;
        $this->leads = $leads;
        $this->middleware('lead.create', ['only' => ['create']]);
        $this->middleware('lead.assigned', ['only' => ['updateAssign']]);
        $this->middleware('lead.update.status', ['only' => ['updateStatus']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('leads.index');
    }

    /**
     * Data for Data tables
     * @return mixed
     */
    public function anyData()
    {
       
        if ((!auth()->user()->hasRole('administrator'))&(!auth()->user()->hasRole('manager'))){
            $user_id=auth()->user()->id;
        $leads = Lead::select(
            ['id', 'title', 'user_created_id', 'client_id', 'user_assigned_id', 'contact_date','status']
        )->where('user_assigned_id',$user_id);
        }
        else $leads = Lead::select(
            ['id', 'title', 'user_created_id', 'client_id', 'user_assigned_id', 'contact_date','status']
        );
        
        return Datatables::of($leads)
            ->addColumn('titlelink', function ($leads) {
                return '<a href="leads/' . $leads->id . '" ">' . $leads->title . '</a>';
            })
            ->editColumn('user_created_id', function ($leads) {
                return $leads->creator->name;
            })
            ->editColumn('contact_date', function ($leads) {
                return $leads->contact_date ? with(new Carbon($leads->contact_date))
                    ->format('d/m/Y') : '';
            })
            ->editColumn('user_assigned_id', function ($leads) {
                return $leads->user->name;
            })
            ->editColumn('status', function ($leads) {
                switch ($leads->status){
                    case '1':
                        return '<span class="label label-primary">咨询中</span>';
                        break;
                    case '2':
                        return '<span class="label label-success">已完成</span>';
                        break;
                    case '3':
                        return '<span class="label label-danger">无兴趣</span>';
                        break;
                }

            })
            ->addColumn('edit', function ($lead) {
                return '<a href="leads/' .$lead->id . '" class="btn btn-success"> Edit</a>';
            })
            ->add_column('delete', '
                <form action="{{ route(\'leads.destroy\', $id) }}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" name="submit" value="Delete" class="btn btn-danger" onClick="return confirm(\'你确定要彻底删除该条咨询吗（不可恢复）?\')"">

            {{csrf_field()}}
            </form>')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leads.create')
            ->withUsers($this->users->getAllUsersWithDepartments())
            ->withClients($this->clients->listAllClients());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLeadRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadRequest $request)
    {
        $getInsertedId = $this->leads->create($request);
        Session()->flash('flash_message', 'Lead is created');
        return redirect()->route('leads.show', $getInsertedId);
    }

    public function updateAssign($id, Request $request)
    {
        $this->leads->updateAssign($id, $request);
        Session()->flash('flash_message', '成功重新分配顾问');
        return redirect()->back();
    }

    /**
     * Update the follow up date (Deadline)
     * @param UpdateLeadFollowUpRequest $request
     * @param $id
     * @return mixed
     */
    public function updateFollowup(UpdateLeadFollowUpRequest $request, $id)
    {
        $this->leads->updateFollowup($id, $request);
        Session()->flash('flash_message', '下一次跟进已更新');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Lead::find($id)){
        return view('leads.show')
            ->withLead($this->leads->find($id))
            ->withUsers($this->users->getAllUsersWithDepartments())
            ->withCompanyname($this->settings->getCompanyName());
        }
        else 
        {
            Session()->flash('flash_message_warning', '该条咨询不存在或已删除');
            return redirect()->back();
        
        }
    }

    /**
     * Complete lead
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateStatus($id, Request $request)
    {
        $this->leads->updateStatus($id, $request);
        Session()->flash('flash_message', '咨询状态已更新');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->laedss->destroy($id);

        return redirect()->route('leads.index');
    }
}
