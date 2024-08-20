<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private $ignoredInterfacesFiles = ['..', '.', 'IBaseRepository.php'];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupRepositoriesClasses();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function setupRepositoriesClasses()
    {
        $repositoriesInterfacesFile = scandir(app_path("Http/Repositories/Interfaces"));

        foreach ($repositoriesInterfacesFile as $file) {

            if (!in_array($file, $this->ignoredInterfacesFiles)) {

                $file = pathinfo($file, PATHINFO_FILENAME);

                $this->app->bind("App\Http\Repositories\Interfaces\\" . $file, "App\Http\Repositories\Eloquent\\" . substr($file, 1, strlen($file)));
            }
        }
    }
}
