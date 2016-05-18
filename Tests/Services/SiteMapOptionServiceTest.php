<?php

namespace RepoSitemapBundle\Tests\Services;

use RepoSitemapBundle\Services\SiteMapOptionService;
use Symfony\Component\Routing\Route;

class SiteMapOptionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SiteMapOptionService
     */
    private $mockedSiteMapOptionService;

    /**
     * @var \Symfony\Component\Routing\Route
     */
    private $mockedRoute;

    /**
     * @var Route
     */
    private $route;

    /**
     * @var mixed
     */
    private $sitemapOption;

    public function setUp() {
        parent::setUp();
        $this->mockedSiteMapOptionService = $this
            ->getMockBuilder('RepoSitemapBundle\Services\SiteMapOptionService')
            ->disableOriginalConstructor()
            ->setMethods(array('getOptionValues', 'setOtherOptions'))
            ->getMock();

        $this->mockedRoute = $this
            ->getMockBuilder('Symfony\Component\Routing\Route')
            ->disableOriginalConstructor()
            ->getMock();

        $this->sitemapOption = array(
            'repository' => 'AppBundle:Test',
            'lastmod' =>    '2000-01-01',
            'changefreq' => 'daily',
            'priority' =>   0.67
        );

        $this->route = new Route('tests');
        $this->route->setOption('sitemap', $this->sitemapOption);
    }

    public function testHasSitemapOption() {
        $this->assertTrue($this->mockedSiteMapOptionService->hasSitemapOption($this->route));
    }

    public function testGetSitemapOptionsReturnsNull() {
        $this->assertNull($this->mockedSiteMapOptionService->getSitemapOptions($this->mockedRoute));
    }

    public function testGetSitemapOptionsReturnsData() {
        $result = $this->mockedSiteMapOptionService->getSitemapOptions($this->route);

        $this->assertNotNull($result);
        $this->assertEquals('AppBundle:Test', $result['repository']);
        $this->assertEquals('2000-01-01', $result['lastmod']);
        $this->assertEquals('daily', $result['changefreq']);
        $this->assertEquals(0.67, $result['priority']);
    }
}
