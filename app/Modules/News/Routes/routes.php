<?php

/*
 |====================================================================================================
 | FRONTEND ROUTES
 |====================================================================================================
 | Frontend routes
 |
 */
$app->group('/news', function () {
    $this->get('/', ['App\\Modules\\News\\Http\\Controllers\\NewsController', 'index'])->setName('news');
    $this->get('/validate', ['App\\Modules\\News\\Http\\Controllers\\NewsController', 'validate'])->setName('validate');
    $this->post('/validate', ['App\\Modules\\News\\Http\\Controllers\\NewsController', 'validate'])->setName('validate.post');
});
