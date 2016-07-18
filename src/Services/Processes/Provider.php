<?php

namespace Supervisorg\Services\Processes;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['process.filter.closure.plain'] = $app->protect(function($name, array $configuration) {
            if( ! array_key_exists('values', $configuration))
            {
                throw new \LogicException("No filter values provided for filter $name.");
            }

            return new Filters\PlainName($configuration['values']);
        });

        $app['process.filter.closure.regex'] = $app->protect(function($name, $configuration) {
            if( ! array_key_exists('pattern', $configuration))
            {
                throw new \LogicException("No pattern provided for filter $name.");
            }

            return new Filters\RegexName($configuration['pattern']);
        });

        $app['process.filter.global'] = $app['configuration']->read('filters/global_filters', []);

        $this->buildFilters($app);
    }

    public function boot(Application $app)
    {
    }

    private function buildFilters(Application $app)
    {
        foreach($app['configuration']->read('filters/filters', []) as $name => $definition)
        {
            if( ! array_key_exists('type', $definition))
            {
                continue;
            }

            $filterType = $definition['type'];
            $type = "process.filter.closure.$filterType";
            if( ! $app->offsetExists($type))
            {
                throw new \LogicException(sprintf('Filter type "%s" not supported.', $filterName));
            }

            $app["process.filter.$name"] = call_user_func_array($app[$type], [$name, $definition]);
        }
    }
}
