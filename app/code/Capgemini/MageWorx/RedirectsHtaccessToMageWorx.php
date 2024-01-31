<?php

use Magento\Framework\App\Bootstrap;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Validation\ValidationException;
use Magento\Store\Model\StoreManagerInterface;

require '../../../bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$driverFile = $objectManager->get(File::class);
$directoryList = $objectManager->get(DirectoryList::class);
$storeManager = $objectManager->get(StoreManagerInterface::class);

$inputFile = $directoryList->getPath('var') . '/' . 'redirects.txt';

if (!file_exists($inputFile)) {
    echo 'No redirects.txt found file in var folder.';
    exit();
}

$outputFile = $directoryList->getPath('var') . '/' . 'redirects.csv';

if (file_exists($outputFile)) {
    $driverFile->deleteFile($outputFile);
}

$allExistingStoresCodes = array_keys($storeManager->getStores(false, true));
$neededStoreCodes = null;

try {
    if ($argc < 2) {
        throwError('Missing store views codes!
     Please pass store views codes separated by whitespace or "all" for all store views as Command Line arguments.');
    } elseif ($argc > 2 && in_array('all', $argv)) {
        throwError('Ambiguity detected!
     If You want to have rewrites for all store views pass argument "all" to Command Line as the only one. Otherwise don\'t pass it at all.');
    } elseif ($argc === 2 && $argv[1] === 'all') {
        $neededStoreCodes = 'all';
    } else {
        $index = 0;
        while (isset($argv[++$index])) {
            $storeCode = $argv[$index];

            if (!in_array($storeCode, $allExistingStoresCodes)) {
                throwError('Store view code does not exist!', null, '(' . $storeCode . ')');
            }

            $neededStoreCodes[] = $storeCode;
        }
    }

    $storeWorkoutCallable = is_array($neededStoreCodes) ? 'createForChosenStores' : 'createForAllStores';
    $inputFilePointer = $driverFile->fileOpen($inputFile, 'r');
    $outputFilePointer = $driverFile->fileOpen($outputFile, 'a');
    $driverFile->filePutCsv(
        $outputFilePointer,
        ['Request Identifier', 'Target URL', 'Redirect Code', 'Store View Code']
    );
    $lineNumber = 0;
    while (true) {
        try {
            $line = $driverFile->fileReadLine($inputFilePointer, 0, "\n");
        } catch (FileSystemException $exception) {

            break;
        }
        /** @var callable $storeWorkoutCallable */
        $storeWorkoutCallable(parseLine($line, ++$lineNumber), $outputFilePointer);

    }
} catch (\Exception $exception) {
    echo $exception->getMessage();
    if (file_exists($outputFile)) {
        $driverFile->deleteFile($outputFile);
    }
    exit();
}

/**
 * @throws ValidationException
 */
function parseLine ($line, $lineNumber): array
{
    $preparedData = [];
    $parts = explode(' ', trim($line));
    $parts = array_diff($parts, ['']);

    if (count($parts) !== 4) {
        throwError(
            'The number of parts in line is not equal 4:',
            $lineNumber,
            partsToPrint($parts)
        );
    }

    $partsOrig = $parts;

    if (array_shift($parts) !== 'RewriteRule') {
        throwError(
            "Line should begin with the word of \"RewriteRule\" while it begins with \"{$partsOrig[0]}\"",
            $lineNumber,
            partsToPrint($partsOrig)
        );
    }

    $preparedData['request_path'] = parseRequestPath($parts[0], $lineNumber);
    $preparedData['target_path'] = parseTargetPath($parts[1], $lineNumber);
    $preparedData['redirect_type'] = extractRedirect($parts[2]);

    return $preparedData;
}

/**
 * @throws ValidationException
 */
function parseRequestPath($path, $lineNumber)
{
    if (!preg_match('#^\^/?([a-zA-Z0-9_-]+/?)+\$$#', $path)) {
        throwError(
            'The request path does not match the pattern "^(/)part1/part2/../partN(/)$": ',
            $lineNumber,
            $path
        );
    }

    $path = substr($path, 0, strlen($path) - 1);

    return substr($path, 1);
}

/**
 * @throws ValidationException
 */
function parseTargetPath($origPath, $lineNumber)
{
    $path = preg_replace('#http[s]?://%{SERVER_NAME}/#', '', $origPath);

    if (!preg_match('#^([a-zA-Z0-9_-]+/?)+$#', $path)) {
        throwError(
            'The target path does not match the pattern "part1/part2/../partN(/)": ',
            $lineNumber,
            $path
        );
    }

    return $path;
}

function extractRedirect($modifiers)
{
    if (preg_match('#R=301|302#', $modifiers, $matches)) {

        return strstr($matches[0], "3");
    }

    return 0;
}

function createForChosenStores($lineInfo, $pointer)
{
    foreach ($GLOBALS['neededStoreCodes'] as $storeCode) {
        $data = [
            $lineInfo['request_path'],
            $lineInfo['target_path'],
            $lineInfo['redirect_type'],
            $storeCode
        ];
        $GLOBALS['driverFile']->filePutCsv($pointer, $data);
    }
}

function createForAllStores($lineInfo, $pointer)
{
    $data = [
        $lineInfo['request_path'],
        $lineInfo['target_path'],
        $lineInfo['redirect_type'],
        'all'
    ];
    $GLOBALS['driverFile']->filePutCsv($pointer, $data);
}

function partsToPrint($parts): string
{
    $counter = 0;
    $toReturn = '';
    foreach ($parts as $part) {
        $toReturn .= ++$counter . ') ' . $part . PHP_EOL;
    }

    return rtrim($toReturn);
}

/**
 * @throws ValidationException
 */
function throwError($coreMessage, $lineNumber = null, $showResult = '')
{
    if ($lineNumber) {
        throw new ValidationException(__(sprintf(
            'Line number %1$d:%4$s%2$s%4$s%3$s%4$s',
            $lineNumber,
            $coreMessage,
            $showResult,
            PHP_EOL
        )));
    }

    throw new ValidationException(__(sprintf('%1$s%3$s%2$s%3$s', $coreMessage, $showResult, PHP_EOL)));
}
