<?php
/**
 * Class InstaWidget | classes\InstaWidget.php
 *
 * @package classes
 */

namespace classes;


/**
 * Class InstaWidget
 * Widjet for output posts
 * @package classes
 */
class InstaWidget
{
    const LIMIT = 10;

    /**
     * @var FileAction $fileAction
     */
    private $fileAction;
    /**
     * @var InstaPosts $instaPosts
     */
    private $instaPosts;

    /**
     * @var string template for row block
     */
    private $template_row = '<div class="row mb-2">{row}</div>';

    /**
     * @var string template for post block
     */
    private $template_post = '<div class="col-md-6">
        <div class="card flex-md-row mb-4 box-shadow h-md-250">
            <div class="card-body d-flex flex-column align-items-start">   
            <strong class="d-inline-block mb-2 text-primary">{post_location}</strong>  
                <div class="mb-1 text-muted">{post_date}</div>
                <p class="card-text mb-auto">{post_content}<a href="{post_link}" target="_blank">...</a></p>            
            </div>
            <img class="card-img-right flex-auto d-none d-md-block" src="{post_image}" alt="{post_title}">
        </div>
    </div>';

    /**
     * Static function widget
     * Outputing Instagram posts
     * @return string
     */
    public static function widget()
    {
        return (new self)->run();
    }

    /**
     * Object initialization
     */
    private function init()
    {
        $this->fileAction = new FileAction();
        $this->instaPosts = new InstaPosts();
    }

    /**
     * Run function for widget
     * if you need to outputing more then 10 posts put to $config limit value
     * for instance ['limit'=>15]
     * @param array $config
     * @return string
     */
    private function run($config = [])
    {
        $this->init();
        $limit = $config['limit'] ?? self::LIMIT;
        $content = '';

        $niks = $this->fileAction->getNiks() ?? [];

        if (empty($niks) || !is_array($niks)) {
            return $content;
        }

        $posts = $this->instaPosts->getPosts($niks);
        $posts = array_slice($posts, 0, $limit);

        $i = 0;
        $row = '';
        $length = count($posts);

        foreach ($posts as $key => $post) {
            $row .= strtr(
                $this->template_post,
                [
                    '{post_title}' => $post['altText'],
                    '{post_location}' => $post['locationName'],
                    '{post_content}' => mb_substr($post['caption'], 0, 150),
                    '{post_date}' => date('d-m-Y H:i', $post['createdTime']),
                    '{post_image}' => $post['imageThumbnailUrl'],
                    '{post_link}' => $post['link'],
                ]
            );

            $i++;
            if ($i == 2 || $key == $length - 1) {
                $content .= strtr(
                    $this->template_row,
                    [
                        '{row}' => $row
                    ]
                );
                $i = 0;
                $row = '';
            }
        }
        return $content;
    }
}