<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Support\Str;
use App\Events\OrderEvent;
use App\Events\ReceiveOrder;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Restaurant\Http\Requests\AreaRequest;
use Modules\Restaurant\Http\Requests\WorkersTypeRequest;
use Modules\Restaurant\Models\Area;
use Modules\Restaurant\Models\WorkersType;

use Illuminate\Support\Facades\Session;
class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('restaurant::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('restaurant::create');
    }
    public function workers_type()
    {
        return view('restaurant::configuration.workers_type');
    }
    public function workers_type_records()
    {
        $workers_type = WorkersType::all();

        return [
            'success' => true,
            'data' => $workers_type
        ];
    }
    public function workers_type_record($id)
    {
        $workers_type = WorkersType::find($id);

        return [
            'success' => true,
            'data' => $workers_type
        ];
    }
    public function workers_type_store(WorkersTypeRequest $request)
    {
        $id = $request->input('id');
        $worker_type = WorkersType::firstOrNew(['id' => $id]);
        $worker_type->fill($request->all());
        $worker_type->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Tipo actualizado con éxito' : 'Tipo creado con éxito'
        ];
    }
    public function areas()
    {
        return view('restaurant::configuration.areas');
    }
    public function areas_records()
    {
        $areas = Area::where('active',1)->get();

        return [
            'success' => true,
            'data' => $areas
        ];
    }
    public function areas_record($id)
    {
        $area = Area::find($id);

        return [
            'success' => true,
            'data' => $area
        ];
    }
    public function areas_store(AreaRequest $request)
    {
        $id = $request->input('id');
        $area = Area::firstOrNew(['id' => $id]);
        $area->fill($request->all());
        $area->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Área actualizada con éxito' : 'Área creada con éxito'
        ];
    }
    public function sendOrder(Request $request)
    {
        $data = $request->all();
        OrderEvent($data);
        return [
            'success' => true,
            'message' => 'Orden enviada'
        ];
    }
    public function receiveOrder(Request $request)
    {
        $data = $request->all();
        event(new ReceiveOrder($data));
        return [
            'success' => true,
            'message' => 'Orden enviada'
        ];
    }
    
    public function loginWorker()
    {
        $configuration = Configuration::first();
        $event_name = $configuration->socket_channel;
        if (!$event_name) {
            $configuration->socket_channel = Str::random(10);
            $configuration->save();
            $event_name = $configuration->socket_channel;
        }

        return view('restaurant::worker.login');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('restaurant::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('restaurant::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function logout(Request $request)
{
    Session::flush();
    Auth::logout();
    return redirect('login');
}
}
