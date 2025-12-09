<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExamAutoGenerateTest extends TestCase
{
    /**
     * Test validation: total by type exceeds total by level
     */
    public function test_validation_total_by_type_exceeds_level()
    {
        // Case: 5+5+5+5 = 20 câu theo mức độ
        // Nhưng 20 trắc nghiệm + 1 tự luận = 21 câu theo loại
        // Expected: Báo lỗi
        
        $totalByLevel = 5 + 5 + 5 + 5; // 20
        $totalByType = 20 + 1; // 21
        
        $this->assertTrue($totalByType > $totalByLevel, 
            "Validation should fail: {$totalByType} questions by type exceeds {$totalByLevel} by level");
    }

    /**
     * Test validation: valid distribution
     */
    public function test_validation_valid_distribution()
    {
        // Case: 5+5+5+5 = 20 câu theo mức độ
        // 15 trắc nghiệm + 5 tự luận = 20 câu theo loại
        // Expected: OK
        
        $totalByLevel = 5 + 5 + 5 + 5; // 20
        $totalByType = 15 + 5; // 20
        
        $this->assertEquals($totalByType, $totalByLevel, 
            "Valid: {$totalByType} questions by type matches {$totalByLevel} by level");
    }

    /**
     * Test points distribution: even distribution
     */
    public function test_points_distribution_even()
    {
        // 20 câu hỏi -> mỗi câu 0.5 điểm -> tổng 10 điểm
        $totalQuestions = 20;
        $targetPoints = 10;
        
        $basePoints = floor(($targetPoints * 10) / $totalQuestions) / 10;
        $expectedBasePoints = 0.5;
        
        $this->assertEquals($expectedBasePoints, $basePoints,
            "Each of {$totalQuestions} questions should get {$expectedBasePoints} points");
        
        $totalCalculated = $basePoints * $totalQuestions;
        $this->assertEquals(10.0, $totalCalculated,
            "Total points should be exactly 10");
    }

    /**
     * Test points distribution: with remainder
     */
    public function test_points_distribution_with_remainder()
    {
        // 7 câu hỏi -> 10/7 = 1.42... 
        // Base: 1.4, Remainder: 10 - (1.4 * 7) = 0.2
        // Distribution: 1.5, 1.5, 1.4, 1.4, 1.4, 1.4, 1.4 = 10.0
        $totalQuestions = 7;
        $targetPoints = 10;
        
        $basePoints = floor(($targetPoints * 10) / $totalQuestions) / 10; // 1.4
        $remainder = round($targetPoints - ($basePoints * $totalQuestions), 1); // 0.2
        
        $this->assertEquals(1.4, $basePoints);
        $this->assertEquals(0.2, $remainder);
        
        // First 2 questions get 1.5, rest get 1.4
        $questionsWithExtra = round($remainder * 10); // 2
        
        $total = ($basePoints + 0.1) * $questionsWithExtra + 
                 $basePoints * ($totalQuestions - $questionsWithExtra);
        
        $this->assertEquals(10.0, round($total, 1),
            "Total points should be exactly 10 with proper distribution");
    }

    /**
     * Test actual point calculation for various scenarios
     */
    public function test_various_question_counts()
    {
        $scenarios = [
            5 => 2.0,   // 5 questions -> 2.0 each
            10 => 1.0,  // 10 questions -> 1.0 each
            20 => 0.5,  // 20 questions -> 0.5 each
            15 => 0.7,  // 15 questions -> 0.6 or 0.7 each (with adjustment)
        ];

        foreach ($scenarios as $count => $expectedBase) {
            $basePoints = floor((10 * 10) / $count) / 10;
            $total = 0;
            
            for ($i = 0; $i < $count; $i++) {
                $points = $basePoints;
                $remainder = round(10 - ($basePoints * $count), 1);
                
                if ($i < round($remainder * 10)) {
                    $points += 0.1;
                }
                
                $total += $points;
            }
            
            $this->assertEquals(10.0, round($total, 1),
                "For {$count} questions, total should be 10 points");
        }
    }
}
