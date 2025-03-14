<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\AdminMenu;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Page::class)]
final class PageTest extends TestCase
{
    const string SLUG = 'some_slug';

    private Page $page;

    #[Before]
    public function setUp(): void
    {
        $this->page = new Fixtures\Page(PageTest::SLUG);
    }

    #[Test]
    public function shouldInitializeProperly(): void
    {
        $this->assertNull($this->page->parentSlug());
        $this->assertEquals(PageTest::SLUG, $this->page->slug());
        $this->assertEquals('Some Slug', $this->page->title());
        $this->assertNull($this->page->pageTitle());
        $this->assertEquals('manage_options', $this->page->capability());
        $this->assertEquals('', $this->page->icon());
        $this->assertNull($this->page->position());
    }
}
