<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Problem;
use App\Models\TestCase;
use App\Models\Contest;
use App\Models\Submission;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MockDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Judges
        $judgeNames = ['rakin', 'wajih', 'farhad', 'shadik', 'sadid'];
        $judges = [];
        foreach ($judgeNames as $name) {
            $judge = User::firstOrCreate([
                'email' => strtolower($name) . "@judgemate.test"
            ], [
                'name' => ucfirst($name),
                'username' => strtolower($name),
                'password' => Hash::make('password'),
                'status' => 'approved',
            ]);
            $judge->assignRole('ProblemSetter');
            $judges[] = $judge;
        }

        // 2. Create Contestants
        $contestantNames = ['toki', 'alif1', 'masum', 'ruhan', 'torikul', 'siyam', 'alok', 'sazzad', 'alif2', 'saikat'];
        $contestants = [];
        foreach ($contestantNames as $name) {
            $contestant = User::firstOrCreate([
                'email' => strtolower($name) . "@judgemate.test"
            ], [
                'name' => ucfirst(preg_replace('/[0-9]+/', '', $name)), // Remove numbers for display name
                'username' => strtolower($name),
                'password' => Hash::make('password'),
                'status' => 'approved',
            ]);
            $contestant->assignRole('Contestant');
            $contestants[] = $contestant;
        }

        // 3. Create Problems for Judges
        $realProblems = [
            [
                'title' => 'Two Sum',
                'statement' => 'Given an array of integers `nums` and an integer `target`, return indices of the two numbers such that they add up to `target`.\nYou may assume that each input would have exactly one solution, and you may not use the same element twice.\nYou can return the answer in any order.',
                'input_format' => "The first line contains two integers `n` (the size of the array) and `target`.\nThe second line contains `n` space-separated integers representing the array `nums`.",
                'output_format' => "Print two space-separated integers representing the indices (0-indexed).",
                'constraints' => "- 2 <= nums.length <= 10^4\n- -10^9 <= nums[i] <= 10^9\n- -10^9 <= target <= 10^9\n- Only one valid answer exists.",
                'difficulty' => 'easy',
                'sample_in' => "4 9\n2 7 11 15",
                'sample_out' => "0 1"
            ],
            [
                'title' => 'Merge Two Sorted Lists',
                'statement' => 'You are given the heads of two sorted linked lists `list1` and `list2`.\nMerge the two lists into one sorted list. The list should be made by splicing together the nodes of the first two lists.\nReturn the head of the merged linked list.',
                'input_format' => "The first line contains two integers `N` and `M`, the sizes of the lists.\nThe second line contains `N` integers for `list1`.\nThe third line contains `M` integers for `list2`.",
                'output_format' => "Print the merged sorted list as space-separated integers.",
                'constraints' => "- The number of nodes in both lists is in the range [0, 50].\n- -100 <= Node.val <= 100\n- Both `list1` and `list2` are sorted in non-decreasing order.",
                'difficulty' => 'easy',
                'sample_in' => "3 3\n1 2 4\n1 3 4",
                'sample_out' => "1 1 2 3 4 4"
            ],
            [
                'title' => 'Longest Palindromic Substring',
                'statement' => 'Given a string `s`, return the longest palindromic substring in `s`.\nA string is palindromic if it reads the same forward and backward.',
                'input_format' => "The first and only line contains a single string `s`.",
                'output_format' => "Print the longest palindromic substring. If there are multiple, print any of them.",
                'constraints' => "- 1 <= s.length <= 1000\n- `s` consist of only digits and English letters.",
                'difficulty' => 'medium',
                'sample_in' => "babad",
                'sample_out' => "bab"
            ],
            [
                'title' => 'Valid Parentheses',
                'statement' => "Given a string `s` containing just the characters '(', ')', '{', '}', '[' and ']', determine if the input string is valid.\nAn input string is valid if:\n1. Open brackets must be closed by the same type of brackets.\n2. Open brackets must be closed in the correct order.\n3. Every close bracket has a corresponding open bracket of the same type.",
                'input_format' => "The first line contains a single string `s`.",
                'output_format' => "Print `true` if the string is valid, otherwise print `false`.",
                'constraints' => "- 1 <= s.length <= 10^4\n- `s` consists of parentheses only '()[]{}'.",
                'difficulty' => 'easy',
                'sample_in' => "()[]{}",
                'sample_out' => "true"
            ],
            [
                'title' => 'Merge k Sorted Lists',
                'statement' => 'You are given an array of `k` linked-lists `lists`, each linked-list is sorted in ascending order.\nMerge all the linked-lists into one sorted linked-list and return it.',
                'input_format' => "The first line contains an integer `k`.\nThen `k` pairs of lines follow. For each pair:\n- The first line contains an integer `N_i` (the size of the i-th list).\n- The second line contains `N_i` space-separated integers.",
                'output_format' => "Print the merged sorted list as space-separated integers.",
                'constraints' => "- k == lists.length\n- 0 <= k <= 10^4\n- 0 <= lists[i].length <= 50\n- -10^4 <= lists[i][j] <= 10^4",
                'difficulty' => 'hard',
                'sample_in' => "3\n3\n1 4 5\n3\n1 3 4\n2\n2 6",
                'sample_out' => "1 1 2 3 4 4 5 6"
            ],
            [
                'title' => 'Container With Most Water',
                'statement' => 'You are given an integer array `height` of length `n`. There are `n` vertical lines drawn such that the two endpoints of the `i`th line are `(i, 0)` and `(i, height[i])`.\nFind two lines that together with the x-axis form a container, such that the container contains the most water.\nReturn the maximum amount of water a container can store.',
                'input_format' => "The first line contains an integer `n`.\nThe second line contains `n` space-separated integers representing the `height` array.",
                'output_format' => "Print a single integer representing the maximum area of water.",
                'constraints' => "- n == height.length\n- 2 <= n <= 10^5\n- 0 <= height[i] <= 10^4",
                'difficulty' => 'medium',
                'sample_in' => "9\n1 8 6 2 5 4 8 3 7",
                'sample_out' => "49"
            ],
            [
                'title' => 'Trapping Rain Water',
                'statement' => 'Given `n` non-negative integers representing an elevation map where the width of each bar is 1, compute how much water it can trap after raining.',
                'input_format' => "The first line contains an integer `n`.\nThe second line contains `n` space-separated non-negative integers representing the elevation map.",
                'output_format' => "Print a single integer representing the total trapped water.",
                'constraints' => "- n == height.length\n- 1 <= n <= 2 * 10^4\n- 0 <= height[i] <= 10^5",
                'difficulty' => 'hard',
                'sample_in' => "12\n0 1 0 2 1 0 1 3 2 1 2 1",
                'sample_out' => "6"
            ],
            [
                'title' => 'Climbing Stairs',
                'statement' => 'You are climbing a staircase. It takes `n` steps to reach the top.\nEach time you can either climb 1 or 2 steps. In how many distinct ways can you climb to the top?',
                'input_format' => "The first line contains a single integer `n`.",
                'output_format' => "Print a single integer, the number of distinct ways to climb to the top.",
                'constraints' => "- 1 <= n <= 45",
                'difficulty' => 'easy',
                'sample_in' => "3",
                'sample_out' => "3"
            ],
            [
                'title' => 'Longest Increasing Subsequence',
                'statement' => 'Given an integer array `nums`, return the length of the longest strictly increasing subsequence.',
                'input_format' => "The first line contains an integer `n`.\nThe second line contains `n` space-separated integers.",
                'output_format' => "Print a single integer representing the length of the longest increasing subsequence.",
                'constraints' => "- 1 <= n <= 2500\n- -10^4 <= nums[i] <= 10^4",
                'difficulty' => 'medium',
                'sample_in' => "8\n10 9 2 5 3 7 101 18",
                'sample_out' => "4"
            ],
            [
                'title' => 'Coin Change',
                'statement' => 'You are given an integer array `coins` representing coins of different denominations and an integer `amount` representing a total amount of money.\nReturn the fewest number of coins that you need to make up that amount. If that amount of money cannot be made up by any combination of the coins, return -1.',
                'input_format' => "The first line contains two integers `n` (number of coins) and `amount`.\nThe second line contains `n` space-separated integers.",
                'output_format' => "Print a single integer, the minimum number of coins. Or -1 if not possible.",
                'constraints' => "- 1 <= coins.length <= 12\n- 1 <= coins[i] <= 2^31 - 1\n- 0 <= amount <= 10^4",
                'difficulty' => 'medium',
                'sample_in' => "3 11\n1 2 5",
                'sample_out' => "3"
            ]
        ];

        // We will randomly assign problems to judges, duplicating some if necessary to get 25
        $problems = [];
        $pIndex = 0;
        foreach ($judges as $judge) {
            for ($p = 1; $p <= 5; $p++) { 
                $data = $realProblems[$pIndex % count($realProblems)];
                $pIndex++;
                
                // Add a slight random suffix to title if it's a duplicate to keep slugs unique
                $title = $pIndex > count($realProblems) ? $data['title'] . " " . rand(2, 50) : $data['title'];

                $problem = Problem::create([
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::random(4),
                    'statement' => $data['statement'],
                    'input_format' => $data['input_format'],
                    'output_format' => $data['output_format'],
                    'constraints' => $data['constraints'],
                    'difficulty' => $data['difficulty'],
                    'is_published' => true,
                    'created_by' => $judge->id,
                ]);
                $problems[] = $problem;

                // Create test cases
                TestCase::create([
                    'problem_id' => $problem->id,
                    'input' => $data['sample_in'] . "\n",
                    'expected_output' => $data['sample_out'] . "\n",
                    'is_hidden' => false,
                ]);
                TestCase::create([
                    'problem_id' => $problem->id,
                    'input' => "100\n",
                    'expected_output' => "-1\n",
                    'is_hidden' => true,
                ]);
            }
        }

        // 4. Create Contests
        $contests = [];
        $contestTitles = ['Weekly Coder Challenge #1', 'Monthly Algorithm Sprint', 'Beginners Welcome Contest', 'Advanced Data Structures Cup'];
        foreach ($contestTitles as $index => $title) {
            $starts_at = now()->subDays(rand(0, 5));
            $ends_at = $starts_at->copy()->addDays(rand(2, 6)); // some might be over, some active
            
            $contest = Contest::create([
                'title' => $title,
                'description' => "This is $title. Solve problems and win prizes!",
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'is_approved' => true,
                'created_by' => collect($judges)->random()->id,
            ]);

            // Add some problems to the contest
            $contestProblems = collect($problems)->random(rand(4, 7));
            foreach ($contestProblems as $idx => $cp) {
                // Ensure no duplicate problem attaches which could throw an error if pivot is unique, 
                // but attach handles duplicates without error in this simple setup if there's no unique constraint.
                if (!$contest->problems()->where('problem_id', $cp->id)->exists()) {
                    $contest->problems()->attach($cp->id, ['label' => chr(65 + $idx)]); // A, B, C...
                }
            }

            // Register some contestants
            $contestParticipants = collect($contestants)->random(rand(3, count($contestants)));
            foreach ($contestParticipants as $participant) {
                if (!$contest->participants()->where('user_id', $participant->id)->exists()) {
                    $contest->participants()->attach($participant->id);
                }
            }

            $contests[] = $contest;
        }

        // 5. Create Submissions
        $languages = ['cpp', 'python', 'java'];
        $statuses = ['accepted', 'wrong_answer', 'time_limit_exceeded', 'compilation_error', 'accepted', 'accepted', 'accepted'];
        
        $badgeService = app(BadgeService::class);

        foreach ($contestants as $contestant) {
            // Each contestant attempts 5 to 15 random problems
            $attemptedProblems = collect($problems)->random(rand(5, 15));

            foreach ($attemptedProblems as $problem) {
                $status = collect($statuses)->random();
                $language = collect($languages)->random();
                
                // Determine if it was inside a contest this contestant is in
                $contest_id = null;
                $contest = $problem->contests()->first();
                if ($contest && $contest->participants()->where('user_id', $contestant->id)->exists()) {
                    $contest_id = $contest->id;
                }

                $submission = Submission::create([
                    'user_id' => $contestant->id,
                    'problem_id' => $problem->id,
                    'contest_id' => $contest_id,
                    'code' => "// Dummy code in $language",
                    'language' => $language,
                    'status' => $status,
                    'verdict_message' => $status === 'accepted' ? 'Passed all test cases.' : 'Failed on hidden test cases.',
                    'submitted_at' => now()->subMinutes(rand(1, 10000)),
                ]);
                
                if ($status === 'accepted') {
                    $badgeService->checkAndAward($submission);
                }
            }
        }
    }
}
