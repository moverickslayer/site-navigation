<?php

namespace Orckid\TreeMenu;

use Illuminate\Support\ServiceProvider;


class TreeMenuServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('tree-menu', function($app){
			return new TreeMenu();
		});
	}

	public function boot()
	{
		$this->publishes([
			__DIR__.'/menu' => app_path('menu'),
		]);
	}
}