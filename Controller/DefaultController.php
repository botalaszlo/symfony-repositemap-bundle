<?php

namespace RepoSitemapBundle\Controller;

use RepoSitemapBundle\Services\SiteMapService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of RepoSitemapBundle\Controller\DefaultController
 *
 * @author Bóta László <bota.laszlo.dev@outlook.com>
 * @package RepoSitemapBundle
 * @subpackage RepoSitemapBundle\Controller
 * @version 1.1.0
 */
class DefaultController extends Controller
{
    /**
     * @var SiteMapService
     */
    protected $siteMapService;

    /**
     * Generating sitemap xml file
     *
     * @Route("/sitemap.{_format}", name="RepoSitemapBundle_sitemap", requirements={"_format" = "xml"})
     */
    public function siteMapAction() {
        $this->siteMapService = $this->get('repo.sitemap');
        $generatedUrls = $this->siteMapService->generateSitemapUrls();
        return $this->render('RepoSitemapBundle:Default:sitemap.xml.twig', array('urls' => $generatedUrls));
    }
}
