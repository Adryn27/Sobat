<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemasok=Pemasok::orderby('updated_at','desc')->get();
        return view('backend.layout.v_data.supplier', [
            'judul'=>'Supplier',
            'subjudul'=>'Daftar Pemasok',
            'index'=>$pemasok,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Pemasok::create($request->all());

        return redirect()->back()->with('success', 'Data berhasil Tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'no_telp'       => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
        ]);

        $pemasok = Pemasok::findOrFail($id);
        $pemasok->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemasok = Pemasok::findOrFail($id);
        $pemasok->delete();
        return redirect()->route('backend.supplier.index')->with('success', 'Data berhasil dihapus');
    }
}
