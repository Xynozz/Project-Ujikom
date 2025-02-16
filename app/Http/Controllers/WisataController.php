<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $wisata = Wisata::all();
        $kategori = Kategori::all();

        return view('admin.wisata.index', compact('wisata', 'kategori'));
    }

    public function create()
    {
        $wisata = Wisata::all();
        $kategori = Kategori::all();

        return view('admin.wisata.create', compact('wisata', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_wisata' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'gambar' => 'required',
            'short_video' => 'required',
            'thumbnail' => 'required',
            'jam_operasional' => 'required',
            'status' => 'required',
            'kategori_id' => 'required',
        ]);

        $wisata = new Wisata();
        $wisata->nama_wisata = $request->nama_wisata;
        $wisata->deskripsi = $request->deskripsi;
        $wisata->lokasi = $request->lokasi;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filePath = $file->store('images/gambar', 'public');
            $wisata->gambar = $filePath;
        }

        if ($request->hasFile('short_video')) {
            $file = $request->file('short_video');
            $filePath = $file->store('images/short_video', 'public');
            $wisata->short_video = $filePath;
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filePath = $file->store('images/thumbnail', 'public');
            $wisata->thumbnail = $filePath;
        }

        $wisata->jam_operasional = $request->jam_operasional;
        $wisata->status = $request->status;
        $wisata->kategori_id = $request->kategori_id;

        $wisata->save();

        return redirect()->route('wisata.index')->with('success', 'Wisata berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $wisata = Wisata::findOrFail($id);
        $kategori = Kategori::all();

        return view('admin.wisata.edit', compact('wisata', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_wisata' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'gambar' => 'required',
            'short_video' => 'required',
            'thumbnail' => 'required',
            'jam_operasional' => 'required',
            'status' => 'required',
            'kategori_id' => 'required',
        ]);

        $wisata = Wisata::findOrFail($id);
        $wisata->nama_wisata = $request->nama_wisata;
        $wisata->deskripsi = $request->deskripsi;
        $wisata->lokasi = $request->lokasi;

        if ($request->hasFile('gambar')) {
            // Jika sebelumnya sudah ada gambar, hapus gambar lama
            if ($wisata->gambar && Storage::disk('public')->exists($wisata->gambar)) {
                Storage::disk('public')->delete($wisata->gambar);
            }

            $file = $request->file('gambar');
            $filePath = $file->store('images/gambar', 'public');
            $wisata->gambar = $filePath;
        }
        if ($request->hasFile('short_video')) {
            // Jika sebelumnya sudah ada gambar, hapus gambar lama
            if ($wisata->short_video && Storage::disk('public')->exists($wisata->short_video)) {
                Storage::disk('public')->delete($wisata->short_video);
            }

            $file = $request->file('short_video');
            $filePath = $file->store('images/short_video', 'public');
            $wisata->short_video = $filePath;
        }
        if ($request->hasFile('thumbnail')) {
            // Jika sebelumnya sudah ada gambar, hapus gambar lama
            if ($wisata->thumbnail && Storage::disk('public')->exists($wisata->thumbnail)) {
                Storage::disk('public')->delete($wisata->thumbnail);
            }

            $file = $request->file('thumbnail');
            $filePath = $file->store('images/thumbnail', 'public');
            $wisata->thumbnail = $filePath;
        }

        $wisata->jam_operasional = $request->jam_operasional;
        $wisata->status = $request->status;
        $wisata->kategori_id = $request->kategori_id;
        $wisata->save();

        return redirect()->route('wisata.index')->with('success', 'Wisata berhasil diupdate!');
    }

    public function destroy($id)
    {
        $wisata = Wisata::findOrFail($id);

        if ($wisata->gambar && Storage::disk('public')->exists($wisata->gambar)) {
            Storage::disk('public')->delete($wisata->gambar);
        }

        if ($wisata->short_video && Storage::disk('public')->exists($wisata->short_video)) {
            Storage::disk('public')->delete($wisata->short_video);
        }

        if ($wisata->thumbnail && Storage::disk('public')->exists($wisata->thumbnail)) {
            Storage::disk('public')->delete($wisata->thumbnail);
        }

        $wisata->delete();

        return redirect()->route('wisata.index')->with('success', 'Wisata berhasil dihapus!');
    }
}
