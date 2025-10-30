<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua user dengan role admin_desa dan eager-load desanya
        $operators = User::where('role', 'admin_desa')->with('desa')->get();
        return view('operator.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $desas = Desa::all();
        return view('operator.create', compact('desas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'desa_id' => 'required|exists:desas,id',
            'nik' => 'required|string|size:16|unique:users,nik',
            'tanggal_lahir' => 'required|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_desa',
            // terms di-set default accepted agar memenuhi constraint database
            'terms' => 'accepted',
            'desa_id' => $request->desa_id,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
        ]);

        return redirect()->route('operator.index')->with('success', 'Operator berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $operator
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $operator)
    {
        // Hanya bisa menghapus user dengan role admin_desa
        if ($operator->role !== 'admin_desa') {
            abort(404);
        }
        $operator->delete();
        return redirect()->route('operator.index')->with('success', 'Operator berhasil dihapus.');
    }
}