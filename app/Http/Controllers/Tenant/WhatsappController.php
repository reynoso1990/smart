<?php

namespace App\Http\Controllers\Tenant;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Quotation;
use Modules\Order\Models\OrderNote;
use App\Http\Controllers\Controller;
use App\Models\System\Configuration;
use Illuminate\Support\Facades\Storage;
use Modules\Purchase\Models\PurchaseOrder;
use App\Models\System\Configuration as Config;
use Illuminate\Support\Facades\Log;
use Modules\Purchase\Models\PurchaseQuotation;

class WhatsappController extends Controller
{

    public function questions()
    {
        $company = Company::active();
        $sender = $company->ws_api_phone_number_id;

        $api_whatsapp = env('API_WHATSAPP');
        return view('tenant.whatsapp.questions', compact('sender','api_whatsapp'));
    }
    public function account_whatsapp(){
        $company = Company::active();
        $sender = $company->ws_api_phone_number_id;
        $api_whatsapp = env('API_WHATSAPP');
        $company=Company::first();
        $name = strtoupper($company->name);
        return view('tenant.whatsapp.whatsapp', compact('sender','api_whatsapp','name'));
    }
    public function answers()
    {
        $company = Company::active();
        $sender = $company->ws_api_phone_number_id;
         $api_whatsapp = env('API_WHATSAPP');
         return view('tenant.whatsapp.answers', compact('sender','api_whatsapp'));
    }

    public function sendwhatsapp(Request $request)
    {
        $company = Company::active();
        $configuration=Configuration::first();
        if($company->ws_api_phone_number_id != null && $configuration->whatsapp != null ){
            $sender_number = $company->ws_api_phone_number_id;
        }else if( $company->ws_api_phone_number_id==null ){
            $sender_number = $configuration->whatsapp;
        }else if( $configuration->whatsapp == null ){
            $sender_number = $company->ws_api_phone_number_id;
        }
        //dd($configuration->whatsapp,$configuration->whatsapp,$company->ws_api_phone_number_id);
        if($configuration->whatsapp=="" && $configuration->whatsapp==null && $company->ws_api_phone_number_id == null){
            return response()->json(
                [
                'success' =>false,
                'message' => 'Debe configurar el numero de WhatsApp',

            ]);
        }
        if ($request->type_id == "COT") {
            $document = Quotation::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("quotation" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        } else if ($request->type_id == "OC") {
            $document = PurchaseOrder::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("purchase_order" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        } else if ($request->type_id == "PD") {
            $document = OrderNote::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("order_note" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        } else if ($request->type_id == "T00") {
            $document = Dispatch::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("pdf" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        } else if ($request->type_id == "COTC") {
            $document = PurchaseQuotation::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("purchase_quotation" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));

        } else if ($request->type_id == "FACT") {
            $document = Document::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("pdf" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        } else if ($request->type_id == "NV") {
            $document = SaleNote::find($request->input('id'));
            $document_download = file_get_contents(Storage::disk('tenant')->path("sale_note" . DIRECTORY_SEPARATOR . $document->filename . ".pdf"));
        }
            //$document=Document::find($request->input('id'));
            $url = env('API_WHATSAPP');
            $token = "95lvBOXccsu9EKCpnWIH37bHfp3Alik1Uk5NUEqfM9y2Aq5nD4";
            $this->client = new Client([
                'http_errors' => false,
                'verify' => false,
                'stream' => false,
                'headers' => [
                    'User-Agent' => 'Testing 1.0'
                ]
            ]);
            try {

                $response =$this->client->post($url.'/api/multimedia', [
                  //  'headers' => ['Authorization' => 'Bearer '.$token],

                    'multipart' => [
                        [
                            'name'     => 'number',
                            'contents' => trim($request->customer_telephone)
                        ],
                        [
                            'name'     => 'message',
                            'contents' => $request->mensaje
                        ],
                        [
                            'name'     => 'sender',
                            'contents' => $sender_number
                        ],
                    [
                        'name'     => 'file',
                        'contents' => $document_download,
                        'filename' => $document->filename . ".pdf"
                    ],

                    ]
                ]);
                $txt=$response->getBody()->getContents();
                $data=json_decode($txt, true);

                return response()->json(
                    ['success' => true,
                    'message' => $data['success']==false ? $data['message'] : "Se envio el mensaje con éxito",
                    'origen' => $sender_number,
                    'destinatario' =>$request->numero,
                    'tipo_mensaje' =>"media",

                ]);
            }catch(\GuzzleHttp\Exception\RequestException $e){
               return $e->getResponse();
            }
    }
    public function statusWhatsapp($sender_number){

        $curl = curl_init();
        try {
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('API_WHATSAPP').'/api/status',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "sender":"932242181",
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

                dd($response);
                return $response;
                // $response =$this->client->post($url.'/api/status', [

                //     'multipart' => [
                //         [
                //             'name'     => 'sender',
                //             'contents' => $sender_number
                //         ],
                //      ]
                // ]);
                // dd($response);
                // $data=$response->getBody()->getContents();
                // return response()->json(
                //     [
                //     'success' => $data['success'],
                //     'message' => $data['message'],

                // ]);
             } catch(\Exception $e) {
                return [
                  "message" =>$e->getMessage(),
                  "line" =>$e->getLine(),
                ];
                 exit;
            }
    }
}
