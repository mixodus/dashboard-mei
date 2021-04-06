<?php

namespace App\Http\Controllers\one\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\One\ApiLibrary;

class FreelancerController extends Controller
{
	public function __construct()
	{
		$this->apiLib = new ApiLibrary;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboardFreelancer(Request $request)
	{
		$token = $request->session()->get('token');
		$put['data'] = ['token' => $token];

		try{
			$this->apiLib->setParams($put['data']);
			$result = $this->apiLib->generate('GET','/api/dashboard/referral/allWeb');
			if (!$result) {
				throw new \Exception("Failed get dashboard freelancer");
			}

			$freelancer = $result->data;
			return view('one.freelancer.dashboardFreelancer', compact('freelancer'));

		} catch(\Exception $e) {

			$err_messages = $e->getMessage(); 
			return view('one.errors.errors', compact('err_messages'));
		}
	}

	public function formcreate(Request $request)
	{
		$token = $request->session()->get('token');
		$put['data'] = ['token' => $token];
		
		$this->apiLib->setParams($put['data']);
		$result = $this->apiLib->generate('GET','/api/dashboard/referral/allWeb');
		
		if($result->status == true)
		{
			$data = $result->data;
			return view('one.freelancer.FreelancerCreate',compact('data'));
		}
	}

	public function store(Request $request){
		$token = $request->session()->get('token','');
		$this->setToken($token);
		$general_data = $request->only(['source','referral_name','referral_email','referral_contact_no','referral_status'
		,'fee','job_position']);
		$multipart_data = array();
		
		if($request->file('file')){
			$multipart_data['file']   = $request->file;
		}
		
		$general_data['referral_status'] = 'Pending';
		$general_data['source'] ='web';
		$general_data['referral_employee_id'] =$request->session()->get('user_id');;
		
		$result = $this->MULTIPART(env("API_URL").'/api/dashboard/referral',$general_data,$multipart_data);

		if($result['status'] == true)
		{
			return redirect('/dashboard/freelancer')->with('success', $result['message']);
		}else{
			return redirect()->back()->with('error', $result['message']);
		} 
	}

	public function formUpdate(Request $request, $id){
		$token = $request->session()->get('token');
		$put['data'] = ['token' => $token];
		
		$this->apiLib->setParams($put['data']);
		$result = $this->apiLib->generate('GET','/api/dashboard/referral/update/'.$id);
		if($result->status == true)
		{
			$data = $result->data;
			return view('one.freelancer.FreelancerUpdate',compact('data'));
		}
	}

	public function storeUpdate(Request $request, $id){
		$token = $request->session()->get('token','');
		$this->setToken($token);
		$general_data = $request->only(['source','referral_name','referral_email','referral_contact_no','referral_status'
		,'fee','job_position']);
		$multipart_data = array();
		
		if($request->file('file')){
			$multipart_data['file']   = $request->file;
		}
		
		$general_data['referral_status'] = 'Pending';
		$general_data['source'] ='web';
		$general_data['referral_employee_id'] =$request->session()->get('user_id');;
		
		$result = $this->MULTIPART(env("API_URL").'/api/dashboard/referral/update/'.$id, $general_data, $multipart_data);

		if($result['status'] == true)
		{
			return redirect('/dashboard/freelancer')->with('success', $result['message']);
		}else{
			return redirect()->back()->with('error', $result['message']);
		} 
	}

	public function formStatus(Request $request, $id){
		$token = $request->session()->get('token');
		$put['data'] = ['token' => $token];
		
		$this->apiLib->setParams($put['data']);
		$result = $this->apiLib->generate('GET','/api/dashboard/referral/update/'.$id.'/status');
		if($result->status == true)
		{
			$data = $result->data;
			return view('one.freelancer.FreelancerStatusUpdate',compact('data'));
		}
	}
	public function storeStatus(Request $request, $id){
		$token = $request->session()->get('token','');
		$this->setToken($token);
		$put['data'] = ['referral_status' => $request->referral_status];
		
		//dd($put['data']);
		$this->apiLib->setParams($put['data']);
		$result = $this->apiLib->generate('POST','/api/dashboard/referral/update/'.$id.'/status');

		if($result->status == true)
		{
			return redirect('/dashboard/freelancer')->with('success', $result->message);
		}else{
			return redirect()->back()->with('error', $result['message']);
		} 
	}

	//belom terpakai && code belum di ubah
	public function dashboardFreelanceShow(Request $request, $id)
	{
		$token = $request->session()->get('token');
		$put['data'] = ['token' => $token];

		try{
			$this->apiLib->setParams($put['data']);
			$result = $this->apiLib->generate('GET','/api/log/dashboard-log/show?byDashboardLog='.$id);

			if (!$result) {
				throw new \Exception("Failed get dashboard log detail");
			}

			$log = $result->data;
			return view('one.log.dashboardLogView', compact('log'));

		} catch(\Exception $e) {

			$err_messages = $e->getMessage(); 
			return view('one.errors.errors', compact('err_messages'));
		}
	}
}
