<?php 

namespace App\Helper;

use App\Customer;
use Validator;

trait Dataviewer {

	protected static $operators = [
        'equal' => '=',
        'not_equal' => '<>',
        'less_than' => '<',
        'greater_than' => '>',
        'less_than_or_equal_to' => '<=',
        'greater_than_or_equal_to' => '>=',
        'in' => 'IN',
        'like' => 'LIKE'


    ];

	public static function searchPaginateAndOrder()
	{
		 

		$request = app()->make('request');

		$v = Validator::make($request->only([
			'column', 'direction', 'per_page',
			'search_column', 'search_operator', 'search_input'
		]), [
			'column' => 'required|in:'.implode(',', self::$columns),
			'direction' => 'required|in:asc,desc',
			'per_page' => 'integer|min:1',
			'search_column' => 'in:'.implode(',', self::$columns),
			
			'search_input' => 'max:255'
 
		]);

		if($v->fails()) {
			dd($v->messages());
		}
		return Customer::
				orderBy($request->column, $request->direction)
				->where(function($query) use ($request) {
					if(isset($request->search_input)) {
						if($request->search_operator == 'in') {
							$query->whereIn($request->search_column, explode(',', $request->search_input));
						} elseif ($request->search_operator == 'like') {
							$query->where($request->search_column, 'LIKE', '%'.$request->search_input.'%');
						} else {
							$query->where($request->search_column, static::$operators[$request->search_operator], $request->search_input);
						}
						
					}
				})
				->paginate($request->per_page);
	}
}