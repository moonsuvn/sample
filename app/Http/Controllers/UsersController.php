<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BikeRequest;
use App\Http\Requests;
use App\Models\User;
use App\Models\Bike;
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

    public function using(User $user)
    {
        return view('bikes.using',compact('user'));
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

    public function riding(Bike $bikes, BikeRequest $request,User $user)
    {

        $this->validate($request, [
            'code' => 'required',
        ]);

        $value=DB::table('bikes')->where('code',$request->input('code'))->first();
        if(!$value)
        {
            session()->flash('error1','该车不存在');
            return redirect()->route('users.rider',$user->id)->with('error1');
        }
        else if ($value->is_riding){
            session()->flash('error2','该车正在使用中');
            return redirect()->route('users.rider',$user->id)->with('error2');
        }
        else {
            DB::table('bikes')->where('code', $request->input('code'))->update(['is_riding' => 1]);
            session()->flash('success','开锁成功正在计时');
            return redirect()->route('users.using',$user->id)->with('success');
        }



    }

}
