<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Establishment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EstablishmentRequest;
use App\Http\Resources\Tenant\EstablishmentResource;
use App\Http\Resources\Tenant\EstablishmentCollection;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Person;
use Modules\Finance\Helpers\UploadFileHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstablishmentController extends Controller
{
    public function index()
    {
        return view('tenant.establishments.index');
    }

    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function removeImage($type, Request $request){
        $id = $request->input('id');
        $establishment = Establishment::findOrFail($id);
        //concatenar el tipo de imagen con la palabra logo como variable de establecimiento
        $type = $type.'_logo';
        if($establishment->$type){
            $path = $establishment->$type;
            $public_path = public_path($path);
            if(file_exists($public_path) && unlink($public_path)){
                $establishment->$type = null;
                $establishment->save();
                return [
                    'success' => true,
                    'message' => 'Imagen eliminada con éxito'
                ];
            }else{
                return [
                    'success' => false,
                    'message' => 'No se pudo eliminar la imagen'
                ];
            }

        }else{
            return [
                'success' => false,
                'message' => 'No se encontró la imagen'
            ];
        }
    }
    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();

        $customers = Person::whereType('customers')->orderBy('name')->take(1)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return compact('countries', 'departments', 'provinces', 'districts', 'customers');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }
    
    
    /**
     *
     * @param  EstablishmentRequest $request
     * @return array
     */
    public function store(EstablishmentRequest $request)
    {
        try 
        {
            $id = $request->input('id');
            $has_igv_31556 = ($request->input('has_igv_31556') === 'true');
            $establishment = Establishment::firstOrNew(['id' => $id]);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $request->validate(['file' => 'mimes:jpeg,png,jpg|max:1024']);
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;

                UploadFileHelper::checkIfValidFile($filename, $file->getPathName(), true);

                $file->storeAs('public/uploads/logos', $filename);
                $path = 'storage/uploads/logos/' . $filename;
                $request->merge(['logo' => $path]);
            }   
            if ($request->hasFile('file_yape') && $request->file('file_yape')->isValid()) {
             
                $request->validate(['file_yape' => 'mimes:jpeg,png,jpg|max:1024']);
                $file = $request->file('file_yape');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                
                UploadFileHelper::checkIfValidFile($filename, $file->getPathName(), true);
                
                $file->storeAs('public/uploads/logos', $filename);
                $path = 'storage/uploads/logos/' . $filename;
                $request->merge(['yape_logo' => $path]);
            }
            if ($request->hasFile('file_plin') && $request->file('file_plin')->isValid()) {
                $request->validate(['file_plin' => 'mimes:jpeg,png,jpg|max:1024']);
                $file = $request->file('file_plin');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;

                UploadFileHelper::checkIfValidFile($filename, $file->getPathName(), true);

                $file->storeAs('public/uploads/logos', $filename);
                $path = 'storage/uploads/logos/' . $filename;
                $request->merge(['plin_logo' => $path]);
            }
            $establishment->fill($request->all());
            $establishment->printer = $request->printer;
            $establishment->has_igv_31556 = $has_igv_31556;
            $establishment->save();

            if(!$id) {
                $warehouse = new Warehouse();
                $warehouse->establishment_id = $establishment->id;
                $warehouse->description = 'Almacén - '.$establishment->description;
                $warehouse->save();
            }

            return [
                'success' => true,
                'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
            ];
        } 
        catch(Exception $e)
        {
            $this->generalWriteErrorLog($e);

            return $this->generalResponse(false, 'Error desconocido: '.$e->getMessage());
        }
    }


    public function records()
    {
        $records = Establishment::all();

        return new EstablishmentCollection($records);
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }
}
