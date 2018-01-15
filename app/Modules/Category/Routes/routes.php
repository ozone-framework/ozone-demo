<?php

/*
 |====================================================================================================
 | FRONTEND ROUTES
 |====================================================================================================
 | Frontend routes
 |
 */
$app->group('/admin', function () {

    $this->group('/category', function () {

        $this->get('/', ['App\Modules\Category\Http\Controllers\CategoryController', 'index'])->setName('category.index');

        $this->map(['GET', 'POST'], '/create', ['App\Modules\Category\Http\Controllers\CategoryController', 'create'])
            ->setName('category.create')
            ->add(App\Acme\Middleware\CsrfMiddleware::class);

        $this->get('/validate', ['App\Modules\Category\Http\Controllers\CategoryController', 'validate'])->setName('validate');

        $this->post('/validate', ['App\Modules\Category\Http\Controllers\CategoryController', 'validate'])->setName('validate.post');

    });

});
