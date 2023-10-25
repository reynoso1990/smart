<?php

namespace Modules\Sire\Http\Controllers;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Sire\Appendixes\Appendixes;

class SireController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function appendix()
    {
        return view('sire::appendix');
    }
    public function index()
    {
        return view('sire::index');
    }
    function get_appendix_column($appendix)
    {
        switch ($appendix) {
            case 2:
                return 'appendix_2';
                break;
            case 3:
                return 'appendix_3';
                break;
            case 4:
                return 'appendix_4';
                break;
            case 5:
                return 'appendix_5';
                break;
            default:
                return 'appendix_2';
                break;
        }
    }
    public function generate(Request $request)
    {
        $company = Company::active();
        $date = $request->month;
        $date_name = date('Ym', strtotime($date));
        $name = 'LE'.$company->number.$date_name.'00140400021112';
        //00140400021112
        $month = date('m', strtotime($date));
        $appendix = $request->appendix;
        $appendix_column = $this->get_appendix_column($appendix);
        $documents = Document::whereMonth('date_of_issue', $month)->where($appendix_column, true)->get();
        $txt = '';
        foreach ($documents as $key => $document) {
            $appendix_txt = new Appendixes($document, $key+1,$appendix);
            $txt .= $appendix_txt->generate_txt() . "\n";
        }

        return [
            'success' => true,
            'content' => $txt,
            'name' => $name.'.txt'
        ];
        
    }

 
}
