<?php

namespace App\Http\Controllers;

use App\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk dengan filter dan pencarian
     */
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('cari')) {
            $query->where('nama_produk', 'LIKE', '%' . $request->cari . '%')
                  ->orWhere('kode_produk', 'LIKE', '%' . $request->cari . '%');
        }

        $produks = $query->paginate(10);
        $kategori = Produk::distinct()->pluck('kategori');

        return view('produk.index', ['produks' => $produks, 'kategori' => $kategori]);
    }

    /**
     * Tampilkan form tambah produk
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produks|max:20',
            'nama_produk' => 'required|max:100',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|max:50',
            'stok' => 'required|integer|min:0'
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')->with('sukses', 'Produk berhasil ditambahkan');
    }

    /**
     * Tampilkan detail produk
     */
    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    /**
     * Tampilkan form edit produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'kode_produk' => 'required|max:20|unique:produks,kode_produk,' . $id,
            'nama_produk' => 'required|max:100',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|max:50',
            'stok' => 'required|integer|min:0'
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')->with('sukses', 'Produk berhasil diperbarui');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('sukses', 'Produk berhasil dihapus');
    }
}
