<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $kategori = Kategori::all();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            'icon' => 'required',
        ]);

        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->deskripsi = $request->deskripsi;

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filePath = $file->store('images/icon', 'public');
            $kategori->icon = $filePath;
        }

        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil disimpan!');;
    }

    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return view('admin.kategori.edit', compact('kategori'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            'icon' => 'required',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->deskripsi = $request->deskripsi;

        if ($request->hasFile('icon')) {
            // Jika sebelumnya sudah ada gambar, hapus gambar lama
            if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
                Storage::disk('public')->delete($kategori->icon);
            }

            $file = $request->file('icon');
            $filePath = $file->store('images/icon', 'public');
            $kategori->icon = $filePath;
        }

        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');;
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
            Storage::disk('public')->delete($kategori->icon);
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
