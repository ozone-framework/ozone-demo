<?php

namespace App\Modules\Category\Http\Controllers {

    use App\Modules\Category\Entity\Category;
    use App\Modules\Example\Repositories\CategoryRepository;
    use Core\Controller;
    use Ozone\Validation;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Stringy\StaticStringy as Stringy;

    class CategoryController extends Controller
    {
        /**
         * @var CategoryRepository
         */
        protected $category;

        public function __construct(CategoryRepository $category)
        {
            $this->category = $category;
        }

        public function index(Request $request, Response $response)
        {

            $filter['sort'] = 'title';
            $filter['order'] = 'asc';

            $data['results'] = $this->category->findAll($limit = 4, $page = 1, $filter);

            return $this->view($response, '@Category/Category/index.twig', $data);
        }

        /**
         * @param Request $request
         * @param Response $response
         * @return mixed
         */
        public function create(Request $request, Response $response)
        {
            $input = (object)$request->getParsedBody();// Get All Request Parameters
            $files = (object)$request->getUploadedFiles();// Get Files Parameters

            if ($request->getMethod() == 'POST') {

                $rules = [
                    'title' => 'required|url',
                    'parent_id' => 'numeric',
                    'sort_order' => 'required|numeric|min:1',
                    'featured' => 'required|boolean',
                    'details' => 'required|min:10',
                    'image' => 'required|file|mimes:jpg,jpeg,png|size:2'// Do not forgot to add rule [file] if its file upload.
                ];

                if (Validation::isValid($request, $rules)) {

                    $category = new Category();

                    $category->setTitle($input->title);
                    $category->setSortOrder($input->sort_order);
                    $category->setIsFeatured(isset($input->featured) ? $input->featured : '');
                    $category->setSlug(Stringy::slugify($input->title));
                    $category->setDetails($input->details);
                    $category->setStatus(1);

                    if (isset($input->parent_id)) {
                        $category->setParent($this->category->find($input->parent_id));
                    }

                    if ($files->image->getError() === UPLOAD_ERR_OK) {
                        $imageName = $this->category->upload($files->image);
                        $category->setImage($imageName);
                    }

                    try {

                        $this->category->save($category);
                        flash('success', "Data saved successfully");

                        return $response->withRedirect($this->pathFor('category.create'));

                    } catch (\Throwable $t) {
                        flash('error', "Something went wrong!");
                    }

                }


            }
            $data['categories'] = $this->category->findAll(9999, 1);
            return $this->view($response, '@Category/Category/create.twig', $data);
        }
    }

}
