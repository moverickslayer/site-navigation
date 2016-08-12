<?php

namespace Orckid\TreeMenu;


use Illuminate\Support\Facades\Request;

class Item
{
	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $icon = '';

	/**
	 * @var string
	 */
	public $route = '';

	/**
	 * @var array
	 */
	public $items = [];

	/**
	 * @var bool
	 */
	public $active = false;

	/**
	 * @var array
	 */
	public $parents = [];

	/**
	 * @param $name
	 * @return static
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param $icon
	 * @return $this
	 */
	public function icon($icon)
	{
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @param $route
	 * @return $this
	 */
	public static function route($route = null)
	{
		$instance = new static;

		$instance->route = $route;

		if(!is_null($route)){
			$instance->active = Request::is($instance->getRoute());
		}

		return $instance;
	}

	/**
	 * @param $callback
	 * @return $this
	 */
	public function items($callback)
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

		if(!$this->active){
			$this->active = $menu->active;

			foreach($this->parents as $parent){
				$parent->active = $menu->active;
			}
		}

		$this->items[] = $menu;

		return $menu;
	}

	/**
	 * @return bool
	 */
	public function hasItem()
	{
		return count($this->items) > 0;
	}

	/**
	 * @return string
	 */
	public function isActive()
	{
		return $this->active ? 'selected' : '';
	}

	/**
	 * @return string
	 */
	public function getRoute()
	{
		if(is_null($this->route)){
			return 'javascript:;';
		}

		if($this->route != ''){
			return "dashboard/$this->route";
		}

		return 'dashboard';
	}

	/**
	 * @param $menu
	 */
	public function parent($menu)
	{
		$this->parents[] = $menu;
	}
}