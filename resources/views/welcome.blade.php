@extends('layouts.vistageneral')

@section('contenidoPrincipal')
        <div class="container ">
            {!! Form::open(array('url'=>'/','method'=>'POST', 'id'=>'formulario')) !!}
            <meta name="_token" content="{!! csrf_token() !!}"/>
            <label for="dateDesde">Fecha desde:</label>
            <input type="date" id="dateDesde" name="dateDesde" required />
            <label for="dateHasta">Fecha hasta:</label>
            <input type="date" id="dateHasta"  name="dateHasta" required />
            <button href="#" type="submit" class="btn btn-success" style="margin-left:1rem" id="BtnEnviar">Filtrar</button>
            {!! Form::close() !!}
            <a href="/" class="btn btn-primary" style="margin-left:1rem" id="BtnLimpiar">Limpiar Filtro</a>

        </div>
        <div id="container" class="container"></div>

        @endsection

        @section('scripts')

<script src="https://code.highcharts.com/stock/highstock.js"></script>

<script type="text/javascript">



  $("#BtnEnviar").click(function () {
    var desde = $('#dateDesde').val();
      var hasta = $('#dateHasta').val();
      $.ajax({
        type: "POST",
        url: "/",
        dattaType:'json',
        data: {
            desde: desde,
            hasta: hasta
        }
      });
  });

Highcharts.chart('container', {

rangeSelector: {
            allButtonsEnabled: true,
            selected: 2
        },

accessibility: {
    point: {
        valueDescriptionFormat: '{index}. {xDescription}, {point.y}.'
    }
},

legend: {
    enabled: false
},

title: {
    text: 'Indicador valor UF'
},
subtitle:{
    text:<?= $fechas?>
},
tooltip: {
    shared: true
},

xAxis: {
    type: 'category',
    title:{
        text:'Fecha'
    }
},

yAxis: {
    title: {
        text: 'Valor'
    }
},

series: [{
    name: 'Valor',
    data: <?= $data ?>
}],

});

         </script>
@endsection
