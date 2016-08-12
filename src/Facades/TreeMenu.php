<?php

namespace Orckid\TreeMenu\Facades;


use Illuminate\Support\Facades\Facade;

class TreeMenu extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'tree-menu';
	}
}