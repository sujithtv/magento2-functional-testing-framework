<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tests\unit\Magento\FunctionalTestFramework\Test\Handlers;

use AspectMock\Test as AspectMock;

use Magento\FunctionalTestingFramework\Test\Objects\ActionObject;
use Magento\FunctionalTestingFramework\Test\Objects\TestObject;
use Magento\FunctionalTestingFramework\Util\MagentoTestCase;
use Magento\FunctionalTestingFramework\Util\TestGenerator;

class TestGeneratorTest extends MagentoTestCase
{
    /**
     * Basic test to validate array => test object conversion.
     *
     * @throws \Exception
     */
    public function testGetTestObject()
    {
        $actionObject = new ActionObject('fakeAction', 'comment', [
            'userInput' => '{{someEntity.entity}}'
        ]);

        $testObject = new TestObject("sampleTest", ["merge123" => $actionObject], [], [], "filename");

        $testGeneratorObject = TestGenerator::getInstance("", ["sampleTest" => $testObject]);

        AspectMock::double(TestGenerator::class, ['loadAllTestObjects' => ["sampleTest" => $testObject]]);

        $this->expectExceptionMessage("Could not resolve entity reference \"{{someEntity.entity}}\" " .
            "in Action with stepKey \"fakeAction\".\n" .
            "Exception occurred parsing action at StepKey \"fakeAction\" in Test \"sampleTest\"");

        $testGeneratorObject->createAllTestFiles(null, []);
    }
}