<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Belanja;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\BelanjaResource;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BelanjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $belanja = Belanja::where('user_id', auth()->user()->id)->get();
        // dd($belanja);
        return DataTables::of($belanja)
            ->addIndexColumn()
            ->addColumn('tanggal', fn ($belanja) => $belanja->created_at->format('Y-m-d'))
            ->addColumn('voucher', fn ($belanja) => $belanja->total >= 1000000 ? 'Ada' : 'Tidak')
            ->addColumn('aksi', fn ($belanja) => view('tombol')->with('data', $belanja))
            ->make(true);
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
        // dd($request);
        $validator = Validator::make($request->all(), [
            'total' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->invoice != null) {
            $voucher = Voucher::firstWhere('kode_belanja', $request->invoice);
            $kode_voucher = $voucher->kode_voucher;
            Voucher::where('kode_belanja', $request->invoice)->update(['status' => 1]);
        } else {
            $kode_voucher = '';
        }

        //create data
        $data = Belanja::create([
            'total' => $request->total,
            'kode_voucher' => $kode_voucher,
            'kode_belanja' => Str::random(25),
            'user_id' => auth()->user()->id
        ]);

        if ($data->total >= 1000000) {
            Voucher::create([
                'user_id' => auth()->user()->id,
                'kode_voucher' => Str::random(25),
                'kode_belanja' => $data->kode_belanja,
                'status' => 0
            ]);
        }


        //return response
        return new BelanjaResource(true, 'Data Belanja Berhasil Ditambahkan!', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Belanja $belanja)
    {
        // $data = Voucher::where('kode_belanja', $belanja->kode_belanja)->where('status', 0)->whereBetween('created_at', [Carbon::now(), Carbon::now()->addMonths(4)])->first();
        $data = Voucher::where('kode_belanja', $belanja->kode_belanja)->get();

        return response()->json(['result' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Belanja $belanja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Belanja $belanja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Belanja $belanja)
    {
        $data = Belanja::find($belanja->id);
        // dd($data->kode_belanja);

        Belanja::where('id', $belanja->id)->delete();
        Voucher::where('kode_belanja', $data->kode_belanja)->delete();
        return new BelanjaResource(true, 'Data Belanja Berhasil Dihapus!', $data);
    }
}
