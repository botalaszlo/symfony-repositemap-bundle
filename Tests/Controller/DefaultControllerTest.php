<?php

namespace RepoSitemapBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSitemapAction()
    {
        $client = static::createClient();

        $generatedSitemapUrl = array(
            array(
                array(
                    'url' => 'test/1',
                    'lastmod' => '2010-01-01',
                    'changefreq' => 'daily',
                    'priority' => 0.9
                )
            )
        );

        $sitemapService = $this->getMockBuilder('RepoSitemapBundle\Services\SiteMapService')
            ->disableOriginalConstructor()
            ->getMock();
        $sitemapService
            ->method('generateSitemapUrls')
            ->willReturn($generatedSitemapUrl);
        $client->getContainer()->set('repo.sitemap', $sitemapService);
        $crawler = $client->request('GET', '/sitemap.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Resources/sample.xml',
            $client->getResponse()->getContent()
        );
    }
}
