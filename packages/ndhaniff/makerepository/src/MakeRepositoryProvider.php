<?php

namespace Ndhaniff\MakeRepository;

use Illuminate\Support\ServiceProvider;

class MakeRepositoryProvider extends ServiceProvider
{

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function  register()
	{
		$this->commands(MakeRepository::class);
	}
}
