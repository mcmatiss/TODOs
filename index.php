<?php
require_once "ThingTODO.php";
require "vendor/autoload.php";

use PHP_Parallel_Lint\PhpConsoleColor\ConsoleColor;
use LucidFrame\Console\ConsoleTable;

$consoleColor = new ConsoleColor();
$table = new ConsoleTable();

$table
    ->addHeader("Index")
    ->addHeader("TODO")
    ->setPadding(2)
    ->addRow();

function isInputValid(int $userInput, int $minValue, int $maxValue): bool
{
    return $userInput >= $minValue && $userInput <= $maxValue;
}

$thingsTODO = [];

if (file_exists("storedTODOs")) {
    $storeTODOs = file_get_contents("storedTODOs");
    $thingsTODO = unserialize($storeTODOs);
}

do {
    if ($thingsTODO) {
        echo "\n";
        foreach ($thingsTODO as $index => $TODO) {
            $table
                ->addColumn($index + 1 . ".", 0, $index)
                ->addColumn($TODO->getDisplayName(), 1, $index);
        }
        $table->display();

        echo "\n1. Create new TODO.\n" .
            "2. Mark TODO as completed.\n" .
            "3. Unmark TODO as completed.\n" .
            "4. Delete a TODO.\n" .
            "5. Exit.\n";
    } else {
        echo "\n1. Create new TODO.\n" .
            $consoleColor->apply(
                "color_240",
                "2. Mark TODO as completed.\n" .
                    "3. Unmark TODO as completed.\n" .
                    "4. Delete a TODO.\n"
            );
        echo "5. Exit.\n";
    }

    $menuSelection = (int) readline("Enter your choice [number]: ");
    switch ($menuSelection) {
        case 1:
            $newName = readline("Enter your TODO: ");
            $thingsTODO[] = new thingTODO($newName);
            break;
        case 2:
            if ($thingsTODO) {
                $markSelection = (int) readline("Enter TODO [index]: ");
                if (!isInputValid($markSelection, 1, count($thingsTODO))) {
                    readline("Invalid input...");
                    break;
                }
                $thingsTODO[$markSelection - 1]->setDisplayName(
                    $consoleColor->apply(
                        "color_240",
                        $thingsTODO[$markSelection - 1]->getName() .
                            " (Completed)"
                    )
                );
            }
            break;
        case 3:
            if ($thingsTODO) {
                $unmarkSelection = (int) readline("Enter TODO [index]: ");
                if (!isInputValid($unmarkSelection, 1, count($thingsTODO))) {
                    readline("Invalid input...");
                    break;
                }
                $thingsTODO[$unmarkSelection - 1]->setDisplayName(
                    $thingsTODO[$unmarkSelection - 1]->getName()
                );
            }
            break;
        case 4:
            if ($thingsTODO) {
                $deleteSelection = (int) readline(
                    "Enter TODO to delete [index]: "
                );
                if (!isInputValid($deleteSelection, 1, count($thingsTODO))) {
                    readline("Invalid input...");
                    break;
                }
                array_splice($thingsTODO, $deleteSelection - 1, 1);
                $table = new ConsoleTable();
                $table
                    ->addHeader("Index")
                    ->addHeader("TODO")
                    ->setPadding(2)
                    ->addRow();
            }
            break;
        case 5:
            $storeTODOs = serialize($thingsTODO);
            file_put_contents("storedTODOs", $storeTODOs);
            return true;
        default:
            readline("Invalid input...");
    }
} while (true);
