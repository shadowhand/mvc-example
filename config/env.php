<?php

// Load development ENV configuration.
$loader = new josegonzalez\Dotenv\Loader(__DIR__ . '/../.env');

// Parse the config file.
$loader->parse();

// We need Github credentials to be defined.
$loader->expect('GITHUB_CLIENT_ID', 'GITHUB_CLIENT_SECRET');

// Make variables available to getenv().
$loader->putEnv(false);
