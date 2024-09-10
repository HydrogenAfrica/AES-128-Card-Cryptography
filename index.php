<?php

class Encryption
{
    public static function encrypt($plaintext, $method, $clientKey, $clientIV)
    {
        // Ensure the key and IV are in binary format
        $clientKey = base64_decode($clientKey);
        $clientIV = base64_decode($clientIV);

        // Perform AES-128-CBC encryption
        $ciphertext = openssl_encrypt($plaintext, $method, $clientKey, OPENSSL_RAW_DATA, $clientIV);
        
        // Encode the result in base64 to make it safe for storage or transmission
        return base64_encode($ciphertext);
    }

    public static function decrypt($ciphertextBase64, $method, $clientKey, $clientIV)
    {
        // Ensure the key and IV are in binary format
        $clientKey = base64_decode($clientKey);
        $clientIV = base64_decode($clientIV);

        // Decode the base64 encoded ciphertext
        $ciphertext = base64_decode($ciphertextBase64);

        // Perform AES-128-CBC decryption
        $plaintext = openssl_decrypt($ciphertext, $method, $clientKey, OPENSSL_RAW_DATA, $clientIV);

        return $plaintext;
    }
}

// Example card details to be encrypted
$cardDetails = [
    'CardNumber' => '4456530000001096',
    'ExpiryMonth' => '03',
    'ExpiryYear' => '50',
    'Pin' => '1111',
    'Cvv' => '111'
];

// Convert the card details array to JSON
$cardDetailsJson = json_encode($cardDetails, JSON_UNESCAPED_SLASHES);  // Match C# serialization

$method = 'AES-128-CBC'; // Use AES-128 for key 16 bytes & AES-256 for key 32 bytes

// Encryption key and IV (these should be securely stored and managed)
$clientKey = 'NBiPLxlq0WWInT4Hob+glw==';  // base64 encoded key
$clientIV = '4betVRpFIVwvbNLJwMszew==';  // base64 encoded IV

// Encrypt the card details
$encryptedText = Encryption::encrypt($cardDetailsJson, $method, $clientKey, $clientIV);

// Decrypt the card details
$decryptedText = Encryption::decrypt($encryptedText, $method, $clientKey, $clientIV);


echo 'Card Details' . $cardDetailsJson . "<br>"; 
echo 'cipher=' . $method . "<br>";
echo "Encrypted Card Details: " . $encryptedText . "<br>";
echo "Decrypted Card Details: " . $decryptedText . "<br>";

?>
