<?php

namespace RepoSitemapBundle\Services;


/**
 * Description of RepoSitemapBundle\Services\SiteMapOptionService
 *
 * @author Bóta László <bota.laszlo.dev@outlook.com>
 * @package RepoSitemapBundle
 * @subpackage RepoSitemapBundle\Services
 * @version 1.1.0
 */
class SiteMapOptionService
{
    const TYPE_STATIC = 'static';
    const TYPE_DYNAMIC = 'dynamic';

    /**
     * @var string option key
     */
    private static $optionRepository= 'repository';
    /**
     * @var string option key
     */
    private static $optionLastModified = 'lastmod';
    /**
     * @var string option key
     */
    private static $optionChangefreq = 'changefreq';
    /**
     * @var string option key
     */
    private static $optionPriority = 'priority';

    /**
     * @var string sitemap naming in option of route of controller
     */
    private static $optionSitemap = 'sitemap';


    private $sitemapInfo = array();

    /**
     * Checks route's has sitemap option.
     *
     * @param mixed $params parameters of route.
     * @return bool route has sitemap option or not.
     */
    public function hasSitemapOption($params) {
        return $params->getOption(self::$optionSitemap) ? true : false;
    }
    
    /**
     * Get sitemap option's value.
     *
     * @param mixed $params parameters of route.
     * @return mixed option values which contain the type and/or repository.
     */
    public function getSitemapOptions($params) {
        $option = $params->getOption('sitemap');

        if ($option === null) {
            return null;
        } 
        return $this->getOptionValues($option);
    }

    /**
     * Generate option values.
     *
     * @param mixed|boolean $option of route of controller
     * @return mixed sitemap info
     */
    private function getOptionValues($option) {
        if ($option === true) {
            $this->sitemapInfo['type'] = self::TYPE_STATIC;
        } else if (is_array($option)) {
            $this->sitemapInfo['type'] = self::TYPE_DYNAMIC;
            $this->setOtherOptions($option);
        }

        return $this->sitemapInfo;
    }

    /**
     * Set the other options.
     * The available options are pre-definied. It one of available option does not exist in the
     * passed $option array then it will not added to the sitemap.
     *
     * @param mixed $option option of route annotation
     */
    private function setOtherOptions($option) {
        $availableOptions = array(
            self::$optionRepository, self::$optionLastModified, self::$optionChangefreq, self::$optionPriority
        );
        foreach ($availableOptions as $availableOption) {
            if (isset($option[$availableOption]) && !empty($option[$availableOption])) {
                $this->sitemapInfo[$availableOption]    = $option[$availableOption];
            }
        }
    }
}