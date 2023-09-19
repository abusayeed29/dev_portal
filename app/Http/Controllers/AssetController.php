<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AstComponent;
use App\Models\AstDetails;
use App\Models\AstHistory;
use App\Models\AstLookUp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function astDashboard()
     {
 
         $help_perm = getReadPermission(12, 1);
 
         if ($help_perm == true) {
             $astByTypes = DB::table('ast_look_ups')
                 ->join('assets', 'ast_look_ups.id', '=', 'assets.ast_type')
                 ->select('ast_look_ups.id as ast_look_ups_id', 'ast_look_ups.data_values as asset_type', DB::raw('count(assets.id) as total'))
                 ->groupBy('ast_look_ups.id')
                 ->orderBy('ast_look_ups.data_values', 'asc')
                 ->get();
             $astTypelabels = [];
             $astTypedata = [];
             foreach ($astByTypes as $astByType) {
                 $astTypelabels[] = $astByType->asset_type;
                 $astTypedata[] = $astByType->total;
             }
 
             $astByCompanies = DB::table('companies')
                 ->join('assets', 'assets.company_id', '=', 'companies.comp_id')
                 ->select('companies.comp_id as company_id', 'companies.slug as comp_name', DB::raw('count(assets.id) as total'))
                 ->groupBy('assets.company_id')
                 ->orderBy('companies.comp_id', 'ASC')
                 ->get();
 
             $astByComplabels = [];
             $astByCompdata = [];
 
             foreach ($astByCompanies as $astByCompany) {
                 $astByComplabels[] = $astByCompany->comp_name;
                 $astByCompdata[] = $astByCompany->total;
             }
 
             return view('backend.common.asset.ast-dashboard', ['astTypelabels' => $astTypelabels, 'astTypedata' => $astTypedata, 'astByComplabels' => $astByComplabels, 'astByCompdata' => $astByCompdata]);
         } else {
             return back()->with('error', 'Access denied');
         }
     }
 
     public function index()
     {
         $help_perm = getReadPermission(12, 1);
 
         if ($help_perm == true) { 
             $components = AstLookUp::where('data_keys', 'ast.component')->get();
             return view('backend.common.asset.manage', ['components' => $components]);
         } else {
             return back()->with('error', 'Access denied');
         }
     }
 
     public function getAssetData(Request $request)
     {
 
         if ($request->ajax()) {
 
             $query = Asset::orderBy('id', 'DESC')->get();
 
             return DataTables::of($query)
                 ->addIndexColumn()
                 ->addColumn('brandName', function (Asset $asset) {
                     return $asset->astBrand->data_values;
                 })
                 ->addColumn('username', function (Asset $asset) {
                     return $asset->astCurnUsername->username;
                 })
                 ->addColumn('action', function ($data) {
                     $button = '<a href="' . route('asset.show', $data->id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>';
                     return $button;
                 })
                 ->rawColumns(['username', 'action'])
                 ->toJson();
         }
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
         //dd($request->all());
 
         $help_perm = getCreatePermission(12, 1);
 
         if ($help_perm == true) {
 
             $validator = Validator::make($request->all(), [
                 'ast_name'        => 'required',
                 'asset_user'      => 'required',
                 'brand'           => 'required',
                 'company_id'      => 'required',
             ]);
 
             if ($validator->fails()) {
                 return response()->json(['errors' => $validator->errors()->all()]);
             }
 
             $ast_id = Asset::latest('id')->first();
             $last_aid = !empty($ast_id->id) ? $ast_id->id : 0;
             
             $asset_tag  = $request->company_id . random_int(10000000, 99999999) . $last_aid + 1;
 
             // create account
             $asset                  = new Asset;
             $asset->ast_name        = $request->ast_name;
             $asset->company_id      = $request->company_id;
             $asset->ast_tag         = $asset_tag;
             $asset->purchase_date   = $request->purchase_date;
             $asset->brand           = $request->brand;
             $asset->model           = $request->model;
             $asset->serial_number   = $request->serial_number;
             $asset->ast_type        = $request->asset_type;
             $asset->supplier        = $request->supplier;
             $asset->ast_ip          = $request->ipaddress;
             $asset->warnty_expdate  = $request->warranty;
             $asset->ast_value       = $request->ast_value;
             $asset->user_id         = Auth::user()->id;
             $asset->status          = $request->ast_status;
             $asset->description     = $request->description;
             $asset->save();
 
             // if (count($request->component) > 0) :
             //     for ($i = 0; $i < count($request->component); $i++) {
             //         $ast_detatil = new AstDetails;
             //         $ast_detatil->ast_lookup_id  = $request->component[$i];
             //         $ast_detatil->ast_cmpnt_id    = $request->value_info[$i];
             //         $ast_detatil->asset_id      = $asset->id;
             //         $ast_detatil->save();
             //     }
             // endif;
 
             // asset details
             // if($request->asset_user):
             //     $ast_history                = new AstHistory;
             //     $ast_history->ast_user_id   = $request->asset_user;
             //     $ast_history->asset_id      = $asset->id;
             //     $ast_history->ast_status    = $asset->ast_status;
             //     $ast_history->save();
             // endif;
 
             // asset history
             if ($request->asset_user) :
                 $ast_history                = new AstHistory;
                 $ast_history->ast_user_id   = $request->asset_user;
                 $ast_history->asset_id      = $asset->id;
                 $ast_history->wh_assign_id  = Auth::user()->id;
                 $ast_history->assign_date   = new Carbon();
                 $ast_history->save();
             endif;
 
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
         $help_perm = getCreatePermission(12, 1);
 
         if ($help_perm == true) {
 
             $asset = Asset::where('id', $id)->first();

             $components = AstLookUp::where('data_keys', 'ast.component')->get();

             return view('backend.common.asset.asset-single', ['single' => $asset, 'components' => $components]);
         } else {
             return back()->with('error', 'Access denied');
         }
     }
 
     public function astComponentStore(Request $request)
     {
 
         $validator = Validator::make($request->all(), [
             'components'     => 'required',
             'asset_id'      => 'required',
         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()->all()]);
         }

         $asset = Asset::find($request->asset_id);
         $asset->components = $request->components;
         $asset->save();

        /*$astdetails = AstDetails::updateOrCreate(
             ['id' => $request->component_id],
             ['ast_lookup_id' => $request->component, 'ast_cmpnt_id' => $request->value_info, 'asset_id' => $request->asset_id]
         );
 
         $componentDetails = AstDetails::leftJoin('ast_look_ups', 'ast_details.ast_lookup_id', '=', 'ast_look_ups.id')
             ->leftJoin('ast_components', 'ast_details.ast_cmpnt_id', '=', 'ast_components.id')
             ->where('ast_details.id', $astdetails->id)
             ->select('ast_details.id', 'ast_components.cmpnt_data', 'ast_look_ups.data_values')
             ->first();
             */
         return response()->json(['success' => 'Your ticket has been updated successfully']);
     }


     public function astNewComponent(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'asset_id'         => 'required',
             'component_tag'    => 'required',
         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()->all()]);
         }

        //  $asset = Asset::find($request->asset_id);
        //  $asset->components = $request->components;
        //  $asset->save();
        $componentID = (Int)$request->component_tag;

        $astdetails = AstDetails::updateOrCreate(
                        ['id' => $request->component_id],
                        ['component_id' => $componentID , 'asset_id' => $request->asset_id]
                    );
 
         $componentDetails = AstDetails::leftJoin('ast_components', 'ast_details.component_id', '=', 'ast_components.id')
             ->where('ast_details.component_id', $astdetails->component_id)
             ->select('ast_details.id','ast_details.asset_id','ast_components.*')
             ->first();
             

         return response()->json(['success' => 'Your ticket has been updated successfully','assetComponent' => $componentDetails]);
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         //
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assetBuiltInComponent(Request $request)
    {
        if($request->has('q')){

            $search = $request->q;

            if($search == ''):
                $cmpnents = AstLookUp::select('id', 'data_values', 'data_keys')
                            ->where('data_type','ast.component')
                            ->get();
            else:
                $cmpnents = AstLookUp::select('id', 'data_values', 'data_keys')
                            ->where('data_values', 'LIKE', "%$search%")
                            ->where('data_type','ast.component')
                            ->get();
            endif;
           
            $response = array();
            foreach($cmpnents as $cmpnent){
                $response[] = array(
                    'id' => $cmpnent->data_values, 
                    'text' => $cmpnent->data_values,
                );
            }
            return response()->json($response);

        }
    }


}
