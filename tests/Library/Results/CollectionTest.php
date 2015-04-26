<?php

namespace Tests\Spinen\ConnectWise\Library\Results;

use Spinen\ConnectWise\Library\Results\Collection;
use Tests\Spinen\ConnectWise\BaseTest;


/**
 * Class CollectionTest
 *
 * @package Tests\Spinen\ConnectWise\Library\Support
 * @group   library
 * @group   results
 * @group   collections
 */
class CollectionTest extends BaseTest
{

    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp()
    {
        parent::setUp();

        $this->collection = new Collection();
    }

    /**
     * @test
     */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf('Spinen\\ConnectWise\\Library\\Results\\Collection', $this->collection);
    }

}
