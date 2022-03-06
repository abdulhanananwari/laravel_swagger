<?php

namespace App\Helpers\Controllers;

use Illuminate\Routing\Controller;
use League\Fractal;

class ApiBaseController extends Controller {

     /**
         * @OA\Info(
         *      version="1.0.0",
         *      title="Simple CRUD Test Kodegiri - Swagger Documentation",
         *      description="Simple CRUD Test Kodegiri - Swagger Documentation ",
         *      @OA\Contact(
         *          email="abdulhanananwari@gmail.com"
         *      ),
         *      @OA\License(
         *          name="Apache 2.0",
         *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
         *      )
         * )
         *
         * @OA\Server(
         *      url=L5_SWAGGER_CONST_HOST,
         *      description="Demo API Server"
         * )
         * @OA\SecurityScheme(
         *    securityScheme="bearer",
         *    in="header",
         *    name="bearerAuth",
         *    type="http",
         *    scheme="bearer",
         *    bearerFormat="JWT",
         * ),
         * @OA\Tag(
         *     name="Projects",
         *     description="API Endpoints of Projects"
         * )
     */
    
    protected $manager;
    protected $transformer;
    protected $dataName;

    public function __construct() {

        $this->manager = new Fractal\Manager();
        $this->manager->setSerializer(new  \App\Helpers\Fractal\Serializer);
    }

    public function formatCollection($data, $meta = [], $paginator = null, $status = 200) {

        $this->parseInclude();
        $this->parseExclude();

        $resource = new \League\Fractal\Resource\Collection($data, $this->transformer, $this->dataName);
        $formattedData = $this->manager->createData($resource)->toArray();

        if (!is_null($paginator)) {
            $paginatorAdapter = new Fractal\Pagination\IlluminatePaginatorAdapter($paginator);
            $meta['pagination'] = [
                'count' => $paginatorAdapter->getCount(),
                'current_page' => $paginatorAdapter->getCurrentPage(),
                'per_page' => $paginatorAdapter->getPerPage(),
                'total' => $paginatorAdapter->getTotal(),
                'total_pages' => $paginatorAdapter->getLastPage(),
            ];
        }

        return $this->formatData($formattedData, $meta, $status);
    }

    public function formatItem($data, $meta = [], $status = 200) {

        $this->parseInclude();
        $this->parseExclude();

        if (is_null($data)) {
            return response('Not Found', 404);
        }

        $resource = new \League\Fractal\Resource\Item($data, $this->transformer, $this->dataName);
        $formattedData = $this->manager->createData($resource)->toArray();

        return $this->formatData($formattedData, $meta, $status);
    }

    public function formatErrors($errors, $status = 400) {

        return response()->json(['errors' => $errors], $status);
    }

    public function formatData($data, $meta = [], $status = 200) {

        return response()->json(['data' => $data, 'meta' => (object) $meta], $status);
    }

    protected function parseInclude() {

        if (request()->has('with')) {
            $this->manager->parseIncludes(explode(',', request()->get('with')));
        }

        if (request()->has('include')) {
            $this->manager->parseIncludes(explode(',', request()->get('include')));
        }
    }

    protected function parseExclude() {

        if (request()->has('without')) {
            $this->manager->parseExcludes(explode(',', request()->get('without')));
        }

        if (request()->has('exclude')) {
            $this->manager->parseExcludes(explode(',', request()->get('exclude')));
        }
    }


    protected function jsonApiData(object $data, string $type = null, $typeMap = [])
    {
        $_data = [
            'id' => $data->id ?? null,
            'type' => $type,
            'attributes' => []
        ];

        $relationships = [];

        foreach ($data as $key => $value) {
            if ($key === 'id') continue;

            if (strpos($key, '.') !== false) {
                $relationship = substr($key, 0, strpos($key, '.'));
                $key = substr($key, strpos($key, '.') + 1);
                $relationships[$relationship][$key] = $value;
            } else {
                // if (strpos('_', $key) !== false) $key = Str::camel($key);

                $_data['attributes'][$key] = $value;
            }
        }

        foreach ($relationships as $relationship => $value) {
            $_data['relationships'][$relationship] = [
                // 'data' => jsonApiData((object) $value, $typeMap[$relationship] ?? $relationship, $typeMap),
            ];
        }

        if ( empty($_data['relationships']) ) unset($_data['relationships']);

        return $_data;
    }


}
