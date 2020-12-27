<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'exists']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with(['bank'])
            ->pending()
            ->search($request->search)
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banksAvailable = Bank::all();

        return view('payment.form', compact('banksAvailable'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $payment = new Payment($request->all());
        $payment->capture = $request->capture ? $payment->attachDocument($request->capture, 'capture') : null;
        $payment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Check if payment exists
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exists(Request $request)
    {
        $payment = Payment::query()->where('transaction_number', $request->transaction_number)->first();

        return response()->json(['success' => true, 'data' => $payment]);
    }
}
