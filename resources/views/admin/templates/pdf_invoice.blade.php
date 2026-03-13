<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        h1 { color: #4CAF50; }
        tr { height: 35px }
        .row { display: flex; }
        .col { width: 20%; }
        .text-end { text-align: right; }
    </style>
    <meta charset="utf-8">
</head>
<body>
    <div style="display:flex; height:130px;">
        <div style="float:left; width:50%">
            <img src="{{ $image }}" width="220px">
        </div>    
        <div style="float:left; text-align:center;">
            <pre style="font-size: 14px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
                INGENIERIA MECANICA RUBIO
                C. 2D 268E, entre 65-G y 65-F
                Col. Francisco Villa Oriente. 
                C.P.: 97370 Mérida, Yuc.
            </pre>
        </div>
    </div>
    <br>
    <hr>
    <br>
    <div style="background-color: #efefef; padding: 10px; border:solid 1px #888;">
        @if ($title)
            <h4 style="margin-top: 0px;">{{ $title }}</h4>
        @else
            <h4 style="margin-top: 0px;">ORDEN DE SERVICIO</h4>
        @endif
        <table width="100%">
            <tr>
                <td width="75%"><b style="font-size:12px">CLIENTE:</b> {{ $service->client->name }}</td>
                <td><b style="font-size:12px">FECHA: </b>{{ Carbon\Carbon::now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td width="75%"><b style="font-size:12px">AUTOMOVIL:</b> {{ $service->car->carName() }} {{ $service->car->year }}</td>
                <td><b style="font-size:12px">SERVICIO: </b>{{ "#".$service->id }}</td>
            </tr>
        </table>
    </div>

    <div style="background-color: #efefef; padding: 10px; border:solid 1px #888; margin-top: 10px; padding-bottom: 0px; padding-top: 0px;">
        <h4 class="padding-top: 0px">SERVICIO / FALLO REPORTADO</h4> 
        <pre style="font-size: 14px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">{{ $service->fault }}</pre>
    </div>
    
    <p> LISTA DE MATERIALES Y MANO DE OBRA REQUERIDOS PARA EL SERVICIO:</p>

    <hr>
    <table width="100%">
        <thead>
            <td><b>Cant</b></td>
            <td><b>Descripción</b></td>
            <td class="text-end"><b>P.Unitario</b></td>
            <td class="text-end"><b>Importe</b></td>
        </thead>
        <tbody>
            @foreach ($service->serviceItems as $item)
            <tr style="height: 35px;">
                <td>{{ $item->amount }}</td>
                <td>{{ $item->item }}</td>
                <td class="text-end">{{ Number::currency($item->price) }}</td>
                <td class="text-end">{{ Number::currency($item->amount * $item->price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <table style="margin-top: 20px;" width="100%">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="text-end">{{ Number::currency($service->serviceItemsTotal()) }}</td>
        </tr>
    </table>

    <p style="margin-top: 50px;">
        Este presupuesto es una estimación basada en la revision inicial. El costo final puede variar debido a fluctuaciones en el precio de los componentes
        o el descubrimiento de daños ocultos tras el desarmado. Cualquier excedente será notificado y consultado previamente.
    </p>

    <p style="margin-top: 50px;">
        Para cualquier duda o aclaración quedamos a su entera disposicion, 
        puede comunicarse con nosotros o enviarnos whatsapp al numero: <b>(9994) 48 44 63</b>
    </p>
</body>
</html>