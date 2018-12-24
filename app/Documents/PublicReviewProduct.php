<?php 
namespace App\Documents;

class PublicReviewProduct extends Document
{
    public $type = 'product';
    
    public $bodyProperties = ['name','productCategory','subCategory', 'is_vegetarian', 'brand_name', 'company_name'];

    public function getValueOfProductCategory()
    {
    	return $this->model->product_category()->select('name')->get()->pluck('name')->toArray();
    }

    public function getValueOfSubCategory()
    {
    	return $this->model->product_sub_category()->select('name')->get()->pluck('name')->toArray();
    }
}