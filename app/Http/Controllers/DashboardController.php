<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;


// Load Models
use App\Models\User;
use App\Models\Hasil;
use App\Models\PesertaPPDB;
use PDF;



class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {

        $items = Hasil::with(['peserta.orang_tua'])->get();

        // Count
        $count_user = User::all()->count();
        $count_all_peserta = Hasil::all()->count();
        $count_menunggu_peserta = Hasil::where('status', 'MENUNGGU')->count();
        $count_ditolak_peserta = Hasil::where('status', 'DITOLAK')->count();
        $count_diterima_peserta = Hasil::where('status', 'DITERIMA')->count();


        return view('dashboard.index', compact(
            'items',
            'count_user',
            'count_all_peserta',
            'count_menunggu_peserta',
            'count_ditolak_peserta',
            'count_diterima_peserta'
        ));
    }

    public function detail($id)
    {
        $item = Hasil::with(['peserta.orang_tua'])->where('id', $id)->first();
        return view('dashboard.detail', compact('item'));
    }
    public function terima($id)
    {
        // Retrieve the 'name' field from 'tbl_peserta_ppdb' table based on the provided ID
        $namaPeserta = PesertaPPDB::where('id', $id)->value('nama'); // Assuming 'nama' is the field you want to retrieve
        $no_hp = PesertaPPDB::where('id', $id)->value('no_telp');

        if (!$namaPeserta) {
            // Handle the situation where data with the provided ID is not found
            Alert::error('Error', 'Data not found');
            return redirect()->route('home');
        }

        // Create a new user
        $user = new User();
        // Set the name of the user based on the retrieved 'nama' field
        $user->name = $namaPeserta;

        // Generate email with suffix based on ID
        $baseEmail = $namaPeserta . '@example.com';
        $suffix = $id; // Use the ID directly as the suffix

        // Check if the email already exists, if yes, append a random suffix
        if (User::where('email', $baseEmail)->exists()) {
            $randomEmail = $namaPeserta . $suffix . '@example.com';
        } else {
            $randomEmail = $baseEmail;
        }

        $user->email = $randomEmail;

        // Set the ID from tbl_peserta_ppdb table to id_peserta_ppdb field in User model
        $user->mobile_number = $no_hp;
        $user->id_peserta_ppdb = $id;

        $user->password = bcrypt($randomEmail);
        // Save the user object to the database
        $user->save();

        // Update the status of the Hasil item to 'DITERIMA'
        $item = Hasil::findOrFail($id);
        $item->status = 'DITERIMA';
        $item->update();

        // Display a success message
        Alert::success('Sukses', 'Simpan Data Sukses');

        // Redirect the user to the 'home' route
        return redirect()->route('home');
    }

    public function tolak($id)
    {
        $item = Hasil::findOrFail($id);

        $item->status = 'DITOLAK';
        $item->update();

        Alert::success('Sukses', 'Simpan Data Sukses');
        return redirect()->route('home');
    }

    public function download()
    {
        $data = PesertaPPDB::all();
        $pdf = PDF::loadView('laporan', compact('data')); // 'reports.report' is the blade file for your report
        return $pdf->download('laporan.pdf');
    }
}
