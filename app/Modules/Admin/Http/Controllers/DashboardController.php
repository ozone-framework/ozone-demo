<?php

namespace App\Modules\Admin\Http\Controllers {

    use Core\Controller;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class DashboardController extends Controller
    {
        public function index(Request $request, Response $response)
        {
            return $this->view($response, '@Admin/Dashboard/index.twig');
        }
    }

}
