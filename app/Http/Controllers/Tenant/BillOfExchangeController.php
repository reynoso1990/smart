<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\BillOfExchangeCollection;
use App\Http\Resources\Tenant\BillOfExchangePaymentCollection;
use App\Http\Resources\Tenant\BillOfExchangeResource;
use App\Models\Tenant\BillOfExchange;
use App\Models\Tenant\BillOfExchangeDocument;
use App\Models\Tenant\BillOfExchangePayment;
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocumentCredit;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\PaymentMethodType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Traits\FilePaymentTrait;
use Modules\Finance\Traits\FinanceTrait;

class BillOfExchangeController  extends Controller
{

    use StorageDocument, FinanceTrait, FilePaymentTrait;

    /**
     * EmailController constructor.
     */
    public function __construct()
    {
    }
    public function pdf($id)
    {
        $document = BillOfExchange::find($id);
        $company = Company::active();
        $pdf = Pdf::loadView('tenant.bill_of_exchange.bill_of_exchange', compact(
            "company",
            "document"
        ))
            ->setPaper('a4', 'portrait');
        $filename = "Ticket de encomienda";

        return $pdf->stream($filename . '.pdf');
    }
    public function delete_payment($id){
        $bill_of_exchange_payment = BillOfExchangePayment::find($id);
        
        $bill_of_exchange_payment->delete();
        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }
    public function delete($id)
    {
        $bill_of_exchange = BillOfExchange::find($id);
        $documents = BillOfExchangeDocument::where('bill_of_exchange_id', $id)->get();
        foreach ($documents as $document) {
            if ($document->document) {
                $document->document->bill_of_exchange_id = null;
                $document->document->total_pending_payment = $document->document->total;
                $document->document->total_canceled = 0;
                $document->document->save();
            }
            $document->delete();
        }
        $bill_of_exchange->delete();

        return [
            'success' => true,
            'message' => 'Letra de cambio eliminada con éxito'
        ];
    }
    public function store_payment(Request $request)
    {
        $id = $request->input('id');

        DB::connection('tenant')->transaction(function () use ($id, $request) {

            $record = BillOfExchangePayment::firstOrNew(['id' => $id]);
            $record->fill($request->all());
            $record->save();
            $this->createGlobalPayment($record, $request->all());
            $this->saveFiles($record, $request, 'bill_of_exchange');
        });

        if ($request->paid == true) {
            $bill_of_exchange_payment = BillOfExchange::find($request->bill_of_exchange_id);
            $bill_of_exchange_payment->total_canceled = true;
            $bill_of_exchange_payment->save();
            $cash = Cash::where([
                ['user_id', auth()->user()->id],
                ['state', true],
            ])->first();
            $req = [
                'document_id' => null,
                'bill_of_exchange_id' => $request->bill_of_exchange_id
            ];

            $cash->cash_documents()->updateOrCreate($req);

            // }

        }

        // $this->createPdf($request->input('sale_note_id'));

        return [
            'success' => true,
            'message' => ($id) ? 'Pago editado con éxito' : 'Pago registrado con éxito'
        ];
    }
    public function document($id)
    {
        $bill_of_exchange = BillOfExchange::find($id);

        $total_paid = round(collect($bill_of_exchange->payments)->sum('payment'), 2);
        $total = $bill_of_exchange->total;
        $total_difference = round($total - $total_paid, 2);

        if ($total_difference < 1) {
            $bill_of_exchange->total_canceled = true;
            $bill_of_exchange->save();
        }

        return [
            'identifier' => "{$bill_of_exchange->series}-{$bill_of_exchange->number}",
            'full_number' =>  "{$bill_of_exchange->series}-{$bill_of_exchange->number}",
            'number_full' => "{$bill_of_exchange->series}-{$bill_of_exchange->number}",
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference,
            'paid' => $bill_of_exchange->total_canceled,
            'external_id' => $bill_of_exchange->id,
        ];
    }
    public function payments($bill_of_exchange_id)
    {
        $records = BillOfExchangePayment::where('bill_of_exchange_id', $bill_of_exchange_id)->get();

        return new BillOfExchangePaymentCollection($records);
    }
    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all(),
            'payment_destinations' => $this->getPaymentDestinations()
        ];
    }
    public function documentsCreditByClient(Request $request)
    {
        $request->validate([
            'client_id' => 'required|numeric|min:1',
        ]);
        $clientId = $request->client_id;
        $records = Document::without(['user', 'soap_type', 'state_type', 'currency_type'])

            ->select('series', 'number', 'id', 'date_of_issue', 'total')
            ->selectRaw('(SELECT SUM(payment) FROM document_payments WHERE document_id = documents.id) AS total_payment')
            ->selectRaw('documents.total - IFNULL((SELECT SUM(payment) FROM document_payments WHERE document_id = documents.id), 0) AS total')

            ->where('customer_id', $clientId)
            ->where('payment_condition_id', '02')
            ->whereIn('document_type_id', ['01', '03', '08'])
            ->whereIn('state_type_id', ['05'])
            // ->where('total_pending_payment', '>', 0)
            ->where('total_canceled', 0)
            ->orderBy('number', 'desc');

        $dateOfIssue = $request->date_of_issue;
        $dateOfDue = $request->date_of_due;
        if ($dateOfIssue && !$dateOfDue) {
            $records = $records->where('date_of_issue', $dateOfIssue);
        }

        if ($dateOfIssue && $dateOfDue) {
            $records = $records->whereBetween('date_of_issue', [$dateOfIssue, $dateOfDue]);
        }
        $sum_total = 0;
        $records = $records->take(20)
            ->get();
        $total = $records->sum('total');
        $payment_total = $records->sum('total_payment');
        $sum_total = $total - $payment_total;
        return response()->json([
            'success' => true,
            'data' => $records,
            'sum_total' => $sum_total,
        ], 200);
    }
    public function store(Request $request)
    {

        $documents_id = $request->input('documents_id');

        $documents = Document::whereIn('id', $documents_id);
        $document_payment = DocumentPayment::whereIn('document_id', $documents_id);
        $total_payments = $document_payment->sum('payment');
        $total_documents = $documents->sum('total');
        $total_pending = $total_documents - $total_payments;
        $date_of_due = $request->input('date_of_due');
        $bill_of_exchange = new BillOfExchange;
        $bill_of_exchange->series = "LC01";
        $bill_of_exchange->number = (BillOfExchange::count() == 0) ? 1 : BillOfExchange::orderBy('number', 'desc')->first()->number + 1;
        $bill_of_exchange->date_of_due = $date_of_due;
        $bill_of_exchange->total = $total_pending;
        $bill_of_exchange->establishment_id = auth()->user()->establishment_id;
        $bill_of_exchange->customer_id = $request->input('customer_id');
        $bill_of_exchange->user_id = auth()->id();
        $bill_of_exchange->save();

        $data_documents = $documents->get();
        foreach ($data_documents as $document) {
            $total = $document->total;
            $payment = $document->payments->sum('payment');
            $total_pending_payment = $total - $payment;
            $bill_of_exchange_document = new BillOfExchangeDocument;
            $bill_of_exchange_document->bill_of_exchange_id = $bill_of_exchange->id;
            $bill_of_exchange_document->document_id = $document->id;
            $bill_of_exchange_document->total = $total_pending_payment;
            $bill_of_exchange_document->save();
            $document->bill_of_exchange_id = $bill_of_exchange->id;
            $document->total_pending_payment = 0;
            $document->total_canceled = 1;
            $document->save();
        }


        return [
            'success' => true,
            'message' => 'Letra de cambio registrada con éxito',
            'id' => $bill_of_exchange->id
        ];
    }
    public function records(Request $request)
    {
        $column = $request->input('column');
        $value = $request->input('value');
        $records = BillOfExchange::query();

        if ($column && $value) {
            $records = $records->where($column, 'like', "%{$value}%");
        }
        $records = $records->orderBy('id', 'desc');
        return new BillOfExchangeCollection($records->paginate(config('tenant.items_per_page')));
    }
    public function record($id)
    {
        $record = BillOfExchange::findOrFail($id);
        return new BillOfExchangeResource($record);
    }
    public function columns()
    {
        return [
            'series' => 'Serie',
            'number' => 'Número',
            'date_of_due' => 'Fecha de vencimiento',
            'document_id' => 'Documento',
            'total' => 'Total',
        ];
    }
    public function index()
    {
        return view('tenant.bill_of_exchange.index');
    }
}
