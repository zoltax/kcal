<?php

namespace app\controllers;
use app\models\Servings;
use app\models\Foods;
use lithium\security\Auth;
use lithium\storage\Session;
use app\extensions\helpers\Paginator;

class FoodController extends \lithium\action\Controller {

	public function index() {

		$total = Foods::count();

		$page = 1;

		foreach ($this->request->params['args'] as $arg) {
			list($name,$value) = explode(":", $arg);
			if ( $name == 'page') {
				$page = $value; 
			}
		} 
		
		$limit = 20;

    	$order = array('created' => 'DESC');

    	$meals = Foods::all(compact('order','limit','page'));

		return compact('meals', 'total', 'page', 'limit');

	}
	public function add() {
		
		
		if ($this->request->data) {
			$food = Foods::create($this->request->data);
			$success = $food->save();
			return $this->redirect('Food::index');	
		}
	}

	public function import() {
		$meals = Foods::count();
		if ( $meals > 640 ) {
			$this->redirect("Food::index");
		}
		$products = file_get_contents("data/products.txt");
		$products = explode("\n",$products);
		foreach ($products as $product) {
			list(
				$product_info['name'],
				$product_info['prot'],
				$product_info['fat'],
				$product_info['carb'],
				$product_info['kcal'],
				$product_info['sat_fat'],
				$product_info['unsat_fat'])
			 = explode("\t",$product);
			 $food = Foods::create($product_info);
			 $food->save();
		}
		
		$this->redirect("Food::index");
	}
	
	
	
}
	