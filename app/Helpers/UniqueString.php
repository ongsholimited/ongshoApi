<?php
namespace App\Helpers;
 

class UniqueString{
    public static function getToken($length = 15) {
        // Generate a unique ID based on the current time in microseconds
        $token = uniqid();
    
        // Define the characters that can be used in the token
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
        // Calculate the number of characters in the character set
        $numCharacters = strlen($characters);
    
        // Add random characters to the token until it reaches the desired length
        while (strlen($token) < $length) {
            // Generate a random index within the character set
            $randomIndex = mt_rand(0, $numCharacters - 1);
    
            // Append the random character to the token
            $token .= $characters[$randomIndex];
        }
    
        // Make sure the token is exactly the desired length
        $token = substr($token, 0, $length);
    
        return $token;
    }
}
