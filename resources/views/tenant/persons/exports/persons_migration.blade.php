<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
        content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes</title>
</head>

<body>
    @if (!empty($records))
        <div class="">
            <div class=" ">
                <table class="">
                    <thead>
                        <tr>
                            <td>Código documento de identidad</td>
                            <td>Número de documento</td>
                            <td>Nombre/Razón Social</td>
                            <td>Nombre Comercial</td>
                            <td>Código del Páis</td>
                            <td>Código de Ubigeo</td>
                            <td>Dirección</td>
                            <td>Correo electrónico</td>
                            <td>Teléfono</td>
                            <td>Tipo Cliente</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $key => $value)
                            <?php
                            /** @var \App\Models\Tenant\Person $value*/
                            
                            $ubigeo = $value->district_id;
                            $department = $value->department->description ?? '';
                            $province = $value->province->description ?? '';
                            $district = $value->district->description ?? '';
                            $zone = $value->zone->name ?? '';
                            $seller = $value->seller ? $value->seller->getName() : '';
                            $observation = $value->observation ?: '';
                            ?>
                            <tr>
                                <td class="celda">{{ $value->identity_document_type_id }}</td>
                                <td class="celda">{{ $value->number }}</td>
                                <td class="celda">{{ $value->name }}</td>
                                <td class="celda">{{ $value->trade_name }}</td>
                                <td class="celda">{{ $value->country_id }}</td>
                                <td class="celda">{{ $ubigeo }}</td>
                                <td class="celda">{{ $value->address }}</td>
                                <td class="celda">{{ $value->email }}</td>
                                <td class="celda">{{ $value->telephone }}</td>
                                <td class="celda">{{ $value->person_type_id == 1 ? 'INTERNO' : 'DISTRIBUIDOR' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div>
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
