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
 * Represents a WordPress admin menu page registry.
 */
final class PageRegistry
{
    /**
     * Register given admin menu page.
     *
     * @param \N7e\WordPress\AdminMenu\Page $page Arbitrary page.
     */
    public function register(Page $page): void
    {
        add_action('admin_menu', fn() => $this->registerPage($page));
    }

    /**
     * Register given page.
     *
     * @param \N7e\WordPress\AdminMenu\Page $page Arbitrary page.
     */
    private function registerPage(Page $page): void
    {
        if (! is_null($page->parentSlug())) {
            $this->registerSubmenuPage($page);

            return;
        }

        $hookSuffix = add_menu_page(
            $page->pageTitle() ?? $page->title(),
            $page->title(),
            $page->capability(),
            $page->slug(),
            fn() => $this->render($page),
            $page->icon(),
            $page->position()
        );

        add_action("load-{$hookSuffix}", [$page, 'load']);
    }

    /**
     * Register given admin submenu page.
     *
     * @param \N7e\WordPress\AdminMenu\Page $page Arbitrary submenu page.
     */
    private function registerSubmenuPage(Page $page): void
    {
        $hookSuffix = add_submenu_page(
            $page->parentSlug(),
            $page->pageTitle() ?? $page->title(),
            $page->title(),
            $page->capability(),
            $page->slug(),
            fn() => $this->render($page),
            $page->position()
        );

        if ($hookSuffix !== false) {
            add_action("load-{$hookSuffix}", [$page, 'load']);
        }
    }

    /**
     * Render given page if appropriate.
     *
     * @param \N7e\WordPress\AdminMenu\Page $page Arbitrary page.
     */
    private function render(Page $page): void
    {
        if (! current_user_can($page->capability())) {
            return;
        }

        echo $page->render();
    }
}
