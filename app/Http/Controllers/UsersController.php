<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\BikeRequest;
use App\Http\Requests;
use App\Models\User;
use App\Models\Bike;
use App\Models\Rider;
use App\Models\Scatter;
use Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', [            
            'except' => ['show', 'create', 'store']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);

    }
    
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function payCenter(User $user)
    {
        return view('users.pay',compact('user'));
    }

    public function rider(User $user)
    {
        return view('bikes.rider',compact('user'));
    }

    public function using(User $user,Bike $bikes,BikeRequest $request)
    {
        return view('bikes.using',compact(['user','bike']));
    }

    public function payBalance(User $user,Request $request)
    {
        $this->validate($request,[
            'pay' => 'required']);

        $data=[];
        if ($request->pay) {
            $data['pay'] = $request->pay;
            $user->increment('balance',$request->input('pay'));
        }

        $user->update($data);

        session()->flash('success', '充值成功！');

        return redirect()->route('users.payCenter', $user->id);
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function riding(Bike $bike, BikeRequest $request,User $user,Rider $rider)
    {

        $this->validate($request, [
            'code' => 'required',
        ]);

        $value=DB::table('bikes')->where('code',$request->input('code'))->first();
        if(!$value)
        {
            session()->flash('error1','该车不存在');
            return redirect()->route('users.rider',$user->id)->with('error','该车不存在');
        }
        else if ($value->is_riding){
            session()->flash('error2','该车正在使用中');
            return redirect()->route('users.rider',$user->id)->with('error','该车正在使用中');
        }
        else {
            DB::table('bikes')->where('code', $request->input('code'))->update(['is_riding' => 1]);
            $start_lng=$value->lng;
            $start_lat=$value->lat;
            $bike_id=$value->id;
            $user_id=$user->id;
            $rider=new Rider;
            $rider->user_id = $user_id;
            $rider->bike_id = $bike_id;
            $rider->start_at = Carbon::now();
            $rider->start_lng = $start_lng;
            $rider->start_lat = $start_lat;
            $rider->save();

            $data = array('_id' =>$bike_id ,'status' =>'正在使用中' );
            $data=json_encode($data);
            $url="http://yuntuapi.amap.com/datamanage/data/update";
            $key="4b6bf63daf54c876f1603104c504d4f4";
            $tableid="5b060ee4305a2a668877b2eb";
            $url=$url.'?key='.$key.'&tableid='.$tableid.'&data='.$data;
            /* 发送请求 */
            $get = file_get_contents($url);
            $result = json_decode(($get));
            $status = $result->status;//请求状态
            $message = $result->info;//请求返回信息
            //dd($bike_id);
            session()->flash('success','开锁成功正在计时');
            return redirect()->route('users.using',[$user->id,$value->id,'user'=>$user]);

        }
    }

    public function used(Bike $bike,BikeRequest $request,User $user,Rider $rider)
    {
        /*$url="http://yuntuapi.amap.com/datamanage/data/update";
        $key="4b6bf63daf54c876f1603104c504d4f4";
        $tableid="5b060ee4305a2a668877b2eb";
        $request=$url.'?key='.$key.'&tableid'.$tableid;*/

        $end_lng=$request->input('longitude'); 
        $end_lat=$request->input('latitude');
        $scatter_lng=(string)$end_lng;
        $scatter_lat=(string)$end_lat;
        $scatter=$scatter_lng.','.$scatter_lat;
        $endtime=Carbon::now();
        $value=DB::table('bikes')->where('code',$request->input('code'))->first();

        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_at'=>$endtime]);
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_lng'=>$end_lng]);
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_lat'=>$end_lat]);
        $bike_id=Rider::where('user_id',$user->id)->orderByDesc('id')->first()->bike_id;
        DB::table('bikes')->where('id',$bike_id)->update(['lng' => $end_lng]);
        DB::table('bikes')->where('id',$bike_id)->update(['lat' => $end_lat]);
        DB::table('bikes')->where('id',$bike_id)->update(['is_riding' => 0]);
        Scatter::where('id',$bike_id)->update(['lnglat' => $scatter]);

        $data = array('_id' =>$bike_id ,'_location' =>$scatter,'status' =>'空闲' );
        $data=json_encode($data);
        $url="http://yuntuapi.amap.com/datamanage/data/update";
        $key="4b6bf63daf54c876f1603104c504d4f4";
        $tableid="5b060ee4305a2a668877b2eb";
        $url=$url.'?key='.$key.'&tableid='.$tableid.'&data='.$data;
        /* 发送请求 */
        $get = file_get_contents($url);
        $result = json_decode(($get));
        $status = $result->status;//请求状态
        $message = $result->info;//请求返回信息
        /*$bikeid=(string)$bike_id;
        $data = array('_id' =>$bike_id ,'_location' =>$scatter );
        $data=json_encode($data);
        $url="http://yuntuapi.amap.com/datamanage/data/update";
        $key="4b6bf63daf54c876f1603104c504d4f4";
        $tableid="5b060ee4305a2a668877b2eb";
        $url=$url.'?key='.$key.'&tableid='.$tableid;
        //dd($url);
        $opts = array('http' => array('method' => 'POST','header' => 'Content-type: application/x-www-form-urlencoded','content' => $data));
        $content = stream_context_create($opts);
        $result = file_get_contents($url,true,$content);
        dd($result);*/
        $over=Rider::where('user_id',$user->id)->orderByDesc('id')->first()->start_at;
        $differTime=Carbon::now()->diffInSeconds($over);
        $money=$differTime*0.1;
        $balance=$user->balance;
        $balance=$balance-$money;
        User::where('id',$user->id)->update(['balance'=>$balance]);
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['money'=>$money]);
        return response([
            'message'=>'还车成功']);
    }

    public function track(User $user,Bike $bike,BikeRequest $request,Rider $rider)
    {
        $lnging=$request->input('longitude'); 
        $lating=$request->input('latitude');
        $scatter_lng=(string)$lnging;
        $scatter_lat=(string)$lating;
        $scatter=$scatter_lng.','.$scatter_lat;
        $user_id=(string)$user->id;
        $bike_id=Rider::where('user_id',$user->id)->orderByDesc('id')->first()->bike_id;
        $data = array('_name' =>$user_id ,'_location' =>$scatter);
        $data=json_encode($data);
        $url="http://yuntuapi.amap.com/datamanage/data/create";
        $key="4b6bf63daf54c876f1603104c504d4f4";
        $tableid="5b08ca9c7bbf1916a5a95851";
        $url=$url.'?key='.$key.'&tableid='.$tableid.'&data='.$data;
        /* 发送请求 */
        $get = file_get_contents($url);
        $result = json_decode(($get));
        $status = $result->status;//请求状态
        $message = $result->info;//请求返回信息
        return response([
            'message'=>'定位成功']);
    }

    public function riders(User $user,Rider $rider,Request $request)
    {

        $riders = Rider::where('user_id',$user->id)->orderByDesc('id')->get();
        //$riders = DB::table('riders')->get();
        return view('users.riders', compact('riders'));
     
    }

    public function cloudtrackpage(User $user,Rider $rider,Request $request)
    {
        //$rider_id=$rider->id;
        return view('users.cloudtrack',compact('rider_id'));
    }

    public function cloudtrack(User $user,Rider $rider,Request $request)
    {
        $rider_id=$rider->id;
        return view('users.cloudtrack',compact('rider_id'));
    }

    public function setcloudtrack(User $user,Rider $rider,Request $request)
    {
        $rider_id=$rider->id;
        $value=Rider::where('id',$rider_id)->first();
        $user_id=$value->user_id;
        $start_time=$value->start_at;
        $end_time=$value->end_at;
        $location = [];

        $url="http://yuntuapi.amap.com/datasearch/local";
        $key="4b6bf63daf54c876f1603104c504d4f4";
        $tableid="5b08ca9c7bbf1916a5a95851";
        $keywords=$user_id;
        $city="全国";
        $url=$url.'?key='.$key.'&tableid='.$tableid.'&keywords='.$keywords.'&city='.$city;
        /* 发送请求 */
        $get = file_get_contents($url);
        $result = json_decode(($get));
        $status = $result->status;//请求状态
        $message = $result->info;//请求返回信息
        $count = $result->count;//返回结果总数目
        $data = $result->datas;//返回的数据

        for ($i=0; $i < count($data); $i++) { 
            $_location=$data[$i]->_location;
            $location[$i]=$_location;
        }
        return response([
            'message'=>$location]);
    }


}
