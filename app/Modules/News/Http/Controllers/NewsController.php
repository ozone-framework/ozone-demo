<?php

namespace App\Modules\News\Http\Controllers {

    use Ozone\Validate;
    use Core\Controller;
    use App\Modules\News\Repositories\NewsRepository;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class NewsController extends Controller
    {
        protected $news;

        public function __construct(NewsRepository $news)
        {
            $this->news = $news;
        }

        public function index(Request $request, Response $response)
        {
//            $news = new Category();
//            $news->setTitle("Life is awesome");
//            $news->setImage('jdhf.jpg');
//            $news->setDetails('jshdgfsgfhgsjdfg sfgsgf usg fugsug ysgfysgf');
//            $news->setSlug("love-life");
//            $news->setStatus(1);
//            dd($this->news->save($news));
            $data['results'] = $this->news->findAll($page=1,$limit=4,['sort'=>'title','order'=>'asc']);
            //Update
            $news = $this->news->find(1);
            $news->setTitle('Love is blind');
            $this->news->update($news);
//            $this->news->delete($news);
            return $this->view($response, '@News/News/index.twig',$data);
        }

        public function validate(Request $request, Response $response)
        {
            $input =$request->getParsedBody();
            $files = $request->getUploadedFiles();

            $data = [];

            if ($request->getMethod() == 'POST') {

                Validate::str($input['name'], 'Name', 'required|min:3|max:4');
                Validate::email($input['email'], 'Email', 'required');

                if (Validate::isFine()) {
                    return $response->withRedirect($this->pathFor('home'));
                }
            }

            return $this->view($response, '@News/News/validate.twig',$data);

        }
    }

}
