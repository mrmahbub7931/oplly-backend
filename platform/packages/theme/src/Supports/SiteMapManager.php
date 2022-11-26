<?php

namespace Canopy\Theme\Supports;

use AdminBar;
use BaseHelper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Canopy\Sitemap\Sitemap;

class SiteMapManager
{
    /**
     * @var Sitemap
     */
    protected $siteMap;

    /**
     * SiteMapManager constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        // create new site map object
        $this->siteMap = app()->make('sitemap');

        // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
        // by default cache is disabled
        $this->siteMap->setCache('public.sitemap', config('core.base.general.cache_site_map'));

        if (!BaseHelper::getHomepageId()) {
            $this->siteMap->add(url('/'), '2020-11-15 10:00', '1.0', 'daily');
        }

        AdminBar::setIsDisplay(false);
    }

    /**
     * @param string $url
     * @param string $date
     * @param string $priority
     * @param string $sequence
     * @return $this
     */
    public function add($url, $date, $priority = '1.0', $sequence = 'daily')
    {
        if (!$this->siteMap->isCached()) {
            $this->siteMap->add($url, $date, $priority, $sequence);
        }

        return $this;
    }

    /**
     * @param string $type
     * @return string
     */
    public function render($type = 'xml')
    {
        // show your site map (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $this->siteMap->render($type);
    }
}
