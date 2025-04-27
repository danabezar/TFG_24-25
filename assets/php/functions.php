<?php 
function isValidUsername(string $username): bool{
    $filter = "/^[a-zA-Z\d]+$/";
    if (preg_match($filter, $username)) {
        return true;
    }
    return false;
}

function isValidNumericValue(string $number): bool {
    if(filter_var($number, FILTER_VALIDATE_INT) && intval($number) > 0){
        return true;
    }
    return false;
}

function isValidEmail(string $email){
    return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
}

function areThereNullFields(array $fieldsToCheck, array $dataArray): array{
    $foundNulls = [];

    foreach ($fieldsToCheck as $index => $field) {
        if (!isset($dataArray[$field]) || empty($dataArray[$field]) || $dataArray[$field] == null) {
            $foundNulls[] = $field;
        }
    }
    return $foundNulls;
}

function valueExists(array $dataArray, string $field, mixed $value): bool{
    return in_array($dataArray[$field], $value);
}

function showErrors($errorArray, $field){
    $errorString = "";

    if (isset($errorArray[$field])) {
        $lastError = end($errorArray);

        foreach ($errorArray[$field] as $index => $errorMessage) {
            $lineBreak = ($errorArray[$field] == $lastError) ? "" : "<br>";
            $errorString .= "{$errorMessage}{$lineBreak}";
        }
    }

    return $errorString;
}
?>