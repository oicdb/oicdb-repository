#!/usr/bin/env php
<?php
include_once 'vendor/autoload.php';

define('PATH', realpath('..'));

$repositoryTemplate = file_get_contents('templates/repository.json');
$contentRecipes = '';
$oicdbVersion = '';
if (count($argv) > 1) {
    $oicdbVersion = $argv[1];
}

$recipeDirectoryPath = PATH . '/recipes/';
$handle = opendir($recipeDirectoryPath);
if (!$handle) {
    throw new \RuntimeException(sprintf('Directory %s could not be opened', $recipeDirectoryPath));
}

$output = '';
$numErrors = 0;
$numRecipes = 0;
while (false !== ($filename = readdir($handle))) {
    if ($filename === "." || $filename === "..") {
        continue;
    }

    // Reading recipe and determining the current version
    // The last git change timestamp is used as a version string
    $recipeFilePath = PATH . '/recipes/' . $filename;
    $output .= "Validating: $filename";
    $recipe = file_get_contents($recipeFilePath);
    $lastGitChangeTimestamp = '';
    exec('git log -1 --pretty="format:%at" -- ' . escapeshellarg($recipeFilePath), $lastGitChangeTimestamp);
    $version = $lastGitChangeTimestamp[0] . '-' . sha1($recipe);
    $recipe = str_replace('"type":', '"version": "' . $version . '",' . "\n" . '  "type":', $recipe);
    $content = str_replace('"recipes": []', '"recipes": [' . $recipe . ']', $repositoryTemplate);
    if ($numRecipes > 0) {
        $contentRecipes .= ",\n";
    }
    $contentRecipes .= '    ' . $recipe;

    // JSON Schema validation for a single recipe
    $data = json_decode($content, false);
    $validator = new JsonSchema\Validator();
    $validator->validate($data, (object)['$ref' => 'file://' . PATH . '/schema/oicdb.schema.json']);
    if ($validator->isValid()) {
        $output .= " - OK" . PHP_EOL;
    } else {
        $output .= " - ERROR" . PHP_EOL;
        foreach ($validator->getErrors() as $error) {
            $output = sprintf("[%s] %s" . PHP_EOL, $error['property'], $error['message']);
            ++$numErrors;
        }
    }
    ++$numRecipes;
}

closedir($handle);
if ($numErrors > 0) {
    echo "Found $numErrors errors" . PHP_EOL;
    echo $output;
    throw new \RuntimeException(sprintf('Found %s errors', $numErrors));
}

echo "Build finished succesfully" . PHP_EOL;
echo $output;
$hash = sha1($contentRecipes);

// Create build directory
$dir = PATH . '/build';
if (!is_dir($dir)) {
    if (!mkdir($dir) && !is_dir($dir)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
    }
}

// Normal build
if (strlen($oicdbVersion) <= 0) {
    $oicdbVersion = date('YmdHis') . '-' . $hash;
    $content = str_replace('###VERSION###', $oicdbVersion, $repositoryTemplate);
    $content = str_replace('"recipes": []', '"recipes": [' . "\n" . $contentRecipes . "\n" . '  ]', $content);
    file_put_contents(PATH . '/build/oicdb-dev.json', $content);

} else {
    // Release version
    $content = str_replace('###VERSION###', $oicdbVersion, $repositoryTemplate);
    $content = str_replace('"recipes": []', '"recipes": [' . "\n" . $contentRecipes . "\n" . '  ]', $content);
    file_put_contents(PATH . '/build/oicdb-' . $oicdbVersion. '.json', $content);
    copy(PATH . '/build/oicdb-' . $oicdbVersion. '.json', PATH . '/build/oicdb-latest.json');
}