<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function findEmployeeById(Request $request)
    {
        //$users = [];
        if ($request->has('q')) {

            $search = $request->q;

            if ($search == '') :
                $users = User::select("users.id", "users.name", "users.emp_id", "users.company_id", "companies.slug as comp_name")
                    ->leftJoin('companies', 'companies.comp_id', 'users.company_id')
                    ->whereNotIn('users.role_id', [1, 2])
                    ->get();
            else :
                $users = User::select("users.id", "users.name", "users.emp_id", "users.company_id", "companies.slug as comp_name")
                    ->leftJoin('companies', 'companies.comp_id', 'users.company_id')
                    ->whereNotIn('users.role_id', [1])
                    ->where('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.emp_id', 'LIKE', "%$search%")
                    ->get();
            endif;

            $response = array();
            foreach ($users as $user) {
                $response[] = array(
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->comp_name . '-' . $user->emp_id . ') ',
                );
            }
            return response()->json($response);
        }

        // $data = $request->all();
        // $query  = $data['query'];
        // $filter_data = User::select('id','name')
        //                 ->where('name', 'LIKE', '%'.$query.'%')
        //                 ->get();

        // return response()->json($filter_data);


    }


    public function getworkPlaceByCompany(Request $request)
    {
        $workPlaces = DB::table('emp_work_places')->where('company_id', $request->company_id)->get();

        return response()->json(['work_places' => $workPlaces]);
    }
}
