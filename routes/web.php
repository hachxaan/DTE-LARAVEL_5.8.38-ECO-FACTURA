<?php


    Route::get('/', 'FacturarlosWSDL\FacturarlosWSDLController@index');
    Route::get('/anulacion', 'FacturarlosWSDL\FacturarlosWSDLController@anulacion');


    Route::get('/resultadoFirma', 'FacturarlosWSDL\RespuestaFirmaController@index');
    Route::get('/resultadoAnulacion', 'FacturarlosWSDL\RespuestaFirmaController@anulacion');

    
    Route::post('/', 'FacturarlosWSDL\FacturarlosWSDLController@firmaFactura')->name('firmaFactura');    
    Route::post('/anulacion', 'FacturarlosWSDL\FacturarlosWSDLController@anulacionFactura')->name('anulacionFactura');
