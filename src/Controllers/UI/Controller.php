<?php

namespace Supervisorg\Controllers\UI;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Puzzle\Configuration;
use Supervisorg\Domain\ServerCollection;
use Spear\Silex\Provider\Traits\TwigAware;

class Controller
{
    use
        TwigAware,
        Traits\RequestAware,
        LoggerAwareTrait;

    private
        $servers,
        $configuration;

    public function __construct(ServerCollection $servers, Configuration $configuration)
    {
        $this->servers = $servers;
        $this->configuration = $configuration;
        $this->logger = new NullLogger();
    }

    private function retrieveApplications(Configuration $config)
    {
        if($config->read('process/applications/enabled', false))
        {
            $apps = [];

            foreach($this->servers as $server)
            {
                $apps = array_merge($apps, $server->extractApplicationList());
            }

            $apps = array_unique($apps);

            return $apps;
        }

        return false;
    }

    public function sidebarAction()
    {
        return $this->render('sidebar.twig', [
            'currentServer' => $this->request->attributes->get('serverName', null),
            'currentApplication' => $this->request->attributes->get('applicationName', null),
            'servers' => $this->servers,
            'apps' => $this->retrieveApplications($this->configuration)
        ]);
    }
}
