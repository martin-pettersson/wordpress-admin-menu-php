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
 * Represents a post submenu page.
 */
abstract class PostPage extends Page
{
    /** {@inheritDoc} */
    protected ?string $parentSlug = 'edit.php';
}
