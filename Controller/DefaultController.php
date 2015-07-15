<?php

namespace RepoSiteMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Description of RepoSiteMapBundle\Controller\DefaultController
 *
 * @author Bóta László Gábor
 * @package RepoSiteMapBundle
 * @subpackage RepoSiteMapBundle\Controller
 * @version 0.1
 *
 * @see Controller
 */
class DefaultController extends Controller
{
    /**
     * Generating sitemap xml file
     * 
     * @Route("/sitemap.{_format}", name="CoreBundle_sitemap", requirements={"_format" = "xml"})
     */
    public function sitemapAction() {
        $sitemapService = $this->get('repo.sitemap');
        $generatedUrls = $sitemapService->generateSitemapUrls();

        return $this->render('RepoSiteMapBundle:Default:sitemap.xml.twig', array('urls' => $generatedUrls));
    }
}
