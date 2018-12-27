<?php 
namespace App\Documents;

class PublicReviewProduct extends Document
{
    public $type = 'product';
    
    public $bodyProperties = ['name','productCategory','subCategory', 'food_type', 'brand_name', 'company_name'];

    public function getValueOfproductCategory()
    {
    	return $this->model->product_category()->select('name')->get()->pluck('name')->toArray();
    }

    public function getValueOfsubCategory()
    {
    	return $this->model->product_sub_category()->select('name')->get()->pluck('name')->toArray();
    }

    public function getValueOffood_type()
    {
        if($this->model->is_vegetarian == 1)
        {
            return 'Vegetarian';
        }
        else
        {
            return 'Non-Vegeratrian';
        }
    }
}