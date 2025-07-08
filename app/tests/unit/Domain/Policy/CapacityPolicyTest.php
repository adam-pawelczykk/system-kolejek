<?php
/** @author: Adam Pawełczyk */

namespace tests\unit\Domain\Policy;

use App\Domain\Coaster;
use App\Domain\Policy\CapacityPolicy;
use App\Domain\ValueObject\CapacityStatus;
use App\Domain\Wagon;
use PHPUnit\Framework\TestCase;

class CapacityPolicyTest extends TestCase
{
    private CapacityPolicy $SUT;

    protected function setUp(): void
    {
        $this->SUT = new CapacityPolicy();
    }

    public function testCapacityIsSufficientWhenWagonsCanHandleAllClients(): void
    {
        // Given
        $coaster = new Coaster(
            id: 'C1',
            personnel: 10, // nieistotne tu
            clientsPerDay: 600,
            trackLength: 1200,
            hourFrom: '08:00',
            hourTo: '16:00'
        );

        // Dodaj 2 wagony o sensownych parametrach
        $coaster->addWagon(new Wagon('W1', 32, 1.2));
        $coaster->addWagon(new Wagon('W2', 32, 1.2));

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertInstanceOf(CapacityStatus::class, $status);
        $this->assertTrue($status->sufficient);
        $this->assertEquals(600, $status->expectedClients);
        $this->assertGreaterThanOrEqual(600, $status->maxPossibleClients);
        $this->assertEquals(0, $status->missingClients);
    }

    public function testCapacityIsInsufficientWhenTooFewWagons(): void
    {
        // Given
        $coaster = new Coaster(
            id: 'C2',
            personnel: 5,
            clientsPerDay: 1000,
            trackLength: 1500,
            hourFrom: '09:00',
            hourTo: '17:00'
        );

        $coaster->addWagon(new Wagon('W1', 32, 1.2)); // tylko jeden wagon

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertFalse($status->sufficient);
        $this->assertEquals(1000, $status->expectedClients);
        $this->assertLessThan(1000, $status->maxPossibleClients);
        $this->assertGreaterThan(0, $status->missingClients);
    }

    public function testCapacityIsZeroWhenNoWagons(): void
    {
        // Given
        $coaster = new Coaster(
            id: 'C3',
            personnel: 3,
            clientsPerDay: 300,
            trackLength: 1000,
            hourFrom: '08:00',
            hourTo: '16:00',
            wagons: []
        );

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertFalse($status->sufficient);
        $this->assertEquals(0, $status->maxPossibleClients);
        $this->assertEquals(300, $status->missingClients);
        $this->assertEquals(0, $status->excessClients);
    }

    public function testCapacityHasExcessWhenMoreThanDouble(): void
    {
        // Given
        $coaster = new Coaster(
            id: 'C4',
            personnel: 10,
            clientsPerDay: 300,
            trackLength: 1000,
            hourFrom: '08:00',
            hourTo: '16:00'
        );

        $coaster->addWagon(new Wagon('W1', 32, 1.2));
        $coaster->addWagon(new Wagon('W2', 32, 1.2));
        $coaster->addWagon(new Wagon('W3', 32, 1.2));

        // When
        $status = $this->SUT->evaluate($coaster);

        // Then
        $this->assertTrue($status->sufficient);
        $this->assertGreaterThan(300, $status->maxPossibleClients);
        $this->assertGreaterThan(0, $status->excessClients);
        $this->assertEquals(0, $status->missingClients);
    }
}
