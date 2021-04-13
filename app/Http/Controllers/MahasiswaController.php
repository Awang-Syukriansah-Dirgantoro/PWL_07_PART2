<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        $mahasiswas = Mahasiswa::with('kelas')->get(); // Mengambil semua isi tabel
        $posts = Mahasiswa::orderBy('Nim', 'desc')->paginate(5);
        //dd($mahasiswas);
        return view('users.index', compact('mahasiswas'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        $kelas = Kelas::all();
        return view('users.create', ['kelas' => $kelas]);
    }
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);

        $kelas = Kelas::find($request->get('Kelas'));

        $mahasiswa = new Mahasiswa();
        $mahasiswa->Nim = $request->get('Nim');
        $mahasiswa->Nama = $request->get('Nama');
        $mahasiswa->Jurusan = $request->get('Jurusan');
        $mahasiswa->No_Handphone = $request->get('No_Handphone');
        $mahasiswa->E_mail = $request->get('E_mail');
        $mahasiswa->Tanggal_Lahir = $request->get('Tanggal_Lahir');
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::with('kelas')->where('Nim', $Nim)->first();
        return view('users.detail', compact('Mahasiswa'));
    }
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::with('kelas')->where('Nim', $Nim)->first();
        $kelas = Kelas::all();
        return view('users.edit', compact('Mahasiswa', 'kelas'));
    }
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);

        $kelas = Kelas::find($request->get('Kelas'));

        $mahasiswa = Mahasiswa::find($Nim);
        $mahasiswa->Nim = $request->get('Nim');
        $mahasiswa->Nama = $request->get('Nama');
        $mahasiswa->Jurusan = $request->get('Jurusan');
        $mahasiswa->No_Handphone = $request->get('No_Handphone');
        $mahasiswa->E_mail = $request->get('E_mail');
        $mahasiswa->Tanggal_Lahir = $request->get('Tanggal_Lahir');
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
    public function cari(Request $request)
    {
        $object = $request->cari;
        $mahasiswas = Mahasiswa::where('Nama', 'like', $object . '%')->get();
        return view('users.cari', compact('mahasiswas'));
    }
};
