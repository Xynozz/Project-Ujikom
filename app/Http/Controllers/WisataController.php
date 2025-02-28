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
        $kategori = Kategori::all();

        return view('admin.wisata.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_wisata' => 'required|string|max:255|unique:wisatas',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_video' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'status' => 'required|in:aktif,tidak_aktif',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->except(['gambar', 'short_video', 'thumbnail']);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('wisata/gambar', 'public');
        }

        // Upload short_video
        if ($request->hasFile('short_video')) {
            $data['short_video'] = $request->file('short_video')->store('wisata/videos', 'public');
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('wisata/thumbnails', 'public');
        }

        Wisata::create($data);

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil ditambahkan');
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
            'nama_wisata' => 'required|string|max:255|unique:wisatas,nama_wisata,' . $id,
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:20480', // 20MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'status' => 'required|in:aktif,tidak_aktif',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $wisata = Wisata::findOrFail($id);
        $data = $request->except(['gambar', 'short_video', 'thumbnail']);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            if ($wisata->gambar && Storage::disk('public')->exists($wisata->gambar)) {
                Storage::disk('public')->delete($wisata->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('wisata/gambar', 'public');
        }

        // Upload short_video
        if ($request->hasFile('short_video')) {
            if ($wisata->short_video && Storage::disk('public')->exists($wisata->short_video)) {
                Storage::disk('public')->delete($wisata->short_video);
            }
            $data['short_video'] = $request->file('short_video')->store('wisata/videos', 'public');
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($wisata->thumbnail && Storage::disk('public')->exists($wisata->thumbnail)) {
                Storage::disk('public')->delete($wisata->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('wisata/thumbnails', 'public');
        }

        $wisata->update($data);

        return redirect()->route('wisatas.index')
            ->with('success', 'Data wisata berhasil diupdate');
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

        return redirect()->route('wisatas.index')
            ->with('success', 'Data wisata berhasil dihapus');
    }
}
