<?php

if (! function_exists('generateUserNumber')) {
    function generateUserNumber(): string
    {
        $latestUser = \App\Models\User::latest()->first();

        if ($latestUser) {
            preg_match('/U-(\d+)/', $latestUser->user_number, $matches);
            $number = $matches[1] ?? 0;
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('U-%05d', $newNumber);
    }
}
