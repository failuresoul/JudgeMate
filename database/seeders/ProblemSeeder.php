<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setter = \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'ProblemSetter'))->first();

        if (!$setter) {
            $setter = \App\Models\User::firstOrCreate(
                ['email' => 'judge@judgemate.test'],
                [
                    'name'     => 'Sample Judge',
                    'username' => 'judge1',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'status'   => 'approved',
                ]
            );
            $setter->assignRole('ProblemSetter');
        }

        $tagsData = [
            ['name' => 'Math', 'slug' => 'math'],
            ['name' => 'Dynamic Programming', 'slug' => 'dynamic-programming'],
            ['name' => 'Greedy', 'slug' => 'greedy'],
            ['name' => 'Graphs', 'slug' => 'graphs'],
            ['name' => 'Strings', 'slug' => 'strings'],
        ];

        $tags = [];
        foreach ($tagsData as $tData) {
            $tags[$tData['slug']] = \App\Models\Tag::firstOrCreate(
                ['slug' => $tData['slug']],
                ['name' => $tData['name']]
            );
        }

        $problemsData = [
            [
                'title' => 'Two Sum',
                'slug' => 'two-sum',
                'statement' => "Given an array of integers `nums` and an integer `target`, return indices of the two numbers such that they add up to `target`.\n\nYou may assume that each input would have exactly one solution, and you may not use the same element twice.",
                'input_format' => "The first line contains N (the number of elements) and target.\nThe second line contains N space-separated integers representing the array.",
                'output_format' => 'Print the indices of the two numbers space-separated in ascending order.',
                'constraints' => "2 <= N <= 10^4\n-10^9 <= nums[i] <= 10^9\n-10^9 <= target <= 10^9",
                'difficulty' => 'easy',
                'is_published' => true,
                'tags' => ['math'],
                'test_cases' => [
                    ['input' => "4 9\n2 7 11 15", 'expected_output' => '0 1', 'is_hidden' => false],
                    ['input' => "3 6\n3 2 4", 'expected_output' => '1 2', 'is_hidden' => true],
                ],
            ],
            [
                'title' => 'Palindrome Number',
                'slug' => 'palindrome-number',
                'statement' => "Given an integer `x`, return `true` if `x` is a palindrome, and `false` otherwise.\n\nAn integer is a palindrome when it reads the same backward as forward.",
                'input_format' => 'A single line containing the integer x.',
                'output_format' => 'Print "true" if x is a palindrome, otherwise print "false".',
                'constraints' => '-2^31 <= x <= 2^31 - 1',
                'difficulty' => 'easy',
                'is_published' => true,
                'tags' => ['math', 'strings'],
                'test_cases' => [
                    ['input' => '121', 'expected_output' => 'true', 'is_hidden' => false],
                    ['input' => '-121', 'expected_output' => 'false', 'is_hidden' => true],
                ],
            ],
            [
                'title' => 'Longest Substring Without Repeating Characters',
                'slug' => 'longest-substring-without-repeating-characters',
                'statement' => 'Given a string `s`, find the length of the longest substring without repeating characters.',
                'input_format' => 'A single line containing the string s.',
                'output_format' => 'Print the length of the longest substring.',
                'constraints' => "0 <= s.length <= 5 * 10^4\ns consists of English letters, digits, symbols and spaces.",
                'difficulty' => 'medium',
                'is_published' => true,
                'tags' => ['strings'],
                'test_cases' => [
                    ['input' => 'abcabcbb', 'expected_output' => '3', 'is_hidden' => false],
                    ['input' => 'bbbbb', 'expected_output' => '1', 'is_hidden' => true],
                ],
            ],
            [
                'title' => 'Container With Most Water',
                'slug' => 'container-with-most-water',
                'statement' => "You are given an integer array `height` of length `n`. There are `n` vertical lines drawn such that the two endpoints of the `i-th` line are `(i, 0)` and `(i, height[i])`.\n\nFind two lines that together with the x-axis form a container, such that the container contains the most water.",
                'input_format' => "The first line contains n.\nThe second line contains n space-separated integers representing the array height.",
                'output_format' => 'Print the maximum area of water.',
                'constraints' => "2 <= n <= 10^5\n0 <= height[i] <= 10^4",
                'difficulty' => 'medium',
                'is_published' => true,
                'tags' => ['greedy'],
                'test_cases' => [
                    ['input' => "9\n1 8 6 2 5 4 8 3 7", 'expected_output' => '49', 'is_hidden' => false],
                    ['input' => "2\n1 1", 'expected_output' => '1', 'is_hidden' => true],
                ],
            ],
            [
                'title' => 'Median of Two Sorted Arrays',
                'slug' => 'median-of-two-sorted-arrays',
                'statement' => 'Given two sorted arrays `nums1` and `nums2` of size `m` and `n` respectively, return the median of the two sorted arrays.',
                'input_format' => "The first line contains m and n.\nThe second line contains m space-separated integers.\nThe third line contains n space-separated integers.",
                'output_format' => 'Print the median as a float formatted to 5 decimal places.',
                'constraints' => "0 <= m, n <= 1000\n1 <= m + n",
                'difficulty' => 'hard',
                'is_published' => true,
                'tags' => ['math'],
                'test_cases' => [
                    ['input' => "2 1\n1 3\n2", 'expected_output' => '2.00000', 'is_hidden' => false],
                    ['input' => "2 2\n1 2\n3 4", 'expected_output' => '2.50000', 'is_hidden' => true],
                ],
            ],
        ];

        foreach ($problemsData as $data) {
            $testCases = $data['test_cases'];
            $tagSlugs = $data['tags'] ?? [];
            unset($data['test_cases'], $data['tags']);

            $data['created_by'] = $setter->id;
            $problem = \App\Models\Problem::create($data);

            // Sync tags
            $tagIds = [];
            foreach ($tagSlugs as $slug) {
                if (isset($tags[$slug])) {
                    $tagIds[] = $tags[$slug]->id;
                }
            }
            $problem->tags()->sync($tagIds);

            foreach ($testCases as $tc) {
                $tc['problem_id'] = $problem->id;
                \App\Models\TestCase::create($tc);
            }
        }
    }
}
