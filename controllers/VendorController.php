<?php

use App\core\Controller;
use App\repositories\CategoryRepository;
use App\repositories\ProductRepository;

class VendorController extends Controller
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
}