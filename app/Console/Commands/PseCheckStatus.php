<?php

namespace App\Console\Commands;

use Facades\App\Http\Controllers\Tenant\DocumentController;
use Illuminate\Console\Command;
use App\Traits\CommandTrait;
use App\Models\Tenant\{
    Company,
    Configuration,
    Document
};
use App\Services\PseServiceTask;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

class PseCheckStatus extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pse:check';
    protected $company = null;
    protected $token = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of PSE documents';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    private function formatNumber($number, $zeros = 8)

    {

        return str_pad($number, $zeros, '0', STR_PAD_LEFT);
    }
    function format_to_check($documents)
    {
        $xml = new SimpleXMLElement('<NewDataSet></NewDataSet>');
        foreach ($documents as $document) {
            $table1 = $xml->addChild('Table1');
            $table1->addChild('numruc', $this->company->number);
            $table1->addChild('altido', $document->document_type_id);
            $table1->addChild('sersun', $document->series);
            $table1->addChild('numsun',  $this->formatNumber($document->number));
        }
        $xmlString = $xml->asXML();
        $xml_string = str_replace("\n", "", $xmlString);
        $startIndex = strpos($xml_string, '<NewDataSet>');
        $newDataSetXml = substr($xml_string, $startIndex);
        return $newDataSetXml;
    }
    function get_documents($state_type_id)
    {
       try{
        $documents = DB::connection('tenant')->table('documents')
        ->where('date_of_issue', '>=', '2023-09-01')
        ->where('state_type_id', $state_type_id)
        ->get();

    return $documents;
       }catch(\Exception $e){
       }
    }
    public function handle()
    {
        $company = Company::firstOrFail();
        $this->company = $company;
        if($company->pse && $company->pse_url&& $company->pse_token && $company->number){
        // if($company->number == "20604665966"){

        // $to_send = $this->get_documents('01');
        // $to_validate = $this->get_documents('03');
        // $to_anulate = $this->get_documents('13');
       
        foreach (['03', '13'] as $state_type_id) {
            $documents = $this->get_documents($state_type_id);
            if($documents->count() > 0){
                new PseServiceTask($documents,$state_type_id);
           

            }
            
        }


        }


        $this->info('The command is finished');
    }
}
