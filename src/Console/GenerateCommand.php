<?php namespace Marley71\RouteControllers\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;

class GenerateCommand extends GeneratorCommand
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:generate 
                            {type : The type route generate web or api } 
                            {--tag : The optional values for the tagged route } 
                            {--path : path controller}';


    protected $description = "generate route from controllers and add in routes.web.php o routes.api.php";


    /*
     * Se il file di ambiente esiste già viene semplicemente sovrascirtto con i nuovi valori passati dal comando (update)
     */
    public function handle()
    {
        echo "deh";

    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->files->exists($this->getDomainEnvFilePath())) {
            return $this->getDomainEnvFilePath();
        }
        return base_path() . DIRECTORY_SEPARATOR . Config::get('domain.env_stub', '.env');
    }

    protected function createDomainEnvFile()
    {
        $envFilePath = $this->getStub();

        $domainValues = json_decode($this->option("domain_values"), true);


        if (!is_array($domainValues)) {
            $domainValues = array();
        }


        $envArray = $this->getVarsArray($envFilePath);

        $domainEnvFilePath = $this->getDomainEnvFilePath();

        $domainEnvArray = array_merge($envArray, $domainValues);
        $domainEnvFileContents = $this->makeDomainEnvFileContents($domainEnvArray);

        $this->files->put($domainEnvFilePath, $domainEnvFileContents);
    }

    public function createDomainStorageDirs()
    {
        $storageDirs = Config::get('domain.storage_dirs', array());
        $path = $this->getDomainStoragePath($this->domain);
        $rootPath = storage_path();
        if ($this->files->exists($path) && !$this->option('force')) {
            return;
        }

        if ($this->files->exists($path)) {
            $this->files->deleteDirectory($path);
        }


        $this->createRecursiveDomainStorageDirs($rootPath, $path, $storageDirs);


    }

    protected function createRecursiveDomainStorageDirs($rootPath, $path, $dirs)
    {
        $this->files->makeDirectory($path, 0755, true);
        foreach (['.gitignore', '.gitkeep'] as $gitFile) {
            $rootGitPath = $rootPath . DIRECTORY_SEPARATOR . $gitFile;
            if ($this->files->exists($rootGitPath)) {
                $gitPath = $path . DIRECTORY_SEPARATOR . $gitFile;
                $this->files->copy($rootGitPath, $gitPath);
            }
        }
        foreach ($dirs as $subdir => $subsubdirs) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $subdir;
            $fullRootPath = $rootPath . DIRECTORY_SEPARATOR . $subdir;
            $this->createRecursiveDomainStorageDirs($fullRootPath, $fullPath, $subsubdirs);
        }

    }

    protected function addDomainToConfigFile($config) {
        $domains = array_get($config, 'domains', []);
        if (!array_key_exists($this->domain, $domains)) {
            $domains[$this->domain] = $this->domain;
        }

        ksort($domains);
        $config['domains'] = $domains;

        return $config;
    }





}
