<?php

namespace App\Http\Controllers;

use App\Models\SlaDepartment;
use App\Models\SlaTask;
use App\Models\SlaTaskCategory;
use Illuminate\Http\Request;

class SlaTaskCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slas = SlaTaskCategory::with('slaSubCategory')
            ->whereNull('parent_id')
            ->get();

        $sla_departments = SlaDepartment::all();

        return view('backend.head.sla-category', ['slas' => $slas, 'sla_departments' => $sla_departments]);
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

    public function departmentTask(Request $request)
    {
        $slug = $request->department;

        $dep_id = SlaDepartment::where('slug', $slug)->select('id')->first();

        if ($request->ajax()) {

            $slas = SlaTaskCategory::with('slaSubCategory')
                ->where('department_id', $dep_id->id)
                ->whereNull('parent_id')
                ->get();

            $data = '<div class="tab-pane fade show active " id="' . $slug . '" role="tabpanel" aria-labelledby="home-line-tab"><div class="table-responsive"> <table class="table table-hover mb-0 task_category_table"><thead> <tr><th scope="col" class="pt-0">#</th><th scope="col" class="pt-0">Task Name</th> <th scope="col" class="pt-0">Role</th><th scope="col" class="pt-0">Lead Time</th><th scope="col" class="pt-0">TTR</th><th scope="col" class="pt-0">Action</th> </tr></thead><tbody>';

            $i = 1;
            $j = 1;
            foreach ($slas as $single) {
                $data .= '<tr class="align-middle bg-secondary">
                            <td>' . $i . '</td>
                                <th colspan="3">' . $single->name . '</th>
                                <td style="color:#ddd">' . $single->lead_time . '</td>
                                <td></td>
                            </tr>';

                if (count($single->slaSubCategory) > 0) :
                    $j = 1;
                    $first = true;
                    foreach ($single->slaSubCategory as $sub_single) {
                        $data .= '<tr class="align-middle">
                                    <td>' . $i . '.' . $j . '</td>
                                    <td>' . $sub_single->name . '</td>
                                    <td>' . $sub_single->username . '(' . $sub_single->desg_name . ')</td>
                                    <td>' . $sub_single->lead_time . '</td>';
                        if ($first == true) :
                            $data .= '<td class="align-middle bg-light" rowspan="' . count($single->slaSubCategory) . '">
                                                    ' . SlaTaskCategory::where('parent_id', $sub_single->parent_id)->sum('lead_time') . '
                                                </td>';
                            $first = false;
                        endif;
                        $data .= '<td><a href="#">View</a></td>
                                </tr>';
                        $j++;
                    }
                endif;

                $i++;
            }

            $data .=  '</tbody> </table></div></div>';

            return response()->json(['success' => 'success', 'tasks' => $data]);
        }
    }

}
