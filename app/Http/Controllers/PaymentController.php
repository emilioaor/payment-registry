<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Payment;
use App\Service\AlertService;
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

        AlertService::alertSuccess(__('alert.processSuccessfully'));

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
        $payment = Payment::query()->uuid($id)->firstOrFail();
        $banksAvailable = Bank::all();

        return view('payment.form', compact('payment', 'banksAvailable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::query()->uuid($id)->firstOrFail();
        $payment->fill($request->all());
        $payment->save();

        AlertService::alertSuccess(__('alert.processSuccessfully'));

        return response()->json(['success' => true]);
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

    /**
     * Change status
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id, $status)
    {
        $payment = Payment::query()->uuid($id)->firstOrFail();
        $payment->status = $status;
        $payment->save();

        AlertService::alertSuccess(__('alert.processSuccessfully'));

        return response()->json(['success' => true]);
    }

    /**
     * Report
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        $banksAvailable = Bank::all();

        return view('payment.report', compact('banksAvailable'));
    }

    /**
     * Report process
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportProcess(Request $request)
    {
        $payments = Payment::query()->report($request->all())->get();

        return response()->json(['success' => true, 'data' => $payments]);
    }
}
