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
        if (!isset($dataArray[$field]) || empty($dataArray[$field]) || $dataArray[$field] === null) {
            if($dataArray[$field] != "0" && $dataArray[$field] != 0){
                $foundNulls[] = $field;
            }
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

function isValidFormData(array $nonNullableFields, array $uniqueFields, array $formDataArray, BaseModel $model): bool{
    $error = false;
    $errors = [];

    //In case we need to add error and form data, we erase the previously registered ones
    $_SESSION["errors"] = [];
    if (isset($_SESSION["errors"])) {
        unset($_SESSION["errors"]);
    }
    $_SESSION["formData"] = [];
    if (isset($_SESSION["formData"])) {
        unset($_SESSION["formData"]);
    }

    //Empty field checks
    $foundNullFields = areThereNullFields($nonNullableFields, $formDataArray);

    if (count($foundNullFields) > 0) {
        $error = true;
        for ($i = 0; $i < count($foundNullFields); $i++) {
            $errors[$foundNullFields[$i]][] = "The \"{$foundNullFields[$i]}\" field is null";
        }
    }

    //Unique fields checks
    foreach ($uniqueFields as $uniqueField) {
        if ($model->exists($uniqueField, $formDataArray[$uniqueField])) {
            $error = true;
            $errors[$uniqueField][] = "Value \"{$formDataArray[$uniqueField]}\" for \"{$uniqueField}\" is already registered";
        }
    }

    //Final check. If no errors were found, the insertion is made
    if (!$error) {
        return true;
    } else {
        $_SESSION["errors"] = $errors;
        $_SESSION["formData"] = $formDataArray;
        return false;
    }
}
?>