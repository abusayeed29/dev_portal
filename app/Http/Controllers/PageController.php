<?php

namespace App\Http\Controllers;

use App\Models\AstComponent;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Notifications\TicketEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$response = Http::get('http://localhost/nrel_soft/napi/nrel/employee.php');
        //$data = json_decode($response->body());
        //return view('frontend.home', compact('data'));
        return view('frontend.home');
    }

    public function home2()
    {
        return view('frontend.home2');
    }

    public function policies()
    {
       // return view('frontend.policy');
        return view('frontend.coming');
    }

    public function aboutUs()
    {
        return view('frontend.about');
    }
    public function nFunctions()
    {
        return view('frontend.functions');
    }

    public function forms()
    {
        return view('frontend.forms');
    }
    public function comingSoon()
    {
        return view('frontend.coming');
    }

    

    public function getTaskbyDepartment( Request $request){

        $tasks = DB::table('pr_tasks')->where('department_id', $request->department_id)->pluck('title', 'id');

        return response()->json(['tasks'=>$tasks]);

    }

    public function getUserByDepartment( Request $request){

        $emps = DB::table('users')->where('department_id', $request->department_id)->pluck('name', 'id', 'emp_id');

        return response()->json(['emps'=>$emps]);

    }

    public function getDeparmentByCompany( Request $request){

        $deparments = DB::table('departments')->where('company_id', $request->company_id)->get();

        return response()->json(['deparments'=>$deparments]);

    }

    public function getDeparmentDesignationByCompany( Request $request){

        $deparments = DB::table('departments')
                    ->where('company_id', $request->company_id)
                    ->orderBy('name','asc')
                    ->get();
        $designations = DB::table('designations')
                        ->where('company_id', $request->company_id)
                        ->orderBy('name','asc')
                        ->get();

        return response()->json(['deparments'=>$deparments, 'designations' => $designations]);

    }


    public function getComponentValues(Request $request){

        $component_data = AstComponent::where('ast_lookup_id', $request->component_id)->get();

        return response()->json(['component_data'=>$component_data]);
    }

    public function blobDownload(){
        return view('oracle.download');
    }

    public function getEmployes(){

        $response = Http::get('http://localhost/nrel_soft/napi/nrel/employee.php');

        $datas = $response->body();

        foreach(json_decode($datas) as $data){
           echo $data->EMPLOYEE_NAME. '<br>'; 
        }


        //return view('oracle.download');
    }

    public function attendence()
    {
        $response = Http::get('http://localhost/nrel_soft/napi/nrel/read.php', [ 'emp_id' => '0944']);

        $datas = $response->body();

        foreach(json_decode($datas) as $data){
           echo $data->EMPLOYEE_NAME. '<br>'; 
        }
        
        return view('frontend.attendence');
    }

    public function getLeave()
    {
        $response = Http::get('http://localhost/oracle_php/nrelleave.php', ['emp_id' =>'0944']);
        $datas = $response->body();
        $data = json_decode($datas);
        for ($i = 0; $i < count($data); $i++) {

            DB::table('leave_balances')
                ->where('leave_type_id', $data[$i]->LEAVE_TYPE_ID)
                ->where('employee_id', $data[$i]->EMPLOYEE_ID)
                ->update([
                    'leave_days'=> $data[$i]->LEAVE_DAYS,
                    'taken_days'=> $data[$i]->TAKEN_DAYS, 
                    'cur_bal'=> $data[$i]->CUR_BAL,
                    'carried_days'  => $data[$i]->CARRIED_DAYS
                ]);
           //dd($succ);
        }

        // foreach(json_decode($datas) as $data){
        //    echo $data->LEAVE_TYPE_ID. '<br>'; 
        //    $data = array(
        //         'company_id'=>$data->COMPANY_ID,
        //         'user_id'=>$data->LEAVE_TYPE_ID,
        //         'leave_type_id'=>$data->LEAVE_TYPE_ID,
        //         'leave_days'=>$data->LEAVE_DAYS,
        //         'carried_days'=>$data->LEAVE_TYPE_ID,
        //         'taken_days'=>$data->TAKEN_DAYS,
        //         'cur_bal' =>$data->CUR_BAL,
        //         'leave_year' =>$data->LEAVE_YEAR,
        //         'employee_id' =>$data->EMPLOYEE_ID,
        //    );
        // }

        return view('oracle.nrel');
    }


    public function getEmployee(){
        return view('backend.admin.employee.pull');
    }

    public function getSession()
    {
        return view('frontend.kill-session');
    }

    public function killSession()
    {
        /*IP = 150.230.104.6
PORT = 1523
Service Name : TESTPDB
ID : IT_SAYEED
Password : nrel123456 */

        $response = Http::get('http://localhost/nrel_soft/napi/nrel/employee.php');

        $datas = $response->body();

        foreach(json_decode($datas) as $data){
           echo $data->EMPLOYEE_NAME. '<br>'; 
        }

    }


    public function serverStatus()
    {
        return view('frontend.server-status');
    }
    
    public function getServerStatus()
    {
        //$ping = exec("ping -n 1 192.168.6.1", $out, $status);

        //dd($status);

        $networks = DB::table('computer_networks')
                    ->orderBy('location','ASC')
                    ->get();
        // $results = [];
        // for($i=0; $i<count($networks); $i++){
        //     $ip = $networks[$i][0];
        //     $ping = exec("ping -n 1 $ip", $out, $status);
        //     $results[] = $status;
        // }

        // dd( $results);

        $output = "";

        foreach($networks as $network){
            exec("ping -n 1 $network->ip", $out, $status);

            $chast = "";
            if($status==0){
                $chast = 'online'; 
            }else{
                $chast = 'offline'; 
            }
            $output .= "<div class='col-md-3'>
                        <div class='card mt-4 bg-light $chast'>
                            <div class='card-body'>
                                <p class='card-title'>".$network->name."</p>
                                <h5 class='card-title'>".$network->ip."</h5>
                               <p>".$chast."</p>
                            </div>
                        </div>
                    </div>";
        }

        return response()->json(['networks'=>$output]);
       // return view('frontend.server-status');
    }

    // Notification test
    public function sendNotification(){
        // $user = User::find(4); //31 97
        //$user->notify(new TicketEmailNotification($invoice));
        // Notification::send($user, new TicketEmailNotification());

        $users = User::whereIn('id', [4, 4])->get();
        foreach($users as $user){
            Notification::send($user, new TicketEmailNotification('Sayeed', 'Body description'));
        }

        //return redirect()->back();
        
    }


    public function womensNetwork(){

        return view('frontend.womens-network');

    }



}
