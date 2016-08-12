<?php

namespace Orckid\TreeMenu;


use Illuminate\Support\Facades\View;

class TreeMenu
{
	protected $navigation = [];

	public function __construct()
	{

	}

	/**
	 * @var array
	 */
	protected $menu = [];

	/**
	 * @var string
	 */
	protected $html = '';

	/**
	 * @var
	 */
	protected $activate_routes;

	/**
	 * @param $callback_name
	 * @return $this
	 */
	public function build($callback_name)
	{
		$path = 'Menu/' . $callback_name . '.php';

		$menu = $this;

		require app_path($path);

		return $this;
	}

	public function builder($callback)
	{
		$callback($this);

		return $this;
	}

	/**
	 * @param $name
	 * @return Item
	 */
	public function add($name)
	{
		$menu = Item::route($name);

		$menu->parent($this);

		$this->menu[] = $menu;

		return $menu;
	}

	/**
	 * @param array $array
	 * @return $this
	 */
	public function activate($array = [])
	{
		$this->activate_routes = $array;

		return $this;
	}

	public function evaluateCustomActive($menu)
	{
		if(in_array($menu->route, $this->activate_routes)){
			$menu->active = true;

			collect($menu->parents)->each(function($parent){
				$parent->active = true;
			});
		}
		else{
			collect($menu->items)->each(function($child){
				$this->evaluateCustomActive($child);
			});
		}
	}

	/**
	 * @return string
	 */
	public function render()
	{
		collect($this->menu)->each(function($menu){
			$this->evaluateCustomActive($menu);
		});

		foreach ($this->menu as $item){
			$this->html .= View::make(
				'dashboard._partials.menu-item',
				['item' => $item]
			)->render();
		}

		return $this->html;
	}

	/**
	 * @return array
	 */
	public function toJson()
	{
		return $this->menu;
	}
}