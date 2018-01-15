<?php

namespace App\Modules\Example\Http\Controllers {

    use Ozone\Validate;
    use Core\Controller;
    use App\Modules\Example\Entity\Example;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use App\Modules\Example\Repositories\ExampleRepository;

    class ExampleController extends Controller
    {
        protected $example;

        public function __construct(ExampleRepository $example)
        {
            $this->example = $example;
        }

        public function index(Request $request, Response $response)
        {
//            $example = new Example();
//            $example->setTitle("Life is awesome");
//            $example->setImage('jdhf.jpg');
//            $example->setDetails('jshdgfsgfhgsjdfg sfgsgf usg fugsug ysgfysgf');
//            $example->setSlug("love-life");
//            $example->setStatus(1);
//            dd($this->example->save($example));
            $data['results'] = $this->example->findAll($page=1,$limit=4,['sort'=>'title','order'=>'asc']);
            //Update
            $example = $this->example->find(1);
            $example->setTitle('Love is blind');
            $this->example->update($example);
//            $this->example->delete($example);
            return $this->view($response, '@Example/example/index.twig',$data);
        }

        public function validate(Request $request, Response $response)
        {
            $input = $request->getParsedBody();
            $files = $request->getUploadedFiles();

            $data = [];

            if ($request->getMethod() == 'POST') {

                Validate::str($input['name'], 'Name', 'required|min:3|max:4');
                Validate::email($input['email'], 'Email', 'required');

                if (Validate::isFine()) {
                    return $response->withRedirect($this->pathFor('home'));
                }
            }

            return $this->view($response, '@Example/example/validate.twig',$data);

        }
    }

}
