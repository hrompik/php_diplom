<?php

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

class CacheMenu
{
    public function __construct(private CacheItemPoolInterface $cache)
    {
    }

    private function checkMenu(): void
    {
        if (date_diff(new \DateTime(), $this->lastDateTimeUpdateMenu(), true)->days > 1) {
            $this->resetMenu();
            $this->lastDateTimeUpdateMenu();
        }
    }

    public function getMenu(\Closure $query)
    {
        $this->checkMenu();
        return $this->cache->get(
            'menu',
            $query
        );
    }

    public function resetMenu(): void
    {
        $this->cache->deleteItem('menu');
        $this->cache->deleteItem('menuLastTimeUpdate');
    }

    private function lastDateTimeUpdateMenu(): \DateTime
    {
        return $this->cache->get(
            'menuLastTimeUpdate',
            function () {
                return new \DateTime();
            }
        );
    }
}
