<!DOCTYPE html>
<html lang="en">
<?php
use App\Models\Tenant\Item;
use App\Models\Tenant\ItemUnitType;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comisiones vendedores</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black;
        }

        .celda {
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
        }

        th {
            padding: 5px;
            text-align: center;
            border-color: #0088cc;
            border: 0.1px solid black;
        }

        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 20px !important;
            text-decoration: underline;
        }

        p>strong {
            margin-left: 5px;
            font-size: 13px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>
    <div>
        <p align="center" class="title"><strong>Reporte de comisión de vendedores - utilidades</strong></p>
    </div>
    <div style="margin-top:20px; margin-bottom:20px;">
        <table>
            <tr>
                <td>
                    <p><strong>Empresa: </strong>{{ $company->name }}</p>
                </td>
                <td>
                    <p><strong>Fecha: </strong>{{ date('Y-m-d') }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>Ruc: </strong>{{ $company->number }}</p>
                </td>

            </tr>
        </table>
    </div>
    @if (!empty($records))
        @php
            $acum_unit_gain = 0;
            $acum_overall_profit = 0;
            
        @endphp
        <div class="">
            <div class=" ">
                <table class="">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th class="text-center">Comprobante</th>
                            <th class="text-center">Serie</th>
                            <th class="text-center">Ruc/Dni</th>

                            <th class="text-center">Comercial</th>
                            <th class="text-center">Detalle</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Precio compra</th>
                            <th class="text-center">Precio venta</th>

                            <th class="text-center">Ganancia unidad</th>
                            <th class="text-center">Ganancia total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $row)
                            @php
                                $quantity = $row->quantity;
                                $unit_price = $row->unit_price;
                                $type_document = '';
                                $presentation_name = null;
                                $relation = $row->document_id ? $row->document : $row->sale_note;
                                $items = Item::find($row->item_id); 
                                $purchase_unit_price  = 0;
                                if ($row->document_id) {
                                    $type_document = $row->document->document_type_id == '01' ? 'FACTURA' : 'BOLETA';
                                    if (isset($row->item->presentation->id)) {
                                       // dd($row->item->unit_type_id);
                                        $presentation = $row->item->presentation;
                                        $presentation_name = $presentation->description;
                                        $data_items = ItemUnitType::where('item_id',$row->item_id)->where('unit_type_id',$row->item->unit_type_id)->first();
                                        $purchase_unit_price = $items->purchase_unit_price*$data_items->quantity_unit;
                                
                                        $quantity = $presentation->quantity_unit;
                                        $unit_price = number_format($unit_price, 2, '.', '');
                                        // $unit_price =
                                    }else{
                                         $purchase_unit_price = $items->purchase_unit_price;
                                    }
                                } elseif ($row->sale_note_id) {
                                    $type_document = 'NOTA DE VENTA';
                                }
                                
                                // $purchase_unit_price = 0;
                                // if (isset($row->item->purchase_unit_price)) {
                                //     $purchase_unit_price = $row->item->purchase_unit_price;
                                // }
                                $unit_gain = (float) $unit_price - (float) $purchase_unit_price;
                                $overall_profit = (float) $unit_price * $quantity - (float) $purchase_unit_price * $quantity;
                                
                                $acum_unit_gain += (float) $unit_gain;
                                $acum_overall_profit += (float) $overall_profit;
                                if($purchase_unit_price  == 0){
                                    $item = \App\Models\Tenant\Item::find($row->item_id);
                                    $purchase_unit_price = $item->purchase_unit_price;
                                }
                            @endphp

                            <tr>
                                <td class="celda">{{ $loop->iteration }}</td>
                                <td class="celda">{{ $relation->date_of_issue->format('Y-m-d') }}</td>
                                <td class="celda">{{ $type_document }}</td>
                                <td class="celda">{{ $relation->number_full }}</td>
                                <td class="celda">{{ $relation->customer->number }}</td>

                                <td class="celda">{{ $relation->customer->name }}

                                </td>
                                <td class="celda">{{ $row->relation_item->description }}
                                    @if ($presentation_name)
                                        <br>
                                        <small>
                                            PRES:{{ $presentation_name }}
                                        </small>
                                    @endif
                                </td>

                                <td class="celda">{{ $quantity }}</td>
                                <td class="celda">{{ $purchase_unit_price }}</td>
                                <td class="celda">{{ $unit_price }}</td>

                                <td class="celda">{{ $unit_gain }}</td>
                                <td class="celda">{{ $overall_profit }}</td>

                            </tr>
                        @endforeach

                        <tr>
                            <td class="celda" style="text-align:right;" colspan="10">TOTAL:</td>
                            <td class="celda">{{ number_format($acum_unit_gain, 2, '.', '') }}</td>
                            <td class="celda">{{ number_format($acum_overall_profit, 2, '.', '') }}</td>
                        </tr>

                    </tbody>
                    {{-- <tfoot>
                            <tr>
                                <td style="text-align:right;" colspan="10">TOTAL:</td>
                                <td class="text-center">{{ $acum_unit_gain }}</td>
                                <td class="text-center">{{ $acum_overall_profit }}</td>
                            </tr>
                        </tfoot> --}}
                </table>
            </div>
        </div>
    @else
        <div class="callout callout-info">
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
