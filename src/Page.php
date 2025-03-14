<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\AdminMenu;

/**
 * Represents a WordPress admin menu page.
 */
abstract class Page
{
    /**
     * Parent page slug.
     *
     * @var string|null
     */
    protected ?string $parentSlug = null;

    /**
     * Page slug.
     *
     * @var string
     */
    protected string $slug;

    /**
     * Page title.
     *
     * @var string
     */
    protected string $title;

    /**
     * Page title used in the title tag.
     *
     * @var string|null
     */
    protected ?string $pageTitle = null;

    /**
     * Capability required to access the page.
     *
     * @var string
     */
    protected string $capability = 'manage_options';

    /**
     * Menu item icon.
     *
     * @var string
     */
    protected string $icon = '';

    /**
     * Menu item position.
     *
     * @var int|null
     */
    protected ?int $position = null;

    /**
     * Create a new admin menu page instance.
     *
     * @param string $slug Arbitrary slug.
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;

        if (! isset($this->title)) {
            $this->title = implode(' ', array_map(static fn($word) => ucfirst($word), explode('_', $slug)));
        }
    }

    /**
     * Produce a string representation of the page.
     *
     * @return string String representation of the page.
     */
    abstract public function render(): string;

    /**
     * Execute any logic at the page load-hook.
     */
    public function load(): void
    {
    }

    /**
     * Retrieve the parent page slug.
     *
     * @return string|null Parent page slug.
     */
    public function parentSlug(): ?string
    {
        return $this->parentSlug;
    }

    /**
     * Retrieve the page slug.
     *
     * @return string Page slug.
     */
    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * Retrieve the page title.
     *
     * @return string Page title.
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Retrieve the page title used in the title tag.
     *
     * @return string|null Page title used in the title tag.
     */
    public function pageTitle(): ?string
    {
        return $this->pageTitle;
    }

    /**
     * Retrieve the capability required to access the page.
     *
     * @return string Capability required to access the page.
     */
    public function capability(): string
    {
        return $this->capability;
    }

    /**
     * Retrieve the menu item icon.
     *
     * @return string Menu item icon.
     */
    public function icon(): string
    {
        return $this->icon;
    }

    /**
     * Retrieve the menu item position.
     *
     * @return int|null Menu item position.
     */
    public function position(): ?int
    {
        return $this->position;
    }
}
