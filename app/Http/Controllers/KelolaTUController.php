<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;

// use App\Models\KelolaTU;

use App\Models\User;

use Illuminate\Http\Request;

class KelolaTUController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = User::all();
        return view('dashboards.pekerjaan_ortu.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboards.pekerjaan_ortu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pekerjaan' => 'required',
        ]);

        $kelola_TU = new User();
        $kelola_TU->nama_pekerjaan = $request->nama_pekerjaan;
        $kelola_TU->save();

        Alert::success('Success', 'Data Saved Successfully');
        return redirect()->route('pekerjaan_ortu.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $kelola_TU)
    {
        // Implementation for showing a specific resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $kelola_TU)
    {
        return view('dashboards.pekerjaan_ortu.edit', compact('kelola_TU'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $kelola_TU)
    {
        $request->validate([
            'nama_pekerjaan' => 'required',
        ]);

        $kelola_TU->update([
            'nama_pekerjaan' => $request->nama_pekerjaan,
        ]);

        Alert::success('Success', 'Data Updated Successfully');
        return redirect()->route('pekerjaan_ortu.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $kelola_TU)
    {
        $kelola_TU->delete();

        Alert::success('Success', 'Data Deleted Successfully');
        return redirect()->route('pekerjaan_ortu.index');
    }
}
