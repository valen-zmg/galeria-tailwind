<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $fotos = [
        ['img' => 'img1.webp', 'titulo' => 'Paisaje Rocoso', 'desc' => 'Un hermoso paisaje de montañas rocosas.'],
        ['img' => 'img2.jpg', 'titulo' => 'Montañas',       'desc' => 'Vista de montañas al amanecer.'],
        ['img' => 'img3.jpg', 'titulo' => 'Bosque',         'desc' => 'Un bosque lleno de vida y color.'],
        ['img' => 'img4.jpg', 'titulo' => 'Atardecer',      'desc' => 'Un atardecer sobre el mar.'],
        ['img' => 'img5.png', 'titulo' => 'Ciudad',         'desc' => 'Edificios modernos en la ciudad.'],
        ['img' => 'img6.jpg', 'titulo' => 'Lago',           'desc' => 'Un lago tranquilo entre montañas.'],
    ];

    return view('galeria', compact('fotos'));
});

Route::get('categorias', [CategoriaController::class, 'getCategorias'])
    ->name('getcategorias');

Route::post('create/categoria', [CategoriaController::class, 'createCategoria'])
    ->name('categoria.create');

Route::put("update/categoria/{id}", [CategoriaController::class,''])
    ->name("categoria.update");

Route::delete("delete/categoria/{id}", [CategoriaController::class, 'deleteCategoria'])
    ->name("categoria.delete");

    //hacer como el de nueva categoria
    //otro componente para actualizar categoria y eliminar categoria

Route::put('update/categoria/{id}', [CategoriaController::class, 'updateCategoria'])
    ->name('categoria.update');

Route::delete('delete/categoria/{id}', [CategoriaController::class, 'deleteCategoria'])
    ->name('categoria.delete');

Route::get('productos', [ProductoController::class, 'getProductos'])
    ->name('productos.get');

Route::post('productos/create', [ProductoController::class, 'createProducto'])
    ->name('productos.create');

Route::put('productos/update/{id}', [ProductoController::class, 'updateProducto'])
    ->name('productos.update');

Route::delete('productos/delete/{id}', [ProductoController::class, 'deleteProducto'])
    ->name('productos.delete');
    
