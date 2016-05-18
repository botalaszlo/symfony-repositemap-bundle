<?php

namespace RepoSitemapBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

/**
 * Description of RepoSitemapBundle\Services\SiteMapControllerService
 *
 * @author Bóta László <bota.laszlo.dev@outlook.com>
 * @package RepoSitemapBundle
 * @subpackage RepoSitemapBundle\Services
 * @version 1.1.0
 */
class SiteMapControllerService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * SiteMapControllerService constructor.
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct($em, $router)
    {
        $this->em = $em;
        $this->router = $router;
    }
    
    /**
     * Creating route controllers by actual route and it's sitemap option values.
     *
     * @param mixed $route actual route.
     * @param mixed $sitemapInfo sitemap option values.
     * @return mixed routing controllers.
     */
    public function createRouteControllers($route, $sitemapInfo) {
        $createdRoutes = array();

        switch ($sitemapInfo['type']) {
            case SiteMapOptionService::TYPE_STATIC :
                $createdRoutes[] = $this->router->generate($route);
                break;
            case SiteMapOptionService::TYPE_DYNAMIC :
                $createdRoutes = array_merge(
                    $createdRoutes, $this->generateEntityRouteControllers($route, $sitemapInfo)
                );
                break;
        }
        return $createdRoutes;
    }
    
    /**
     * Generating entity route controllers by actual route and repository name.
     *
     * @param mixed $route actual route.
     * @param mixed $sitemapInfo sitemap information: repository name, lastmod, priority, changefreq
     * @return mixed generated route controllers for entity repository.
     */
    private function generateEntityRouteControllers($route, $sitemapInfo) {
        $result = array();
        $limit = $this->countEntities($sitemapInfo['repository']);
        for ($i = 1; $i <= $limit; $i++) {
            $result[] = array(
                'url' =>        $this->router->generate($route) . $i,
                'lastmod' =>    $sitemapInfo['lastmod'],
                'changefreq' => $sitemapInfo['changefreq'],
                'priority' =>   $sitemapInfo['priority']
            );
        }
        return $result;
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
        return $qb->getQuery()->getSingleScalarResult();
    }
}