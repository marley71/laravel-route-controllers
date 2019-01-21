<?php namespace Marley71\RouteControllers;

use App;
use Gecche\Multidomain\Foundation\Console\RemoveDomainCommand;
use Illuminate\Support\ServiceProvider;
use Gecche\Multidomain\Foundation\Console\DomainCommand;
use Gecche\Multidomain\Foundation\Console\AddDomainCommand;
use Gecche\Multidomain\Foundation\Console\UpdateEnvDomainCommand;
use Marley71\Console\GenerateCommand;

class RouteControllersServiceProvider extends ServiceProvider {

    protected $defer = false;


    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Generate'
    ];


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        foreach ($this->commands as $command)
        {
            $this->{"register{$command}Command"}();
        }

        $this->commands(
            "command.route-controllers.generate"
        );

    }


    public function boot() {
//        $this->publishes([
//            __DIR__.'/../../config/domain.php' => config_path('domain.php'),
//        ]);
    }


    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerGenerateCommand()
    {
        $this->app->singleton('command.route-controllers.generate', function()
        {
            return new GenerateCommand();
        });
    }
}
