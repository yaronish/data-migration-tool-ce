<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Migration\Handler;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProviderMaps()
    {
        return [
            [
                'map' => '[one_column:1column;two_columns_left:2columns-left]',
                'initialValue' => 'one_column',
                'processedValue' => '1column'
            ],
            [
                'map' => '[one_column:1column;two_columns_left:2columns-left]',
                'initialValue' => 'dummy_value',
                'processedValue' => 'dummy_value'
            ]
        ];
    }

    /**
     * @param string $map
     * @param mixed $initialValue
     * @param mixed $processedValue
     * @dataProvider dataProviderMaps
     * @return void
     */
    public function testHandle($map, $initialValue, $processedValue)
    {
        $fieldName = 'fieldname';
        /** @var \Migration\ResourceModel\Record|\PHPUnit_Framework_MockObject_MockObject $record */
        $record = $this->getMock(
            'Migration\ResourceModel\Record',
            ['setValue', 'getValue', 'getFields'],
            [],
            '',
            false
        );
        $record->expects($this->once())->method('getValue')->will($this->returnValue($initialValue));
        $record->expects($this->once())->method('setValue')->with($fieldName, $processedValue);
        $record->expects($this->any())->method('getFields')->will($this->returnValue([$fieldName]));

        $record2 = $this->getMockBuilder('Migration\ResourceModel\Record')->disableOriginalConstructor()->getMock();

        $handler = new Convert($map);
        $handler->setField($fieldName);
        $handler->handle($record, $record2);
    }

    /**
     * @return void
     */
    public function testInvalidMap()
    {
        $this->setExpectedException('Exception');
        $handler = new Convert('[dummy]');
        $record = $this->getMock('Migration\ResourceModel\Record', [], [], '', false);
        $record2 = $this->getMockBuilder('Migration\ResourceModel\Record')->disableOriginalConstructor()->getMock();
        $handler->handle($record, $record2, 'dummy');
    }
}
