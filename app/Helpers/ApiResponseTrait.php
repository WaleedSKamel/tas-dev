<?php

namespace App\Helpers;


trait ApiResponseTrait
{
    public $paginateNumber = 10 ;

    public function apiResponse($data = null, $error = null, $status = true, $code = 200)
    {
        return response([
            'data' => $data,
            'status' => $status,
            'error' => $error,
        ], $code);
    }

    public function apiResponseData($data = null, $paginate = null, $error = null, $status = true, $code = 200)
    {
        return response([
            'data' => $data,
            'paginate' => $paginate,
            'status' => $status,
            'error' => $error,
        ], $code);
    }

    public function validation($validate, $status = false, $code = 422)
    {
        return $this->apiResponse('', $validate->errors()->first(), $status, $code);
    }

    public function notFound()
    {
        return $this->apiResponse('', 'Not Found', false, 404);

    }

    public function exception($e)
    {
        return $this->apiResponse('', $e->getMessage(), false, 520);
    }

    public function paginator($paginator)
    {
        return [
            'total' => $paginator->total(), // total item return
            'count' => $paginator->count(), // Get the number of items for the current page.
            'currentPage' => $paginator->currentPage(), // Get the current page number.
            'lastPage' => $paginator->lastPage(),  //Get the page number of the last available page. (Not available when using simplePaginate).
            //'firstItem' => $paginator->firstItem(),
            //'getOptions' => $paginator->getOptions(),
            //'hasPages' => $paginator->hasPages(),
            'hasMorePages' => $paginator->hasMorePages(), // Determine if there is more items in the data store.
            //'items' => $paginator->items(),
            //'lastItem' => $paginator->lastItem(),
            'nextPageUrl' => $paginator->nextPageUrl(), // Get the URL for the next page.
            'previousPageUrl' => $paginator->previousPageUrl(), // Get the URL for the previous page.
            //'onFirstPage' => $paginator->onFirstPage(),
            //'getPageName' => $paginator->getPageName(),
        ];
    }


}
