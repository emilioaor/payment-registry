<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Mail\PaymentMail;
use App\Payment;
use App\Service\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $payment = Payment::query()
            ->where($request->field, $request->value)
            ->whereNotNull($request->field)
            ->first();

        return response()->json(['success' => true, 'data' => $payment]);
    }

    /**
     * Change status
     *
     * @param Request $request
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request, $id, $status)
    {
        $payment = Payment::query()->uuid($id)->firstOrFail();
        $payment->fill($request->all());
        $payment->status = $status;
        $payment->status_changed_by = Auth::user()->id;
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

    /**
     * Payment in PDF
     *
     * @param $id
     * @return mixed
     */
    public function pdf($id)
    {
        $payment = Payment::query()->uuid($id)->firstOrFail();
        $pdf = \PDF::loadView('payment.pdf', compact('payment'))->setPaper('letter');

        return $pdf->stream();
    }

    /**
     * Send email
     *
     * @param int $id
     * @param $lang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail($id, $lang, Request $request)
    {
        App::setLocale($lang);
        $payment = Payment::query()->uuid($id)->firstOrFail();

        Mail::to($request->emails)->send(new PaymentMail($payment));

        AlertService::alertSuccess(__('alert.processSuccessfully'));

        return response()->json(['success' => true]);
    }

    /**
     * List banks
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function banks()
    {
        $banks = Bank::all();

        return view('payment.banks', compact('banks'));
    }

    /**
     * Store bank
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeBank(Request $request)
    {
        $bank = new Bank($request->all());
        $bank->save();

        AlertService::alertSuccess(__('alert.processSuccessfully'));

        return response()->json(['success' => true]);
    }
}
