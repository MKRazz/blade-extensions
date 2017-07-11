<?php
/**
 * A laravel service provider to register the class into the the IoC container
 */
namespace Radic\BladeExtensions;

use App;
use Config;
use Exception;
use Laradic\Support\ServiceProvider;
use Radic\BladeExtensions\Directives\AssignmentDirectives;
use Radic\BladeExtensions\Directives\DebugDirectives;
use Radic\BladeExtensions\Directives\ForeachDirectives;
use Radic\BladeExtensions\Directives\MacroDirectives;
use Radic\BladeExtensions\Directives\PartialDirectives;
use Radic\BladeExtensions\Providers\MarkdownServiceProvider;

/**
 * A laravel service provider to register the class into the the IoC container
 *
 * @package        Radic\BladeExtensions
 * @version        2.1.0
 * @author         Robin Radic
 * @license        MIT License - http://radic.mit-license.org
 * @copyright      2011-2015, Robin Radic
 * @link           http://robin.radic.nl/blade-extensions
 *
 */
class BladeExtensionsServiceProvider extends ServiceProvider
{

    /** {@inheritDoc} */
    protected $configFiles = ['blade_extensions'];

    /**
     * {@inheritDoc}
     */
    protected $dir = __DIR__;

    /** {@inheritDoc} */
    public function boot()
    {
        parent::boot();
    }

    /** {@inheritDoc} */
    public function register()
    {
        parent::register();

        AssignmentDirectives::attach($this->app);
        DebugDirectives::attach($this->app);
        ForeachDirectives::attach($this->app);
        PartialDirectives::attach($this->app);

        # Optional macro directives
        if ( array_key_exists('form', App::getBindings()) )
        {
            MacroDirectives::attach($this->app);
        }

        # Optional markdown compiler, engines and directives
        if ( Config::get('blade_extensions.markdown.enabled') )
        {
            if( ! class_exists(Config::get('blade_extensions.markdown.renderer')) ){
                throw new Exception("The configured markdown renderer class does not exist");
            }
            $this->app->register(new MarkdownServiceProvider($this->app));
        }
    }
}
