<?php

namespace Controllers;

use Exception;
use Services\ProductService;

class ProductController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll()
    {
        // this code seems to have been lost
        $products = $this->service->getAll();
        $this->respond($products);

    }

    public function delete($id){
        $this->service->delete($id);
    }

    public function getOne($id)
    {
        $product = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB

        if ($product == null) {
            $this->respondWithError(404, "Product not found");
            return;
        }

        $this->respond($product);
    }

    public function create()
    {
        try {
            $product = $this->createObjectFromPostedJson("Models\Product");
            // something is missing. Shouldn't we update the product in the DB?
            $this->service->insert($product);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function update($id)
    {

        // does the product exist?
        $product = $this->service->getOne($id);

        if ($product == null) {
            $this->respondWithError(404, "Product not found");
            return;
        }

        // update the product
        $product = $this->createObjectFromPostedJson("Models\Product");
        $this->service->update($product, $id);

        // display the updated product
        $this->service->getOne($id);
        $this->respond($product);

    } 
}
