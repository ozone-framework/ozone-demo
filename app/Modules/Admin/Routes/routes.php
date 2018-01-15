<?php

/*
 |====================================================================================================
 | FRONTEND ROUTES
 |====================================================================================================
 | Frontend routes
 |
 */
$app->group('/admin', function () {
    $this->get('/dashboard', ['App\Modules\Admin\Http\Controllers\DashboardController', 'index'])->setName('dashboard');
});
