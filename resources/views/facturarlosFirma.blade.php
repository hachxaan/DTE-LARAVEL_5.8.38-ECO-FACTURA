
<html>
  <head>
    <title>Test DTE para Carlos Arce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .areaXML {
          height : 500px;
        }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Pruebas facturacion Carlos Arce</h2>
        <div class="panel panel-success">
          <div class="panel-heading"><h3>Firma</h3></div>
            <div class="panel-body pr-5">
              <form class="row" action="{{  route('firmaFactura') }}"  method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <input  class="btn btn-primary" type="submit" value="Envar">
                </div>
                <div class="form-group">
                  <label>Cliente</label>
                  <input class="form-control" name="FCliente" type="text" value="80000000114K">
                </div>

                <div class="form-group">
                  <label>Usuario</label>
                  <input class="form-control" name="FUsuario" type="text" value="ADMIN">
                </div>


                <div class="form-group">
                  <label>Clave</label>
                  <input class="form-control" name="FClave" type="text" value="123">
                </div>

                <div class="form-group">
                  <label>NIT Emisor</label>
                  <input class="form-control" name="FNitemisor" type="text" value="80000000114K">
                </div>


                <div class="form-group">
                  <label>Ruta de donde se crearán las facturas (XML, PDF)</label>
                  <input class="form-control" name="FOutputEDT" type="text" value="./OutputEDTFirmados/">
                </div>                                     
                          
                <div class="form-group">
                  <label>Ruta del Archivo XML con los datos para facturar</label>
                  <input class="form-control" name="FPathFacturaIn" type="text" value="./InputFiles/fact.xml">
                </div>     
 
                
              </form>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>