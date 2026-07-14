<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Notifications\SubmissionProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class JudgeSubmission implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Submission $submission
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Load submission relationships
        $this->submission->load(['problem.testCases', 'user']);

        $testCases = $this->submission->problem->testCases->values();

        // 2. Add small random chance (3% Compilation Error, 5% Time Limit Exceeded)
        $rand = rand(1, 100);

        if ($rand <= 3) {
            $status = 'compilation_error';
            $verdictMessage = 'Compilation failed: Random syntax error.';
        } elseif ($rand <= 8) {
            $status = 'time_limit_exceeded';
            $verdictMessage = 'Time Limit Exceeded: The program execution exceeded the limit of 1.00 seconds.';
        } else {
            // 3. Loop through test cases and run the real execution comparison
            $status = 'accepted';
            $verdictMessage = 'All evaluation test cases passed.';

            if ($testCases->isEmpty()) {
                $verdictMessage = 'Passed: No evaluation test cases defined.';
            } else {
                foreach ($testCases as $index => $tc) {
                    $errorMessage = '';
                    $actualOutput = $this->executeReal(
                        $this->submission->code, 
                        $this->submission->language,
                        $tc->input, 
                        $errorMessage
                    );

                    // Check for compilation failure
                    if ($actualOutput === 'COMPILATION_ERROR_FLAG') {
                        $status = 'compilation_error';
                        $verdictMessage = $errorMessage;
                        break;
                    }

                    // Check for general execution error / missing output
                    if ($actualOutput === null) {
                        $status = 'wrong_answer';
                        $verdictMessage = 'Runtime Error: ' . $errorMessage;
                        break;
                    }

                    // Compare trimmed actual against trimmed expected output
                    if (trim($actualOutput) !== trim($tc->expected_output)) {
                        $status = 'wrong_answer';
                        $verdictMessage = 'Wrong Answer on testcase #' . ($index + 1) . '.';
                        break;
                    }
                }
            }
        }

        // 4. Save the submission with final status and verdict
        $this->submission->status = $status;
        $this->submission->verdict_message = $verdictMessage;
        $this->submission->save();

        if ($status === 'accepted') {
            app(\App\Services\BadgeService::class)->checkAndAward($this->submission);
        }

        // 5. Send database notification to the submitting user
        $this->submission->user->notify(new SubmissionProcessed($this->submission));
    }

    /**
     * Resolves the executable path for the compiler/interpreter.
     */
    private function resolveExecutable(string $name): string
    {
        $paths = [
            'python' => [
                'C:\\Users\\USER\\anaconda3\\python.exe',
                'python'
            ],
            'g++' => [
                'C:\\MinGW\\bin\\g++.exe',
                'g++'
            ],
            'javac' => [
                'C:\\Program Files\\Amazon Corretto\\jdk17.0.18_9\\bin\\javac.exe',
                'javac'
            ],
            'java' => [
                'C:\\Program Files\\Amazon Corretto\\jdk17.0.18_9\\bin\\java.exe',
                'java'
            ],
        ];

        if (isset($paths[$name])) {
            foreach ($paths[$name] as $path) {
                if (strpos($path, '\\') !== false) {
                    if (file_exists($path)) {
                        return $path;
                    }
                } else {
                    return $path;
                }
            }
        }

        return $name;
    }

    /**
     * Helper to run a command with stdin input redirected from a file using shell_exec.
     */
    private function runCommand(string $command, string $input, string &$errorMessage): ?string
    {
        $dir = storage_path('app/submissions');
        $tempInputFile = $dir . '/' . uniqid('input_') . '.txt';
        file_put_contents($tempInputFile, $input);

        $fullCmd = $command . " < " . escapeshellarg($tempInputFile) . " 2>&1";
        
        $output = shell_exec($fullCmd);
        
        @unlink($tempInputFile);
        
        return $output;
    }

    /**
     * Executes the contestant's code using local interpreters/compilers.
     */
    private function executeReal(string $code, string $language, string $input, string &$errorMessage): ?string
    {
        $dir = storage_path('app/submissions');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $tempId = uniqid('sub_');

        if ($language === 'python') {
            $filePath = $dir . '/' . $tempId . '.py';
            file_put_contents($filePath, $code);

            // Execute Python solution via shell_exec
            $pythonExe = $this->resolveExecutable('python');
            $cmd = escapeshellarg($pythonExe) . " " . escapeshellarg($filePath);
            $output = $this->runCommand($cmd, $input, $errorMessage);

            // Clean up file
            @unlink($filePath);

            return $output;
        }

        if ($language === 'cpp') {
            $srcPath = $dir . '/' . $tempId . '.cpp';
            $exePath = $dir . '/' . $tempId . '.exe';
            file_put_contents($srcPath, $code);

            // Compile C++ solution via shell_exec
            $gppExe = $this->resolveExecutable('g++');
            $compileCmd = escapeshellarg($gppExe) . " " . escapeshellarg($srcPath) . " -o " . escapeshellarg($exePath) . " 2>&1";
            $compileOutput = shell_exec($compileCmd);

            if (!file_exists($exePath)) {
                $errorMessage = $compileOutput ?: 'Compilation failed with empty compiler output.';
                @unlink($srcPath);
                return 'COMPILATION_ERROR_FLAG';
            }

            // Run compiled C++ executable via shell_exec
            $runCmd = escapeshellarg($exePath);
            $output = $this->runCommand($runCmd, $input, $errorMessage);

            // Clean up files
            @unlink($srcPath);
            @unlink($exePath);

            return $output;
        }

        if ($language === 'java') {
            // Find class name from code block
            $className = 'Solution';
            if (preg_match('/public\s+class\s+(\w+)/', $code, $matches)) {
                $className = $matches[1];
            } elseif (preg_match('/class\s+(\w+)/', $code, $matches)) {
                $className = $matches[1];
            }

            $javaDir = $dir . '/' . $tempId;
            mkdir($javaDir, 0755, true);

            $srcPath = $javaDir . '/' . $className . '.java';
            $classFile = $javaDir . '/' . $className . '.class';
            file_put_contents($srcPath, $code);

            // Compile Java solution via shell_exec
            $javacExe = $this->resolveExecutable('javac');
            $compileCmd = escapeshellarg($javacExe) . " " . escapeshellarg($srcPath) . " 2>&1";
            $compileOutput = shell_exec($compileCmd);

            if (!file_exists($classFile)) {
                $errorMessage = $compileOutput ?: 'Java compilation failed.';
                $this->rrmdir($javaDir);
                return 'COMPILATION_ERROR_FLAG';
            }

            // Run Java program via shell_exec
            $javaExe = $this->resolveExecutable('java');
            $runCmd = escapeshellarg($javaExe) . " -cp " . escapeshellarg($javaDir) . " " . escapeshellarg($className);
            $output = $this->runCommand($runCmd, $input, $errorMessage);

            // Clean up directory
            $this->rrmdir($javaDir);

            return $output;
        }

        return null;
    }

    /**
     * Recursively delete directory.
     */
    private function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== "." && $object !== "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) {
                        $this->rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        @unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            @rmdir($dir);
        }
    }
}
