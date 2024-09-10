# Card Details Encryption & Decryption (AES-128-CBC)

This repository contains code to encrypt and decrypt sensitive card details using the AES-128-CBC encryption algorithm. It provides a class-based approach as well as an alternative non-class-based implementation for users who prefer a simpler method. 
This example uses the built-in OpenSSL functions for encryption and decryption.

## Features

- AES-128-CBC encryption for securing card details.
- Decrypt the encrypted data back into its original form.
- Class-based and alternative procedural approach for flexibility.
- JSON serialization of card details.

## Requirements

- PHP 7.4 or later.
- OpenSSL extension enabled in PHP.

## Installation

```bash
git clone https://github.com/your-username/encryption-example.git
cd encryption-example

```

# Usage

- Class-Based Encryption/Decryption

The class-based implementation uses the Encryption class for AES-128-CBC encryption and decryption.

## Code Example

```php

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


echo "Card Details: " . $cardDetailsJson . "<br>"; 
echo "cipher: " . $method . "<br>";
echo "Encrypted Card Details: " . $encryptedText . "<br>";
echo "Decrypted Card Details: " . $decryptedText . "<br>";

?>

```

## Output Example:

```bash

Card Details: {"CardNumber":"4456530000001096","ExpiryMonth":"03","ExpiryYear":"50","Pin":"1111","Cvv":"111"}
cipher: AES-128-CBC
Encrypted Card Details: WL0l6/r8DFH+azKVX4ms4evQTNxrjD4xkmN+IockxLyk+iXJkjwKI2IJXZmRB6ODiNa5ENtXOhJQVZNHiWykYDuigbcgPjCZaKgSSqQLPHz8cjx933xgDYI2/ufaigLD
Decrypted Card Details: {"CardNumber":"4456530000001096","ExpiryMonth":"03","ExpiryYear":"50","Pin":"1111","Cvv":"111"}

```

- Non-Class-Based Encryption/Decryption(Alternatively)
For users who prefer not to use classes, here is an alternative implementation without classes. This method provides the same AES-128-CBC encryption and decryption functionality.

## Code Example

```php

<?php

$cardDetails = [
    'CardNumber' => '4456530000001096',
    'ExpiryMonth' => '03',
    'ExpiryYear' => '50',
    'Pin' => '1111',
    'Cvv' => '111'
];

// Convert the card details array to JSON
$cardDetailsJson = json_encode($cardDetails, JSON_UNESCAPED_SLASHES);

$clientKey = 'NBiPLxlq0WWInT4Hob+glw==';  // base64 encoded key
$clientIV = '4betVRpFIVwvbNLJwMszew==';  // base64 encoded IV

// Decode the Base64 clientkey and clientiv
$key = base64_decode($clientKey);
$iv = base64_decode($clientIV);

$method = 'aes-128-cbc'; // Use AES-128 for key 16 bytes & AES-256 for key 32 bytes

// Encrypt the card details
$encrypted = base64_encode(openssl_encrypt($cardDetailsJson, $method, $key, OPENSSL_RAW_DATA, $iv));

// Decrypt the encryted card back to the original message
$decrypted = openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);

echo "Card Details= " . $cardDetailsJson . "<br>"; 
echo "Cipher= " . $method . "<br>";
echo "Encrypted to: " . $encrypted . "<br>";
echo "Decrypted to: " . $decrypted . "<br>";

```

## Output Example

```bash
Card Details= {"CardNumber":"4456530000001096","ExpiryMonth":"03","ExpiryYear":"50","Pin":"1111","Cvv":"111"}
Cipher= aes-128-cbc
Encrypted to: WL0l6/r8DFH+azKVX4ms4evQTNxrjD4xkmN+IockxLyk+iXJkjwKI2IJXZmRB6ODiNa5ENtXOhJQVZNHiWykYDuigbcgPjCZaKgSSqQLPHz8cjx933xgDYI2/ufaigLD
Decrypted to: {"CardNumber":"4456530000001096","ExpiryMonth":"03","ExpiryYear":"50","Pin":"1111","Cvv":"111"}

```

## AES-128 vs. AES-256

- AES-128 uses a 16-byte key and a 16-byte IV.
- This project currently uses AES-128 as the preferred algorithm for encrypting card details base on our Server to Server Integration
- AES-256 uses a 32-byte key and a 16-byte IV.


# Note

- Card Details: The card details are first stored in an array and converted into a JSON string format.
- Base64 Encoding/Decoding: The key and IV are Base64-encoded and then decoded before being passed into the encryption function.
- Encryption: The encryption is done using the openssl_encrypt() function with the aes-128-cbc algorithm, and the result is Base64-encoded for safe transmission/storage.
- Decryption: The openssl_decrypt() function is used to decrypt the Base64-encoded encrypted data back into the original card details.



