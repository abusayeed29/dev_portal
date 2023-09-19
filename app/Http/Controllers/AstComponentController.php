<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AstComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AstComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // $components = AstComponent::orderBy('id', 'DESC')->get();

        if ($request->ajax()) {
 
            $query = AstComponent::orderBy('id', 'DESC')->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('username', function ($data) {
                    return $data->user_id;
                })
                ->addColumn('asset_type', function ($data) {
                    return $data->astType->data_values;
                })
                ->addColumn('cmpnt_name', function ($data) {
                    return $data->cmpntName->data_values;
                })
                ->addColumn('status', function ($data) {
                    return $data->componentStatus->data_values;
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('asset.component.show', $data->id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>';
                    return $button;
                })
                ->rawColumns(['cmpnt_name', 'action'])
                ->toJson();
        }

        return view('backend.common.asset.component.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $help_perm = getCreatePermission(12, 1);
 
        if ($help_perm == true) {

            $validator = Validator::make($request->all(), [
                'cmpnt_name'       => 'required',
                'serial_no'    => 'required',
                'asset_type'       => 'required',
                'company_id'       => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $ast_id = AstComponent::latest('id')->first();
            $last_aid = !empty($ast_id->id) ? $ast_id->id : 0;
            
            $component_tag  = $request->company_id . random_int(10000000, 99999999) . $last_aid + 1;

            $component                    = new AstComponent;
            $component->cmpnt_tag         = $component_tag;
            $component->cserial_no        = $request->serial_no;
            $component->cmpnt_name        = $request->cmpnt_name;
            $component->ast_type        = $request->asset_type;
            $component->brand             = $request->brand;
            $component->cmp_value         = $request->cmp_value;
            $component->purchase_date     = $request->purchase_date;
            $component->warranty          = $request->warranty_date;
            $component->user_id           = Auth::user()->id;
            $component->company_id        = $request->company_id;
            $component->status            = $request->status;
            $component->description       = $request->description;
            $component->save();

            return response()->json(['success' => 'Your ticket has been submitted successfully']);
        } else {
            return back()->with('error', 'Access denied');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $component = AstComponent::find($id);
        return view('backend.common.asset.component.show', ['component' => $component]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $component = AstComponent::find($id);
        return response()->json($component);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function componentSearch(Request $request)
    {
        if($request->has('q')){
            
            $search = $request->q;
            if($search == ''):
                $cmpnents = [];
            else:
                $cmpnents = AstComponent::select('id', 'cmpnt_tag', 'cmpnt_name')
                            ->where('cserial_no', 'LIKE', "%$search%")
                            ->orWhere('cmpnt_tag', 'LIKE', "%$search%")
                            ->get();
            endif;
           
            $response = array();
            foreach($cmpnents as $cmpnent){
                $response[] = array(
                    'id' => $cmpnent->id, 
                    'text' => $cmpnent->cmpnt_tag,
                );
            }
            return response()->json($response);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
