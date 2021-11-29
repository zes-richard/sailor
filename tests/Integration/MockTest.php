<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Tests\Integration;

use DateTime;
use Spawnia\PHPUnitAssertFiles\AssertDirectory;
use Spawnia\Sailor\Configuration;
use Spawnia\Sailor\EndpointConfig;
use Spawnia\Sailor\Simple\Fragments;
use Spawnia\Sailor\Simple\Input\InputArg;
use Spawnia\Sailor\Simple\Input\SplitArg;
use Spawnia\Sailor\Simple\InputArgs;
use Spawnia\Sailor\Simple\MyObjectArrayQuery;
use Spawnia\Sailor\Simple\MyObjectQuery\MyObjectQueryResult;
use Spawnia\Sailor\Tests\TestCase;

class MockTest extends TestCase
{
    use AssertDirectory;

    const EXAMPLES_PATH = __DIR__ . '/../../examples/simple/';

    public function testGeneratesFooExample(): void
    {
        $endpoint = self::fooEndpoint();

        Configuration::setEndpoint('simple', $endpoint);

        // dump(Fragments::execute());
        dump(MyObjectArrayQuery::execute([], []));
        $inputArg = new InputArg();
        $inputArg->setInteger(12);
        $inputArg->setSomeID('asds');
        $nested = new SplitArg();
        $nested->setCreated(new DateTime());
        $nested->setString('asd');
        $inputArg->setNested($nested);
        dump(InputArgs::execute($inputArg, 'something', new DateTime()));
    }

    protected static function fooEndpoint(): EndpointConfig
    {
        $fooConfig = include __DIR__ . '/../../examples/simple/sailormock.php';

        return $fooConfig['simple'];
    }
}
