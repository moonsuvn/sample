<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\BikeRequest;
use App\Http\Requests;
use App\Models\User;
use App\Models\Bike;
use App\Models\Rider;
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
            //dd($bike_id);
            session()->flash('success','开锁成功正在计时');
            return redirect()->route('users.using',[$user->id,$value->id,'user'=>$user]);

        }
    }

    public function used(Bike $bike,BikeRequest $request,User $user,Rider $rider)
    {
        $end_lng=$request->input('longitude');    
        $end_lat=$request->input('latitude');
        $endtime=Carbon::now();
        $value=DB::table('bikes')->where('code',$request->input('code'))->first();
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_at'=>$endtime]);
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_lng'=>$end_lng]);
        Rider::where('user_id',$user->id)->orderByDesc('id')->first()->update(['end_lat'=>$end_lat]);
        $bike_id=Rider::where('user_id',$user->id)->orderByDesc('id')->first()->bike_id;
        DB::table('bikes')->where('id',$bike_id)->update(['lng' => $end_lng]);
        DB::table('bikes')->where('id',$bike_id)->update(['lat' => $end_lat]);
        DB::table('bikes')->where('id',$bike_id)->update(['is_riding' => 0]);
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

}
