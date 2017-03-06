<?php

// Symmetric Encryption

// Cipher method to use for symmetric encryption
const CIPHER_METHOD = 'AES-256-CBC';

function key_encrypt($string, $key, $cipher_method=CIPHER_METHOD) {
  // return "D4RK SH4D0W RUL3Z";

  // Needs a key of length 32 (256-bit)
  $key = str_pad($key, 32, '*');

  // Create an initialization vector which randomizes the
  // initial settings of the algorithm, making it harder to decrypt.
  // Start by finding the correct size of an initialization vector 
  // for this cipher method.
  $iv_length = openssl_cipher_iv_length($cipher_method);
  $iv = openssl_random_pseudo_bytes($iv_length);

  // Encrypt
  $encrypted = openssl_encrypt($string, $cipher_method, $key, OPENSSL_RAW_DATA, $iv);

  // Return $iv at front of string, need it for decoding
  $message = $iv . $encrypted;
  
  // Encode just ensures encrypted characters are viewable/savable
  // echo base64_encode($message);
  return base64_encode($message);

  // LmnhW5OjbciSmcmmlrsTyHwSkRQgqSUitfZtJBXLUl4+ZFp9vDVQ6hFI9jJ0g6ru

}

function key_decrypt($string, $key, $cipher_method=CIPHER_METHOD) {
  // return "PWNED YOU!";
  // Needs a key of length 32 (256-bit)
  $key = str_pad($key, 32, '*');

  // Base64 decode before decrypting
  $iv_with_ciphertext = base64_decode($string);
  
  // Separate initialization vector and encrypted string
  $iv_length = openssl_cipher_iv_length($cipher_method);
  $iv = substr($iv_with_ciphertext, 0, $iv_length);
  $ciphertext = substr($iv_with_ciphertext, $iv_length);

  // Decrypt
  $plaintext = openssl_decrypt($ciphertext, CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);

  // echo $plaintext;
  return $plaintext;
}


// Asymmetric Encryption / Public-Key Cryptography

// Cipher configuration to use for asymmetric encryption
const PUBLIC_KEY_CONFIG = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

function generate_keys($config=PUBLIC_KEY_CONFIG) {
  $private_key = 'Ha ha!';
  $public_key = 'Ho ho!';

  $resource = openssl_pkey_new($config);

  openssl_pkey_export($resource, $private_key);

  // Extract public key from the pair
  $key_details = openssl_pkey_get_details($resource);
  $public_key = $key_details["key"];

  $keys = array('private' => $private_key, 'public' => $public_key);

  return $keys;
}

function pkey_encrypt($string, $public_key) {
  // return 'Qnex Funqbj jvyy or jngpuvat lbh';
  $encrypted = '';

  openssl_public_encrypt($string, $encrypted, $public_key);

  $message = base64_encode($encrypted);

  return $message;
}

function pkey_decrypt($string, $private_key) {
  // return 'Alc evi csy pssomrk livi alir csy wlsyph fi wezmrk ETIB?';
  $decrypted = '';

  $ciphertext = base64_decode($string);
  openssl_private_decrypt($ciphertext, $decrypted, $private_key);

  return $decrypted;
}


// Digital signatures using public/private keys

function create_signature($data, $private_key) {
  // A-Za-z : ykMwnXKRVqheCFaxsSNDEOfzgTpYroJBmdIPitGbQUAcZuLjvlWH
  // return 'RpjJ WQL BImLcJo QLu dQv vJ oIo Iu WJu?';

  $raw_signature = '';

  openssl_sign($data, $raw_signature, $private_key);
  
  // Use base64_encode to make contents viewable/sharable
  $signature = base64_encode($raw_signature);
  return $signature;
}

function verify_signature($data, $signature, $public_key) {
  // Vigenère
  // return 'RK, pym oays onicvr. Iuw bkzhvbw uedf pke conll rt ZV nzxbhz.';
  $raw_signature = base64_decode($signature);
  $result = openssl_verify($data, $raw_signature, $public_key);
  return $result;
  // returns 1 if data and signature match

  // $modified_data = $data . "extra content";
  // $result = openssl_verify($modified_data, $signature, $keys['public']);
  // echo $result;
}

?>
