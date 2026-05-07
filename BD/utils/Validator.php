<?php
// utils/Validator.php

class Validator {
    public static function check(?array $data, array $rules): array|null {
        $errors = [];
        $data   = $data ?? [];
        foreach ($rules as $field => $ruleStr) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $ruleStr) as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = "Le champ $field est requis";
                    break;
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (strlen((string)$value) < $min)
                        $errors[$field] = "Le champ $field doit contenir au moins $min caractères";
                }
                if ($rule === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Email invalide";
                }
                if (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if ($value && !in_array($value, $allowed))
                        $errors[$field] = "Valeur non autorisée pour $field";
                }
            }
        }
        return empty($errors) ? null : $errors;
    }
}