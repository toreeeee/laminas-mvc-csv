<?php

namespace Person\Service\CSVFile;

use Person\Service\TableRowInterface;

class PersonRowValidator implements RowValidatorInterface
{
    public function validate(TableRowInterface $row): RowValidationResult
    {
        $columns = $row->getColumns();
        /** @var array<string> $errors */
        $errors = [];

        $validated_date = $this->isValidDateString($columns[0]);

        if (count($validated_date)) {
            $errors[] = "Invalid date string";
            foreach ($validated_date as $it) {
                $errors[] = $it;
            }
        }
        if (!$this->isAlphaString($columns[1])) {
            $errors[] = "First Name contains invalid characters";
        }
        if (!$this->isAlphaString($columns[2])) {
            $errors[] = "Last Name contains invalid characters";
        }
        if (!$this->isNumericString($columns[3])) {
            $errors[] = "Salary must be a number";
        }

        return new RowValidationResult($errors);
    }

    /**
     * @param string $input
     * @return array<string>
     */
    private function isValidDateString(string $input): array
    {
        $split = explode("-", $input);
        $split_count = count($split);

        $errors = [];

        if ($split_count !== 3) {
            $errors[] = "Invalid date string";
        } else {
            if (strlen($split[0]) !== 4 || !$this->isNumericString($split[0])) {
                $errors[] = ("Invalid year");
            }
            if (strlen($split[1]) !== 2 || !$this->isNumericString($split[1])) {
                $errors[] = ("Invalid month");
            }
            if (strlen($split[2]) !== 2 || !$this->isNumericString($split[2])) {
                $errors[] = ("Invalid day");
            }

            if (date_parse($input)["warning_count"] !== 0) {
                $errors[] = "Invalid date";
            }
        }

        return $errors;
    }

    private function isNumericString(string $input): bool
    {
        $pattern = "/^[0-9]*$/";

        return !!preg_match($pattern, $input);
    }

    private function isAlphaString(string $input): bool
    {
        $pattern = "/^[a-z,A-Z]*$/";

        return !!preg_match($pattern, $input);
    }
}
