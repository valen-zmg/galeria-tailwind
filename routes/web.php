<?php

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