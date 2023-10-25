<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
            html {
                font-family: sans-serif;
                font-size: 11px;
            }

            table {
                width: 100%;
                border-spacing: 0;

            }
            .table{
                border: 0.1px solid #ccc;
            }
            .celda {
                text-align: left;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            .celda_left {
                text-align: left;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            .celda_center {
                text-align: center;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            .celda_right {
                text-align: right;
                padding: 5px;
                border: 0.1px solid #ccc;
             }
            tr:nth-child(even) {

            }
            .nth-child {
                background-color: transparent;
            }
            .border-bottom{
                 border-bottom: 0.1px solid #ccc;
            }
            th {
                padding: 5px;
                text-align: center;
                border-color: #409EFF;
                border: 0.1px solid #ccc;
            }
            .headers{
                padding:5px !important;
                border-bottom:0.1px solid #ccc;
                height:25px;
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
                 color:#000;
                text-align: center;
            }
            .title {
                font-weight: bold;
                padding: 3px;
                font-size: 20px !important;
                text-decoration: underline;
            }
            .encabezado{
                background-color:#eee;
                text-transform: uppercase;
                padding: 5px;
                padding-left:5px;
            }
            .categoria{
                background-color:#eee;
                text-transform: uppercase;
                padding: 5px;
                padding-left: 50px;
            }
            .celda_loop{
                width:10% !important;
                text-align: center;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
                    .celda_descrip{
                width:60% !important;
                text-align: left;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            .celda_date{
                width:30% !important;
                text-align: center;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            .celda_left{
                width:30% !important;
                text-align: left;
                padding: 5px;
                border: 0.1px solid #ccc;
            }
            p>strong {
                margin-left: 5px;
                font-size: 11px;
            }
            header {
            position: fixed;
            height: 1cm;
            color: #000;
            text-align: center;
            padding:10px;
            font-size:12px;
            font-family:arial;

        }
        footer {
            position: fixed;
            bottom: 10px;
            height: 0.8cm;
            color:#000;
            text-align: center;
            font-size:11px;
            padding:12px;
            font-family:Arial;
            padding:10px;
        }
        .sinbordes{
            border: 0px !important;
            height: 15px !important;
        }
        h5{
            padding: 0px !important;
            margin:  0px !important;
        }
        @page {
            margin:15px;
        }

        td,th{
                font-size:10px !important;
                height:15px;
        }
        .company_logo_ticket {
            max-width: 150px;
            max-height: 70px;
          }
        </style>

<body>
<div id="register">

    <table border="0" style="border:0px solid;width: 260px !important">
        <thead>
            <tr>
                <td colspan="3" class="celda_left sinbordes" valign="top">
                    <table border="0" width="100%">
                        <tr>
                            <td  align="center">
                                @if($company->logo!=null)
                                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo_ticket contain">
                                @else
                                <img src="data:{{mime_content_type("logo/logo.jpg")}};base64, {{base64_encode(file_get_contents("logo/logo.jpg"))}}"  class="company_logo_ticket contain">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td  align="center">

                                @isset($establishment->aditional_information)
                                    <h5><b>{{ ($establishment->aditional_information !== '-')? $establishment->aditional_information : '' }}</b></h5>
                                @endisset
                                <h5>
                                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                                </h5>
                                @isset($establishment->trade_address)
                                    <h5>{{ ($establishment->trade_address !== '-')? 'D. Comercial: '.$establishment->trade_address : '' }}</h5>
                                @endisset
                                <h5>Telefonos:{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h5>
                                <h5>{{ ($establishment->email !== '-')? 'Email: '.$establishment->email : '' }}</h5>
                                @isset($establishment->web_address)
                                    <h5>{{ ($establishment->web_address !== '-')? 'Web: '.$establishment->web_address : '' }}</h5>
                                @endisset
                            </td>
                        </tr>
                    </table>
                </td>
           </tr>
            <tr>
                <td colspan="3" class="celda_left sinbordes" valign="top">
                    <h2  style="text-align: center;padding:0px;margin-top:5px;margin-bottom:5px;font-size:16px !important;">TICKETS DE PEDIDO <br>
                        N° ORDEN {{ $orden }}
                    </h2>
                </td>
           </tr>
            <tr>
                 <td  colspan="3" class="celda_left sinbordes" valign="top" style="font-size: 12px !important; text-align: center;">
                    <strong>Nº MESA {{ str_pad($ordenes->mesa->number, 2, "0", STR_PAD_LEFT)}} </strong> FECHA {{ $date }}</td>
            </tr>
            @if ($ordenes->to_carry==1)
            <tr>
                <td  colspan="3" class="celda_left sinbordes" valign="top" style="font-size: 12px !important; text-align: center;">
                   <strong>PEDIDO PARA LLEVAR </td>
           </tr>
            @endif
             <tr>
                <td class="encabezado">Cant.</td>
                 <td class="encabezado celda_left">Descripción</td>
                 <td class="encabezado">Importe</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $total=0;
            ?>
            @foreach($orden_items as $row)
            <?php
            $total=$total+$row->price*$row->quantity;
            ?>
             <tr>
                 <td class="celda_center">{{$row->quantity}}</td>
                 <td  class="celda_left">{{ strtoupper($row->food->description)}}</td>
                 <td  class="celda_center">{{$row->price}}</td>
              </tr>
            @endforeach
            <tr>
                <th class="encabezado" colspan="2" style="text-align: right">Total S/ </th>
                <th  class="encabezado">{{ number_format($total,2) }}</th>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>


