<?php
/**
 * Created by PhpStorm.
 * User: lacc
 * Date: 2016.05.15.
 * Time: 14:39
 */

namespace RepoSitemapBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

/**
 * Description of RepoSitemapBundle\Services\SiteMapService
 *
 * @author Bóta László <bota.laszlo.dev@outlook.com>
 * @package RepoSitemapBundle
 * @subpackage RepoSitemapBundle\Services
 * @version 1.1.0
 */
class SiteMapService {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var \Symfony\Component\Routing\Route[]
     */
    protected $routes;

    /**
     * @var mixed generated urls for sitemap
     */
    protected $generatedSiteMapUrls;

    /**
     * @var SiteMapOptionService
     */
    protected $siteMapOptionService;

    /**
     * @var SiteMapControllerService
     */
    protected $siteMapControllerService;

    /**
     * SiteMapService constructor.
     *
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(EntityManager $em, $router) {
        $this->em = $em;
        $this->router = $router;
        $this->routes = $this->router->getRouteCollection()->all();

        $this->siteMapOptionService = new SiteMapOptionService();
        $this->siteMapControllerService = new SiteMapControllerService($em, $router);
    }

    /**
     * Generating sitemap urls.
     * The urls be used in the sitemap xml file.
     *
     * @return mixed Generated sitemap urls for sitemap xml.
     */
    public function generateSitemapUrls() {
        $createdSitemapUrls = array();

        foreach ($this->routes as $route => $params) {
            if ($this->siteMapOptionService->hasSitemapOption($params)) {
                array_push(
                    $createdSitemapUrls,
                    $this->siteMapControllerService->createRouteControllers(
                        $route, $this->siteMapOptionService->getSitemapOptions($params)
                    )
                );
            }
        }

        return $createdSitemapUrls;
    }

    
    
    
}