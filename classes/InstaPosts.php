<?php
/**
 * Class InstaWidget | classes\InstaWidget.php
 *
 * @package classes
 */

namespace classes;

use chillerlan\SimpleCache\CacheOptions;
use chillerlan\SimpleCache\FileCache;
use InstagramScraper\Instagram;
use InstagramScraper\Model\Media;

/**
 * Class InstaPosts
 * Getting posts from instagram for outputing
 * @package classes
 */
class InstaPosts
{
    const TIME_DURATION = 600;

    /** @var FileCache $cache */
    private $cache;

    /** @var string cache_dir cache directory */
    private $cache_dir = __DIR__.'/../.cache';

    /** @var int $cache_time time by default 10 min*/
    private $cache_time = 600;

    /** @var Instagram $scraper */
    private $scraper;

    /**
     * @var int $limit Limit medias for user
     */
    private $limit = 10;

    /**
     * InstaPosts constructor.
     * @throws \chillerlan\SimpleCache\CacheException
     */
    public function __construct()
    {
        if(!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755);
        }
        $this->cache = new FileCache(new CacheOptions(['cacheFilestorage' => $this->cache_dir]));
        $this->scraper = new Instagram();
    }

    /**
     * Method returns
     * @param array $niks
     * @return array
     * @throws \InstagramScraper\Exception\InstagramException
     * @throws \InstagramScraper\Exception\InstagramNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getPosts(array $niks):array {
        $medias = [];
        foreach ($niks as $nikname) {

            if(empty($nikname)) {
                continue;
            }

            $media = $this->cache->get($nikname);
            if(empty($media)) {
                $media = $this->scraper->getMedias($nikname, $this->limit);
                if(!empty($media)) {
                    $this->cache->set($nikname, $media, $this->cache_time);
                }
            }
            $medias[$nikname] = $this->convertUserPosts($media);
        }
        return  $this->sortingPosts($medias);
    }

    /**
     * Convert object to array
     * @param array $medias
     * @return array
     */
    private function convertUserPosts(array $medias): array
    {
        $posts = [];

        /** @var Media $media */
        foreach ($medias as $key=>$media) {
            $posts[$key]['locationName'] = $media->getLocationName();
            $posts[$key]['createdTime'] = $media->getCreatedTime();
            $posts[$key]['caption'] = $media->getCaption();
            $posts[$key]['imageThumbnailUrl'] = $media->getImageThumbnailUrl();
            $posts[$key]['imageHighResolutionUrl'] = $media->getImageHighResolutionUrl();
            $posts[$key]['link'] = $media->getLink();
        }
        return $posts;
    }

    /**
     * Sorting posts by create time desc
     * @param $medias
     * @return array
     */
    private function sortingPosts($medias)
    {
        $sorting = [];
        foreach ($medias as $media) {
            foreach ($media as $posts) {
                $sorting[] = $posts;
            }
        }
        usort($sorting, function($a,$b){
            $value1 = $a['createdTime'];
            $value2 = $b['createdTime'];
            return $value2 - $value1;
        });

        return $sorting;
    }
}