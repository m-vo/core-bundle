<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Menu;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Menu\BackendMenuBuilder;
use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BackendMenuBuilderTest extends TestCase
{
    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * @var BackendMenuBuilder
     */
    private $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->builder = new BackendMenuBuilder(new MenuFactory(), $this->eventDispatcher);
    }

    public function testCanBeInstantiated(): void
    {
        $this->assertInstanceOf('Contao\CoreBundle\Menu\BackendMenuBuilder', $this->builder);
    }

    public function testCreatesTheRootNode(): void
    {
        $tree = $this->builder->create();

        $this->assertInstanceOf('Knp\Menu\ItemInterface', $tree);
        $this->assertSame('root', $tree->getName());
    }

    public function testDispatchesTheMenuBuildEvent(): void
    {
        $this->eventDispatcher
            ->expects($this->atLeastOnce())
            ->method('dispatch')
            ->with(ContaoCoreEvents::BACKEND_MENU_BUILD, $this->isInstanceOf('Contao\CoreBundle\Event\MenuEvent'))
        ;

        $this->builder->create();
    }
}
