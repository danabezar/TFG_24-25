<?php 
//TODO: ADD COMMENT
function isValidUsername(string $username): bool{
    $filter = "/^[a-zA-Z\d]+$/";
    if (preg_match($filter, $username)) {
        return true;
    }
    return false;
}

//TODO: ADD COMMENT
function isValidEmail(string $email){
    return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
}

//TODO: ADD COMMENT
function isValidBaseOrStatValue(string $stat): bool {
    if(filter_var($stat, FILTER_VALIDATE_INT) !== false && (intval($stat) >= 0) && (intval($stat) <= 40)){
        return true;
    }
    return false;
}

//TODO: ADD COMMENT
function isValidGrowthValue(string $growth): bool {
    if(filter_var($growth, FILTER_VALIDATE_INT) !== false && (intval($growth) >= 0) && (intval($growth) <= 100)){
        return true;
    }
    return false;
}

//TODO: ADD COMMENT
function isValidLevelValue(string $level): bool {
    if(filter_var($level, FILTER_VALIDATE_INT) !== false && (intval($level) >= 1) && (intval($level) <= 20)){
        return true;
    }
    return false;
}

//TODO: ADD COMMENT
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

//TODO: ADD COMMENT
function valueExists(array $dataArray, string $field, mixed $value): bool{
    return in_array($dataArray[$field], $value);
}

//TODO: ADD COMMENT
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

//TODO: ADD COMMENT
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

    //Final check. If no errors were found, a true will be returned
    if (!$error) {
        return true;
    } else {
        $_SESSION["errors"] = $errors;
        $_SESSION["formData"] = $formDataArray;
        return false;
    }
}

//TODO: ADD COMMENT
function isValidFormDataFormat(string $formatFilterType, array $toCheckFields, array $formDataArray): bool {
    $foundFormatErrors = [];

    foreach($toCheckFields as $currentField){
        switch ($formatFilterType){
            case "base":
            case "stat":
                if(!isValidBaseOrStatValue($formDataArray[$currentField])){
                    $foundFormatErrors[$currentField][] = "The " . $currentField . " field's format isn't valid, try again";
                }
                break;
            case "growth":
                if(!isValidGrowthValue($formDataArray[$currentField])){
                    $foundFormatErrors[$currentField][] = "The " . $currentField . " field's format isn't valid, try again";
                }
                break;
            case "level":
                if(!isValidLevelValue($formDataArray[$currentField])){
                    $foundFormatErrors[$currentField][] = "The " . $currentField . " field's format isn't valid, try again";
                }
                break;
            default:
                break;
        }
    }

    if(!empty($foundFormatErrors)){
        $errors = $_SESSION["errors"] ?? [];

        foreach($foundFormatErrors as $index => $values){
            $errors[$index][] = $values[0];
        }

        $_SESSION["errors"] = $errors;
        $_SESSION["formData"] = $formDataArray;
        return false;
    }
    
    return true;
}
?>