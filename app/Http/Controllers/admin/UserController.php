<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::latest('created_at');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name','like','%'.$request->get('keyword').'%');
            $users = $users->orWhere('email','like','%'.$request->get('keyword').'%');
        }

        $users = $users->paginate(10);

        return view('admin.users.list',[
            'users' => $users
        ]);
    }

    public function create(Request $request) {

        return view('admin.users.create',[
            
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'role' => 'required',

        ]);

        if ($validator->passes()) {
            
            $user = new User; 
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;

            $user->phone = str_replace(' ', '', $user->phone);
            if (!str_starts_with($user->phone, '+62')) {
                $user->phone = '+62' . ltrim($user->phone, '0');
            }

            $user->address = $request->address;
            $user->status = $request->status;
            $user->role = $request->role;
            $user->save();

            $message = 'User Added Successfully';

            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id) {

        $user = User::find($id);

        if ($user == null) {
            $message = 'User Not Found';

            session()->flash('error',$message);
            return redirect()->route('users.index');
        }

        return view('admin.users.edit',[
            'user' => $user
        ]);
    }

    public function update(Request $request, $id) {

        $user = User::find($id);

        if ($user == null) {
            $message = 'User Not Found';
            session()->flash('error',$message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'phone' => 'required',

        ]);

        if ($validator->passes()) {
            
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->phone = $request->phone;

            $user->phone = str_replace(' ', '', $user->phone);
            if (!str_starts_with($user->phone, '+62')) {
                $user->phone = '+62' . ltrim($user->phone, '0');
            }

            $user->status = $request->status;
            $user->save();

            $message = 'User Added Successfully';

            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id) {

        $user = User::find($id);

        if ($user == null) {
            $message = 'User Not Found';
            session()->flash('error',$message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $user->delete();

        $message = 'User Deleted Successfully';
            session()->flash('success',$message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
    }
}
