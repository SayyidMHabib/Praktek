<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Belanja;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voucher = Voucher::where('user_id', auth()->user()->id)->get();
        return DataTables::of($voucher)
            ->addIndexColumn()
            ->addColumn('tanggal', fn ($voucher) => $voucher->created_at->format('Y-m-d'))
            ->addColumn('status', fn ($voucher) => view('status')->with('data', $voucher))
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
