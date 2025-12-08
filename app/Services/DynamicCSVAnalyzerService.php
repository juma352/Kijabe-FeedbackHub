<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DynamicCSVAnalyzerService
{
    /**
     * Analyze CSV structure and detect column types
     */
    public function analyzeCSV($filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("CSV file not found at path: {$filePath}");
        }

        if (!is_readable($filePath)) {
            throw new \Exception("CSV file is not readable: {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            throw new \Exception("Failed to open CSV file: {$filePath}");
        }
        
        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new \Exception('Could not read CSV headers - file may be empty or corrupted');
        }
        
        if (empty(array_filter($headers))) {
            fclose($handle);
            throw new \Exception('CSV headers are empty - please check your file format');
        }

        // Read sample rows for analysis
        $sampleRows = [];
        $rowCount = 0;
        while (($row = fgetcsv($handle)) !== false && $rowCount < 10) {
            $sampleRows[] = $row;
            $rowCount++;
        }
        
        fclose($handle);

        // Analyze columns
        $columnAnalysis = $this->analyzeColumns($headers, $sampleRows);
        
        // Detect likely feedback structure
        $suggestions = $this->suggestColumnMappings($columnAnalysis);
        
        return [
            'headers' => $headers,
            'sample_rows' => array_slice($sampleRows, 0, 3), // Show first 3 rows as preview
            'column_analysis' => $columnAnalysis,
            'suggested_mappings' => $suggestions,
            'total_columns' => count($headers),
            'estimated_rows' => $this->countTotalRows($filePath)
        ];
    }

    /**
     * Analyze each column to determine its likely content type
     */
    private function analyzeColumns(array $headers, array $sampleRows): array
    {
        $analysis = [];
        
        foreach ($headers as $index => $header) {
            $analysis[$index] = [
                'header' => $header,
                'detected_type' => $this->detectColumnType($header, $index, $sampleRows),
                'sample_values' => $this->getSampleValues($index, $sampleRows),
                'patterns' => $this->analyzeDataPatterns($index, $sampleRows)
            ];
        }
        
        return $analysis;
    }

    /**
     * Detect what type of data a column likely contains
     */
    private function detectColumnType(string $header, int $index, array $sampleRows): array
    {
        $headerLower = strtolower($header);
        $types = [];

        // Feedback content detection
        if (preg_match('/feedback|comment|response|message|review|suggestion|opinion|remark|note|description/i', $header)) {
            $types['feedback_content'] = 90;
        }

        // Rating/score detection  
        if (preg_match('/rating|score|rate|satisfaction|level|scale|star|point/i', $header)) {
            $types['rating'] = 85;
        }

        // Date detection
        if (preg_match('/date|time|timestamp|created|submitted|when/i', $header)) {
            $types['date'] = 80;
        }

        // Department/category detection
        if (preg_match('/department|category|section|division|unit|area|service|ward/i', $header)) {
            $types['department'] = 75;
        }

        // Name/identifier detection
        if (preg_match('/name|user|person|client|patient|student|respondent|id/i', $header)) {
            $types['identifier'] = 70;
        }

        // Email detection
        if (preg_match('/email|mail|contact/i', $header)) {
            $types['email'] = 85;
        }

        // Phone detection
        if (preg_match('/phone|mobile|tel|contact/i', $header)) {
            $types['phone'] = 80;
        }

        // Analyze actual data patterns
        $dataPatterns = $this->analyzeDataPatterns($index, $sampleRows);
        
        // Boost confidence based on data content
        if (isset($dataPatterns['looks_like_text']) && $dataPatterns['looks_like_text'] && !empty($types['feedback_content'])) {
            $types['feedback_content'] += 10;
        }

        if (isset($dataPatterns['looks_like_number']) && $dataPatterns['looks_like_number']) {
            if (isset($types['rating'])) $types['rating'] += 10;
        }

        if (isset($dataPatterns['looks_like_date']) && $dataPatterns['looks_like_date']) {
            if (isset($types['date'])) $types['date'] += 15;
        }

        if (isset($dataPatterns['looks_like_email']) && $dataPatterns['looks_like_email']) {
            if (isset($types['email'])) $types['email'] += 15;
        }

        // If no specific type detected, mark as generic
        if (empty($types)) {
            $types['generic'] = 30;
        }

        // Sort by confidence
        arsort($types);

        return $types;
    }

    /**
     * Analyze patterns in the actual data
     */
    private function analyzeDataPatterns(int $columnIndex, array $sampleRows): array
    {
        $patterns = [];
        $values = [];

        foreach ($sampleRows as $row) {
            if (isset($row[$columnIndex])) {
                $values[] = $row[$columnIndex];
            }
        }

        if (empty($values)) {
            return ['empty' => true];
        }

        // Check if values look like text (sentences, paragraphs)
        $textLikeCount = 0;
        $numberLikeCount = 0;
        $dateLikeCount = 0;
        $emailLikeCount = 0;

        foreach ($values as $value) {
            $value = trim($value);
            
            // Skip empty values
            if (empty($value)) continue;

            // Text detection (multiple words, sentences)
            if (str_word_count($value) > 3 || strlen($value) > 50) {
                $textLikeCount++;
            }

            // Number detection
            if (is_numeric($value) || preg_match('/^\d+\.?\d*$/', $value)) {
                $numberLikeCount++;
            }

            // Date detection
            if (strtotime($value) !== false && preg_match('/\d{4}|\d{2}\/\d{2}|\d{2}-\d{2}/', $value)) {
                $dateLikeCount++;
            }

            // Email detection
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $emailLikeCount++;
            }
        }

        $totalValues = count(array_filter($values, fn($v) => !empty(trim($v))));
        
        if ($totalValues > 0) {
            $patterns['looks_like_text'] = ($textLikeCount / $totalValues) > 0.5;
            $patterns['looks_like_number'] = ($numberLikeCount / $totalValues) > 0.5;
            $patterns['looks_like_date'] = ($dateLikeCount / $totalValues) > 0.3;
            $patterns['looks_like_email'] = ($emailLikeCount / $totalValues) > 0.3;
        }

        $patterns['average_length'] = $totalValues > 0 ? array_sum(array_map('strlen', array_filter($values))) / $totalValues : 0;
        $patterns['unique_values'] = count(array_unique(array_filter($values)));

        return $patterns;
    }

    /**
     * Get sample values from a column
     */
    private function getSampleValues(int $columnIndex, array $sampleRows): array
    {
        $values = [];
        foreach ($sampleRows as $row) {
            if (isset($row[$columnIndex]) && !empty(trim($row[$columnIndex]))) {
                $values[] = trim($row[$columnIndex]);
                if (count($values) >= 3) break; // Only show 3 samples
            }
        }
        return $values;
    }

    /**
     * Suggest column mappings based on analysis
     */
    private function suggestColumnMappings(array $columnAnalysis): array
    {
        $mappings = [
            'feedback_content' => null,
            'rating' => null,
            'date' => null,
            'department' => null,
            'identifier' => null,
            'email' => null,
            'phone' => null,
            'additional_fields' => []
        ];

        foreach ($columnAnalysis as $index => $analysis) {
            $topType = array_key_first($analysis['detected_type']);
            $confidence = $analysis['detected_type'][$topType] ?? 0;

            // Only suggest if confidence is high enough
            if ($confidence >= 60) {
                switch ($topType) {
                    case 'feedback_content':
                        if (!$mappings['feedback_content'] || $confidence > 80) {
                            $mappings['feedback_content'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                    
                    case 'rating':
                        if (!$mappings['rating']) {
                            $mappings['rating'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    case 'date':
                        if (!$mappings['date']) {
                            $mappings['date'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    case 'department':
                        if (!$mappings['department']) {
                            $mappings['department'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    case 'identifier':
                        if (!$mappings['identifier']) {
                            $mappings['identifier'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    case 'email':
                        if (!$mappings['email']) {
                            $mappings['email'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    case 'phone':
                        if (!$mappings['phone']) {
                            $mappings['phone'] = [
                                'column_index' => $index,
                                'column_name' => $analysis['header'],
                                'confidence' => $confidence
                            ];
                        }
                        break;
                        
                    default:
                        $mappings['additional_fields'][] = [
                            'column_index' => $index,
                            'column_name' => $analysis['header'],
                            'detected_type' => $topType,
                            'confidence' => $confidence
                        ];
                        break;
                }
            } else {
                // Low confidence - add as additional field
                $mappings['additional_fields'][] = [
                    'column_index' => $index,
                    'column_name' => $analysis['header'],
                    'detected_type' => $topType,
                    'confidence' => $confidence
                ];
            }
        }

        return $mappings;
    }

    /**
     * Count total rows in CSV file
     */
    private function countTotalRows(string $filePath): int
    {
        $lineCount = 0;
        $handle = fopen($filePath, 'r');
        
        while (fgets($handle) !== false) {
            $lineCount++;
        }
        
        fclose($handle);
        
        // Subtract 1 for header row
        return max(0, $lineCount - 1);
    }

    /**
     * Process CSV with custom column mappings
     */
    public function processWithMappings(string $filePath, array $mappings): array
    {
        $handle = fopen($filePath, 'r');
        
        // Skip headers
        $headers = fgetcsv($handle);
        
        $processedData = [];
        $rowCount = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            $processedRow = $this->mapRowToFeedback($row, $mappings);
            if ($processedRow) {
                $processedData[] = $processedRow;
                $rowCount++;
            }
        }
        
        fclose($handle);
        
        return [
            'success' => true,
            'count' => $rowCount,
            'data' => $processedData
        ];
    }

    /**
     * Map a CSV row to feedback structure using custom mappings
     */
    private function mapRowToFeedback(array $row, array $mappings): ?array
    {
        $feedback = [];
        
        // Map required feedback content
        if (isset($mappings['feedback_content']) && isset($row[$mappings['feedback_content']])) {
            $feedback['content'] = trim($row[$mappings['feedback_content']]);
        } else {
            return null; // Skip rows without feedback content
        }
        
        // Map optional fields
        if (isset($mappings['rating']) && isset($row[$mappings['rating']])) {
            $feedback['rating'] = $this->parseRating($row[$mappings['rating']]);
        }
        
        if (isset($mappings['date']) && isset($row[$mappings['date']])) {
            $feedback['date'] = $this->parseDate($row[$mappings['date']]);
        }
        
        if (isset($mappings['department']) && isset($row[$mappings['department']])) {
            $feedback['department'] = trim($row[$mappings['department']]);
        }
        
        // Map additional fields
        $additionalData = [];
        if (isset($mappings['additional_fields'])) {
            foreach ($mappings['additional_fields'] as $field) {
                if (isset($row[$field['column_index']])) {
                    $additionalData[$field['column_name']] = trim($row[$field['column_index']]);
                }
            }
        }
        
        if (!empty($additionalData)) {
            $feedback['additional_data'] = $additionalData;
        }
        
        return $feedback;
    }

    /**
     * Parse rating from various formats
     */
    private function parseRating($value): ?float
    {
        $value = trim($value);
        
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        // Extract number from text like "4 stars", "Rating: 3.5"
        if (preg_match('/(\d+\.?\d*)/', $value, $matches)) {
            return (float) $matches[1];
        }
        
        return null;
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value): ?string
    {
        $value = trim($value);
        
        if (empty($value)) {
            return null;
        }
        
        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }
        
        return null;
    }
}