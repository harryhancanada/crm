<?php
namespace App\Http\Controllers;
use Excel;
use Session;
use Validator;
use DB;
use Storage;
use File;
use App\Repositories\User\UserRepositoryContract;
use App\Http\Requests;
use App\Models\User;
use App\Models\Setting;
use App\Models\Document;
use Illuminate\Http\Request;



class DocumentsController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */


    public function upload(Request $request, $id)
    {
        $settings = Setting::findOrFail(1);
        $companyname = $settings->company;
        if (!is_dir(public_path() . '/files/' . $companyname)) {
            mkdir(public_path() . '/files/' . $companyname, 0777, true);
        }
        $file = $request->file('file');
        $destinationPath = public_path() . '/files/' . $companyname;
        $filename = str_random(8) . '_' . $file->getClientOriginalName();
        $fileOrginal = $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $size = $file->getClientSize();
        $mbsize = $size / 1048576;
        $totaltsize = substr($mbsize, 0, 4);
        if ($totaltsize > 15) {
            Session::flash('flash_message', '文件大小不能超过 15MB');
            return redirect()->back();
        }
        $input = array_replace(
            $request->all(),
            ['path' => "$filename", 'size' => "$totaltsize", 'file_display' => "$fileOrginal", 'client_id' => $id]
        );
        $document = Document::create($input);
        Session::flash('flash_message', '文件成功上传(刷新后出现)');
        return redirect()->route('clients.index');

    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function import(Request $request)
    {
        $rules = [
            'file' => 'required',
            'num_records' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        // process the form
        if (!$validator->fails()) {
            return Redirect(route('clients.create'))->withErrors($validator);
        } else {
            try {
                Excel::load('public\imports\contacts.xlsx', function ($reader) {
                    foreach ($reader->toArray() as $row) {
                        if ($row['name'] && $row['company'] && $row['email'] && $row['user'] == "") {
                            Session::flash('flash_message_warning', '您未填写必要信息');
                        }
                    }
                });
                Session::flash('flash_message', 'Users uploaded successfully.');
                // return redirect(route('clients.index'));
            } catch (\Exception $e) {
                Session::flash('flash_message_warning', $e->getMessage());
                //return redirect(route('clients.index'));
            }
        }
    }
   public function destroy($id)
	{
       try {
    	$document=Document::find($id);
    	$settings = Setting::findOrFail(1);
    	$companyname = $settings->company;
    	$path = $document->path;
    	$destroy_path = (public_path() . '/files/' . $companyname .'/' . $path);
    	File::delete(public_path() . '/files/' . $companyname .'/' . $path);
    	$document->delete();
    	Session()->flash('flash_message', '该文件已彻底删除');
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('flash_message_warning', '');
        }
        return redirect()->back();
	}
    }
