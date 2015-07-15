<?php

namespace RepoSiteMapBundle\Services;

use Doctrine\ORM\EntityManager;

/**
 * Description of RepoSiteMapBundle\Services\SitemapService
 *
 * @author Bóta László Gábor
 * @package RepoSiteMapBundle
 * @subpackage RepoSiteMapBundle\Services
 * @version 0.1
 */
class SitemapService {

    private static $optionTypeStatic = 1;
    private static $optionTypeDynamic = 2;
    protected $em;
    protected $router;
    protected $routes;
    protected $generatedUrls;

    public function __construct(EntityManager $em, $router) {
        $this->em = $em;
        $this->router = $router;
        $this->routes = $this->router->getRouteCollection()->all();
    }

    /**
     * Generating sitemap urls.
     * The urls are going to be used in the sitemap xml file.
     *
     * @return mixed Generated sitmap urls for sitemap xml.
     */
    public function generateSitemapUrls() {
        $createdSitemapUrls = array();

        foreach ($this->routes as $route => $params) {
            if ($this->hasSitemapOption($params)) {
                $sitemapOptions = $this->getSitemapOptions($params);
                $createdSitemapUrls[] = $this->createRouteControllers($route, $sitemapOptions);
            }
        }

        return $this->generatedUrls = $this->createFormattedSitemapUrls($createdSitemapUrls);
    }

    /**
     * Checking route's has sitemap option.
     *
     * @param mixed $params parameters of route.
     * @return bool route has sitemap option or not. 
     */
    protected function hasSitemapOption($params) {
        $hasSitemapOption = false;

        if ($params->getOption('sitemap')) {
            $hasSitemapOption = true;
        }

        return $hasSitemapOption;
    }

    /**
     * Get sitemap option's value.
     *
     * @param mixed $params parameters of route.
     * @return mixed option values which contain the type and/or repository.
     */
    protected function getSitemapOptions($params) {
        $result = array();

        $option = $params->getOption('sitemap');

        if ($option === null) {
            return null;
        } else if ($option === true) {
            $result['type'] = self::$optionTypeStatic;
        } else if (is_array($option)) {
            $result['type'] = self::$optionTypeDynamic;
            $result['repository'] = $option['repository'];
        }

        return $result;
    }

    /**
     * Creating route controllers by actual route and it's sitemap option values.
     *
     * @param mixed $route actual route.
     * @param mixed $sitemapOptions sitemap option values.
     * @return mixed routing controllers.
     */
    protected function createRouteControllers($route, $sitemapOptions) {
        $result = array();
        switch ($sitemapOptions['type']) {
            case self::$optionTypeStatic :
                $result[] = $this->router->generate($route);
                break;
            case self::$optionTypeDynamic :
                $result = array_merge($result, $this->generateEntityRouteControllers($route, $sitemapOptions['repository']));
                break;
        }

        return $result;
    }

    /**
     * Generating entity route controllers by actual route and repository name.
     *
     * @param mixed $route actual route.
     * @param string $repository repository's name.
     * @return mixed generated route controlles for entity repository.
     */
    protected function generateEntityRouteControllers($route, $repository) {
        $result = array();
        $limit = $this->countEntities($repository);

        for ($i = 1; $i <= $limit; $i++) {
            $result[] = $this->router->generate($route) . "/" . $i;
        }

        return $result;
    }

    /**
     * Creating formatted urls for sitemap.
     *
     * @param mixed $urls generated sitemap urls
     * @return mixed formatted urls for sitemap.
     */
    protected function createFormattedSitemapUrls($urls) {
        $formattedUrls = array();

        foreach ($urls as $urlBlock) {
            if (count($urlBlock) === 1) {
                $formattedUrls[] = array('url' => $urlBlock[0], 'changefreq' => 'weekly', 'priority' => 1.0);
            } else {
                foreach ($urlBlock as $url) {
                    $formattedUrls[] = array('url' => $url, 'changefreq' => 'daily', 'priority' => 0.8);
                }
            }
        }

        return $formattedUrls;
    }

    /**
     * Counting the entities in the repository (database table).
     *
     * @param string $repository name of repository.
     * @return integer number of entities in the repository.
     */
    private function countEntities($repository) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from($repository, 'e');
        $qb->orderBy('e.id', 'desc');

        return $qb->getQuery()->getSingleScalarResult();
    }

}
