<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\AdminMenu;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(PageRegistry::class)]
final class PageRegistryTest extends TestCase
{
    use PHPMock;

    private PageRegistry $registry;
    private MockObject $pageMock;

    #[Before]
    public function setUp(): void
    {
        $this->registry = new PageRegistry();
        $this->pageMock = $this->getMockBuilder(Page::class)->disableOriginalConstructor()->getMock();
    }

    private function capture(&$destination): Callback
    {
        return $this->callback(static function ($source) use (&$destination) {
            $destination = $source;

            return true;
        });
    }

    #[Test]
    public function shouldRegisterMenuPagesAtAppropriateHook(): void
    {
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->once())
            ->with('admin_menu', $this->anything());

        $this->registry->register($this->pageMock);
    }

    #[Test]
    public function shouldRegisterMenuPageIfNoParent(): void
    {
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_menu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->anything(),
                $this->pageMock->icon(),
                $this->pageMock->position()
            );

        $this->registry->register($this->pageMock);
        $actionCallback();
    }

    #[Test]
    public function shouldRegisterSubmenuPageIfParent(): void
    {
        $this->pageMock->method('parentSlug')->willReturn('parent');

        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_submenu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->parentSlug(),
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->anything(),
                $this->pageMock->position()
            );

        $this->registry->register($this->pageMock);
        $actionCallback();
    }

    #[Test]
    public function shouldRegisterMenuPageRenderCallback(): void
    {
        $this->pageMock->expects($this->once())->method('render');
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_menu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->capture($renderCallback),
                $this->pageMock->icon(),
                $this->pageMock->position()
            );
        $this->getFunctionMock(__NAMESPACE__, 'current_user_can')
            ->expects($this->once())
            ->with($this->pageMock->capability())
            ->willReturn(true);

        $this->registry->register($this->pageMock);
        $actionCallback();
        $renderCallback();
    }

    #[Test]
    public function shouldRegisterSubmenuPageRenderCallback(): void
    {
        $this->pageMock->method('parentSlug')->willReturn('parent');
        $this->pageMock->expects($this->once())->method('render');
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_submenu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->parentSlug(),
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->capture($renderCallback),
                $this->pageMock->position()
            );
        $this->getFunctionMock(__NAMESPACE__, 'current_user_can')
            ->expects($this->once())
            ->with($this->pageMock->capability())
            ->willReturn(true);

        $this->registry->register($this->pageMock);
        $actionCallback();
        $renderCallback();
    }

    #[Test]
    public function shouldRegisterMenuPageLoadCallback(): void
    {
        $this->pageMock->expects($this->once())->method('load');
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_menu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->anything(),
                $this->pageMock->icon(),
                $this->pageMock->position()
            );

        $this->registry->register($this->pageMock);
        $actionCallback();
        $actionCallback();
    }

    #[Test]
    public function shouldRegisterSubmenuPageLoadCallback(): void
    {
        $this->pageMock->method('parentSlug')->willReturn('parent');
        $this->pageMock->expects($this->once())->method('load');
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_submenu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->parentSlug(),
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->anything(),
                $this->pageMock->position()
            );

        $this->registry->register($this->pageMock);
        $actionCallback();
        $actionCallback();
    }

    #[Test]
    public function shouldEnsureUserHasPermissionToRenderPage(): void
    {
        $this->pageMock->expects($this->never())->method('render');
        $this->getFunctionMock(__NAMESPACE__, 'add_action')
            ->expects($this->any())
            ->with($this->anything(), $this->capture($actionCallback));
        $this->getFunctionMock(__NAMESPACE__, 'add_menu_page')
            ->expects($this->once())
            ->with(
                $this->pageMock->title(),
                $this->pageMock->title(),
                $this->pageMock->capability(),
                $this->pageMock->slug(),
                $this->capture($renderCallback),
                $this->pageMock->icon(),
                $this->pageMock->position()
            );
        $this->getFunctionMock(__NAMESPACE__, 'current_user_can')
            ->expects($this->once())
            ->with($this->pageMock->capability())
            ->willReturn(false);

        $this->registry->register($this->pageMock);
        $actionCallback();
        $renderCallback();
    }
}
