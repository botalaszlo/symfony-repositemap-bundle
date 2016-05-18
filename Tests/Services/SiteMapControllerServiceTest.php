<?php

namespace RepoSitemapBundle\Tests\Services;


use RepoSitemapBundle\Services\SiteMapControllerService;
use RepoSitemapBundle\Services\SiteMapOptionService;
use Symfony\Bundle\SecurityBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Routing\Route;

class SiteMapControllerServiceTest extends WebTestCase
{
    /**
     * @var SiteMapControllerService
     */
    private $mockedSiteMapControllerService;

    /**
     * @var \Symfony\Component\Routing\Route
     */
    private $mockedRoute;

    /**
     * @var \Symfony\Component\Routing\Route
     */
    private $route;

    /**
     * @var mixed
     */
    private $sitemapInfo;

    public function setUp() {
        parent::setUp();

        $this->mockedRoute = $this
            ->getMockBuilder('Symfony\Component\Routing\Route')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedSiteMapControllerService = $this
            ->getMockBuilder('RepoSitemapBundle\Services\SiteMapControllerService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->sitemapInfo = array(
            'type' =>       SiteMapOptionService::TYPE_DYNAMIC,
            'repository' => 'AppBundle:Test',
            'lastmod' =>    '2000-01-01',
            'changefreq' => 'daily',
            'priority' =>   0.67
        );

        $this->route = new Route('tests');
        $this->route->setOption('sitemap', $this->sitemapInfo);

    }

    public function testCreateRouteControllers() {
        $generatedRoutes = array();
        for ($i = 1; $i <= 9; $i++) {
            $generatedRoutes[] = array(
                'url' =>        'Test' . $i,
                'lastmod' =>    '2000-01-0' . $i,
                'changefreq' => 'daily',
                'priority' =>   0.5
            );
        }

        $this->mockedSiteMapControllerService
            ->method('createRouteControllers')
            ->willReturn($generatedRoutes);

        $this->assertEquals(
            $generatedRoutes,
            $this->mockedSiteMapControllerService->createRouteControllers($this->route, $this->sitemapInfo)
        );
    }
}
