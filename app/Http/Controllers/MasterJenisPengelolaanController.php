<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JenisPengelolaan;


class MasterJenisPengelolaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JenisPengelolaan::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_jenis', 'ilike', "%{$search}%")
                    ->orWhere('kode', 'ilike', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $jenisPengelolaans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master.jenis.index', compact('jenisPengelolaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.jenis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:jenis_pengelolaans',
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_hari' => 'required|integer|min:1',
            'kategori' => 'required|in:pemanfaatan,pemindahtanganan,penghapusan,sewa,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        JenisPengelolaan::create($validated);

        return redirect()->route('master.jenis-pengelolaan.index')
            ->with('success', 'Jenis Pengelolaan berhasil ditambahkan.');
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
    public function edit(JenisPengelolaan $jenisPengelolaan)
    {
        return view('master.jenis.edit', compact('jenisPengelolaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisPengelolaan $jenisPengelolaan)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:jenis_pengelolaans,kode,' . $jenisPengelolaan->id,
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_hari' => 'required|integer|min:1',
            'kategori' => 'required|in:pemanfaatan,pemindahtanganan,penghapusan,sewa,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jenisPengelolaan->update($validated);

        return redirect()->route('master.jenis-pengelolaan.index')
            ->with('success', 'Jenis Pengelolaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisPengelolaan $jenisPengelolaan)
    {
        $jenisPengelolaan->update(['status' => 'nonaktif']);

        return redirect()->route('master.jenis-pengelolaan.index')
            ->with('success', 'Jenis Pengelolaan berhasil dinonaktifkan.');
    }
}
