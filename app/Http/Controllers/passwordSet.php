<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class passwordSet extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/SetPassword', [
            'status' => session('status'),
        ]);
    }

    public function storePassword(Request $request)
    {
        $ids = User::select('id')->where('employee_number', $request->employee_number)->get();

        foreach($ids as $id){
            $userId = $id->id;
            $user = User::findOrFail($userId);
            if($user->temporary_password == null){
                // return response()->json(['message' => 'すでにパスワードが設定されています。'], 400);
                return back()->with('message', 'すでにパスワードが設定されています。');
            } else {
                $user->password = Hash::make($request->password);
                $user->temporary_password = null;
                $user->save();
                return to_route('login');
            };

            
        };

        
        //     ->with([
        //         'status' => 'パスワードを設定しました',
        //     ]);
    }
}
