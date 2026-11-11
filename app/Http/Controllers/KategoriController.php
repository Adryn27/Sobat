<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::orderBy('created_at', 'asc')->withCount('obat')->latest()->get();
        return view('Backend.layout.v_data.kategori', [
            'judul' => 'Category',
            'subjudul'=>'Daftar Kategori',
            'index' => $kategori
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
            'nama_kategori' => 'required|string|unique:kategori,nama_kategori|max:100',
        ]);
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
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
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id,
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        if ($kategori->obat()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal dihapus karena masih terikat dengan data obat');
        }

        $kategori->delete();
        return redirect()->route('backend.category.index')->with('success', 'Data berhasil dihapus');
    }
}
