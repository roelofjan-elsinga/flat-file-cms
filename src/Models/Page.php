<?php

namespace FlatFileCms\Models;

use FlatFileCms\HtmlParser;
use FlatFileCms\Models\Contracts\ModelInterface;
use FlatFileCms\Models\Contracts\PublishInterface;
use FlatFileCms\Models\Traits\Postable;
use FlatFileCms\Models\Traits\Publishable;
use FlatFileCms\Models\Traits\Updatable;

class Page extends Model implements ModelInterface, PublishInterface
{
    use Publishable, Postable, Updatable;

    protected $folder = 'pages';

    protected $required_fields = [
        'title',
        'description',
        'post_date',
        'is_published',
        'is_scheduled',
        'summary',
        'template_name'
    ];

    /**
     * Determine whether this article is scheduled
     *
     * @return bool
     */
    public function isHomepage(): bool
    {
        return $this->matter['is_homepage'] ?? false;
    }

    /**
     * Get the page that's marked as the homepage
     *
     * @return Page|null
     */
    public static function homepage(): ?Page
    {
        return Page::published()
            ->filter(function (Page $page) {
                return $page->isHomepage();
            })
            ->first();
    }

    /**
     * Get the title of this resource
     *
     * @return string
     * @throws \Exception
     */
    public function title(): string
    {
        return $this->matter['title'] ?? $this->getParsedTitleFromContent();
    }

    /**
     * Get the parsed title from the page content
     *
     * @return string
     * @throws \Exception
     */
    private function getParsedTitleFromContent(): string
    {
        $file_content = $this->body();

        $titles = HtmlParser::getTextBetweenTags($file_content, 'h1');

        return $titles[0] ?? "Untitled page";
    }

    /**
     * Get the description of this resource
     *
     * @return string
     * @throws \Exception
     */
    public function description(): string
    {
        return $this->matter['description'] ?? $this->getDescriptionFromContent();
    }

    /**
     * Generate a description for the content
     *
     * @return bool|string
     * @throws \Exception
     */
    protected function getDescriptionFromContent(): string
    {
        $paragraphs = HtmlParser::getTextBetweenTags($this->body(), 'p');

        $paragraphs_with_text_content = array_filter($paragraphs, function ($paragraph) {
            return !empty(strip_tags($paragraph));
        });

        if (count($paragraphs_with_text_content) > 0) {
            return substr(head($paragraphs_with_text_content), 0, 160);
        }

        return "";
    }

    /**
     * Get the summary of this page
     *
     * @return string
     */
    public function summary(): string
    {
        return $this->matter['summary'] ?? '';
    }

    /**
     * Get the template name of this page
     *
     * @return string
     */
    public function templateName(): string
    {
        return $this->matter['template_name'] ?? 'default';
    }

    /**
     * Get the type of this page
     *
     * @return string
     */
    public function type(): string
    {
        return "website";
    }

    /**
     * Get the main image of this page
     *
     * @return string
     * @throws \Exception
     */
    public function image(): string
    {
        return $this->matter['image'] ?? "";
    }

    /**
     * Get the path to the thumbnail of this page
     *
     * @return string
     * @throws \Exception
     */
    public function thumbnail(): string
    {
        return $this->matter['image'] ?? "";
    }

    /**
     * Determine whether this page is in the menu
     *
     * @return bool
     */
    public function isInMenu(): bool
    {
        return $this->matter['in_menu'] ?? false;
    }

    /**
     * Get the menu name of the page
     *
     * @return string
     * @throws \Exception
     */
    public function menuName(): string
    {
        return $this->matter['menu_name'] ?? $this->title();
    }

    /**
     * Get the keywords of this page
     *
     * @return string
     */
    public function keywords(): string
    {
        return $this->matter['keywords'] ?? '';
    }

    /**
     * Get the meta data for this page
     *
     * @return array|null
     */
    public function metaData(): ?array
    {
        return $this->matter['meta_data'] ?? null;
    }

    /**
     * Get the author of this page
     *
     * @return string
     */
    public function author(): string
    {
        return $this->matter['author'] ?? '';
    }

    /**
     * Get the canonical if it's set
     *
     * @return null|string
     */
    public function canonicalLink(): ?string
    {
        return $this->matter['canonical'] ?? null;
    }

    /**
     * Get the url of this page
     *
     * @return string
     */
    public function url(): string
    {
        return $this->matter['url'] ?? $this->file_name;
    }
}
