<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Satker;


class MasterSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Satker::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_satker', 'ilike', "%{$search}%")
                    ->orWhere('kode_satker', 'ilike', "%{$search}%");
            });
        }

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $satkers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master.satker.index', compact('satkers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.satker.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_satker' => 'required|string|max:20|unique:satkers',
            'nama_satker' => 'required|string|max:255',
            'instansi_induk' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:255',
            'pic_kontak' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Satker::create($validated);

        return redirect()->route('master.satker.index')
            ->with('success', 'Satker berhasil ditambahkan.');
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
    public function edit(Satker $satker)
    {
        return view('master.satker.edit', compact('satker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Satker $satker)
    {
        $validated = $request->validate([
            'kode_satker' => 'required|string|max:20|unique:satkers,kode_satker,' . $satker->id,
            'nama_satker' => 'required|string|max:255',
            'instansi_induk' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:255',
            'pic_kontak' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $satker->update($validated);

        return redirect()->route('master.satker.index')
            ->with('success', 'Satker berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Satker $satker)
    {
        $satker->update(['status' => 'nonaktif']);

        return redirect()->route('master.satker.index')
            ->with('success', 'Satker berhasil dinonaktifkan.');
    }
}
