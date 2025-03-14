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
 * Represents a theme submenu page.
 */
abstract class ThemePage extends Page
{
    /** {@inheritDoc} */
    protected ?string $parentSlug = 'themes.php';
}
