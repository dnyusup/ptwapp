<?php

namespace Tests\Unit;

use App\Models\MethodStatement;
use PHPUnit\Framework\TestCase;

class MethodStatementConfirmationProgressTest extends TestCase
{
    public function test_it_counts_free_text_and_registered_responsible_people(): void
    {
        $methodStatement = new MethodStatement([
            'responsible_persons' => [
                ['name' => 'Registered Person', 'email' => 'registered@example.com'],
                ['name' => 'Free Text Person', 'email' => null],
                ['name' => 'Another Person', 'email' => null],
            ],
            'responsible_person_confirmations' => [
                'person:0' => ['signature' => '<svg></svg>'],
                'person:1' => ['signature' => '<svg></svg>'],
            ],
        ]);

        $this->assertSame(
            ['confirmed' => 2, 'total' => 3],
            $methodStatement->responsiblePersonConfirmationCounts()
        );
    }

    public function test_it_counts_existing_email_keyed_confirmations(): void
    {
        $methodStatement = new MethodStatement([
            'responsible_persons' => [
                ['name' => 'Registered Person', 'email' => 'Registered@Example.com'],
                ['name' => 'Free Text Person', 'email' => null],
            ],
            'responsible_person_confirmations' => [
                'registered@example.com' => ['signature' => '<svg></svg>'],
            ],
        ]);

        $this->assertSame(
            ['confirmed' => 1, 'total' => 2],
            $methodStatement->responsiblePersonConfirmationCounts()
        );
    }
}
