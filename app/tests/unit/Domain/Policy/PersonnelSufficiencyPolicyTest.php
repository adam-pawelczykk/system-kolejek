<?php

/** @author: Adam Pawełczyk */

namespace tests\unit\Domain\Policy;

use App\Domain\Coaster;
use App\Domain\Policy\PersonnelSufficiencyPolicy;
use App\Domain\ValueObject\PersonnelStatus;
use App\Domain\Wagon;
use PHPUnit\Framework\TestCase;

class PersonnelSufficiencyPolicyTest extends TestCase
{
    private PersonnelSufficiencyPolicy $SUT;

    protected function setUp(): void
    {
        $this->SUT = new PersonnelSufficiencyPolicy();
    }

    public function testPersonnelIsSufficientWhenRequiredEqualsAvailable(): void
    {
        // Given
        $coaster = new Coaster(
            'A1',
            11,
            600,
            800,
            '10:00',
            '18:00',
        );

        for ($i = 1; $i <= 5; $i++) {
            $coaster->addWagon(new Wagon("W$i", 32, 1.2));
        }

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertInstanceOf(PersonnelStatus::class, $status);
        $this->assertTrue($status->sufficient);
        $this->assertEquals(0, $status->missing);
        $this->assertEquals(0, $status->excess);
    }

    public function testPersonnelIsInsufficientWhenMissingPersonnelDetected(): void
    {
        // Given
        $coaster = new Coaster(
            'A2',
            5,
            600,
            600,
            '09:00',
            '17:00'
        );

        for ($i = 1; $i <= 3; $i++) {
            $coaster->addWagon(new Wagon("W$i", 32, 1.2));
        }

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertFalse($status->sufficient);
        $this->assertEquals(2, $status->missing);
        $this->assertEquals(0, $status->excess);
    }

    public function testPersonnelIsExcessiveWhenMoreThanRequired(): void
    {
        // Given
        $coaster = new Coaster(
            'A3',
            10,
            200,
            500,
            '08:00',
            '16:00'
        );

        for ($i = 1; $i <= 2; $i++) {
            $coaster->addWagon(new Wagon("W$i", 32, 1.2));
        }

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertTrue($status->sufficient);
        $this->assertEquals(0, $status->missing);
        $this->assertEquals(5, $status->excess);
    }
}
