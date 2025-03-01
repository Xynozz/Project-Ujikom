<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wisata   = Wisata::all();
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
            'deskripsi'   => 'nullable|string',
            'lokasi'      => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_video' => 'nullable|mimes:mp4,mov,avi|max:51200', // 50MB max
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka'    => 'nullable|date_format:H:i',
            'jam_tutup'   => 'nullable|date_format:H:i',
            'status'      => 'required|in:aktif,tidak_aktif',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->except(['gambar', 'short_video', 'thumbnail']);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('wisata/gambar', 'public');
        }

        // Simpan video jika diunggah
        if ($request->hasFile('short_video')) {
            $videoPath           = $request->file('short_video')->store('videos', 'public');
            $data['short_video'] = $videoPath;
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
        $wisata   = Wisata::findOrFail($id);
        $kategori = Kategori::all();

        return view('admin.wisata.edit', compact('wisata', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_wisata' => 'required|string|max:255|unique:wisatas,nama_wisata,' . $id,
            'deskripsi'   => 'nullable|string',
            'lokasi'      => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_video' => 'nullable|mimes:mp4,mov,avi|max:51200', // 50MB max
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka'    => 'nullable|date_format:H:i',
            'jam_tutup'   => 'nullable|date_format:H:i',
            'status'      => 'required|in:aktif,tidak_aktif',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $wisata = Wisata::findOrFail($id);
        $data   = $request->except(['gambar', 'short_video', 'thumbnail']);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            if ($wisata->gambar && Storage::disk('public')->exists($wisata->gambar)) {
                Storage::disk('public')->delete($wisata->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('wisata/gambar', 'public');
        }

        // Cek apakah ada file baru yang diunggah
        if ($request->hasFile('short_video')) {
            // Hapus video lama jika ada
            if ($wisata->short_video && Storage::exists('public/' . $wisata->short_video)) {
                Storage::delete('public/' . $wisata->short_video);
            }

            // Simpan video baru
            $videoPath           = $request->file('short_video')->store('videos', 'public');
            $data['short_video'] = $videoPath;
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($wisata->thumbnail && Storage::disk('public')->exists($wisata->thumbnail)) {
                Storage::disk('public')->delete($wisata->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('wisata/thumbnails', 'public');
        }

        $wisata->update($data);

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil diupdate');
    }

    public function destroy($id)
    {
        $wisata = Wisata::findOrFail($id);

        // Hapus gambar utama
        if ($wisata->gambar && Storage::exists('public/' . $wisata->gambar)) {
            Storage::delete('public/' . $wisata->gambar);
        }

        // Hapus thumbnail
        if ($wisata->thumbnail && Storage::exists('public/' . $wisata->thumbnail)) {
            Storage::delete('public/' . $wisata->thumbnail);
        }

        // Hapus short video jika ada
        if ($wisata->short_video && Storage::exists('public/' . $wisata->short_video)) {
            Storage::delete('public/' . $wisata->short_video);
        }

        // Hapus semua ulasan terkait wisata ini
        // $wisata->ulasan()->delete();

        // Hapus wisata dari database
        $wisata->delete();

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil dihapus');
    }
}
