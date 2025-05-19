<?php 
/**
 * Checks if a user's name has the appropiate format
 * 
 * @param string $username The user's name to be checked
 * 
 * @return bool Indicates whether the user's name's format is valid or not
 */
function isValidUsername(string $username): bool{
    $filter = "/^[a-zA-Z\d]+$/";
    if (preg_match($filter, $username)) {
        return true;
    }
    return false;
}

/**
 * Checks if an email has the appropiate format
 * 
 * @param string $email The email to be checked
 * 
 * @return bool Indicates whether the email's format is valid or not
 */
function isValidEmail(string $email){
    return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
}

/**
 * Checks if a Stat or Base value is correct
 * 
 * @param string $stat The Stat/Base to be checked
 * 
 * @return bool Indicates whether the Stat/Base's value is valid or not
 */
function isValidBaseOrStatValue(string $stat): bool {
    if(filter_var($stat, FILTER_VALIDATE_INT) !== false && (intval($stat) >= 0) && (intval($stat) <= 40)){
        return true;
    }
    return false;
}

/**
 * Checks if a Growth value is correct
 * 
 * @param string $growth The Growth value to be checked
 * 
 * @return bool Indicates whether the Growth value is valid or not
 */
function isValidGrowthValue(string $growth): bool {
    if(filter_var($growth, FILTER_VALIDATE_INT) !== false && (intval($growth) >= 0) && (intval($growth) <= 100)){
        return true;
    }
    return false;
}

/**
 * Checks if a Level value is correct
 * 
 * @param string $level The Level value to be checked
 * 
 * @return bool Indicates whether the Level value is valid or not
 */
function isValidLevelValue(string $level): bool {
    if(filter_var($level, FILTER_VALIDATE_INT) !== false && (intval($level) >= 1) && (intval($level) <= 20)){
        return true;
    }
    return false;
}

/**
 * Checks if certain elements of an array with data are either empty or null
 * 
 * @param array $fieldsToCheck The specific fields from an array which will be checked
 * @param array $dataArray The array with the data which will be checked
 * 
 * @return array $foundNulls Array with all the fields that were found to be empty or null
 */
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

/**
 * Checks if a particular field in an associative array has a certain value
 * 
 * @param array $dataArray The array with the data which will be checked
 * @param string $field The field indicating the index of the associative array to be checked
 * @param string mixed $value The value to check if exists in the index of the array
 * 
 * @return bool Indicates whether the element is in the array or not
 */
function valueExists(array $dataArray, string $field, mixed $value): bool{
    return in_array($dataArray[$field], $value);
}

/**
 * Shows all the errors related to a specific field within an array
 * 
 * @param array $errorArray Contains a series of errors from one or multiple fields
 * @param array $field The specific field for which errors should be displayed
 * 
 * @return array $errorString A String with the concatenation of the possible errors
 */
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

/**
 * Checks if the data retrieved from a Form is valid
 * 
 * @param array $nonNullableFields The fields which cannot be null or empty
 * @param array $uniqueFields The fields whose value cannot already be registered in the database
 * @param array $formDataArray The data from the Form to be checked
 * @param BaseModel $model The Model related to the database Entity whose data may or may not be registered
 * 
 * @return bool Indicates if all the values are valid or not
 */
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

/**
 * Checks if the data retrieved from a Form has a valid format
 * 
 * @param array $formatFilterType Specifies which filter will be applied to a list of fields
 * @param array $toCheckFields The fields whose format will be checked
 * @param array $formDataArray The data from the Form to be checked
 * 
 * @return bool Indicates if the format of every field is valid or not
 */
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