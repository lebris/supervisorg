<?php

namespace Supervisorg\Services\XmlRPC;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Supervisorg\Services\Processes\FilterCollection;
use Supervisorg\Domain\Server;
use Supervisorg\Domain\ServerCollection;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['collection.servers'] = $app->share(function($c) {
            $collection = new ServerCollection();

            foreach($c['configuration']->readRequired('supervisor/servers', []) as $serverConfiguration)
            {
                $hostname = $serverConfiguration['host'];
                $host = sprintf('http://%s:%d/RPC2', $hostname, $serverConfiguration['port']);

                $filters = $c['process.filter.global'];
                if(array_key_exists('filters', $serverConfiguration))
                {
                    $filters = $filters + $serverConfiguration['filters'];
                }

                $server = new Server(
                    $hostname,
                    $c['supervisor.client.factory']($host),
                    $this->buildServerFilters($filters, $c)
                );

                $collection->add($server);
            }

            return $collection;
        });

        $app['supervisor.client.factory'] = $app->protect(function($host) {
            return new Clients\CachedClient(
                new Clients\Zend($host)
            );
        });
    }

    public function boot(Application $app)
    {
    }

    private function buildServerFilters(array $serverFilters, Application $app)
    {
        $filters = new FilterCollection();

        foreach($serverFilters as $filterName)
        {
            $serviceName = 'process.filter.' . $filterName;
            if( ! $app->offsetExists($serviceName))
            {
                throw new \LogicException(sprintf('Filter %s not found.', $filterName));
            }

            $filters->addFilter($app[$serviceName]);
        }

        return $filters;
    }
}
