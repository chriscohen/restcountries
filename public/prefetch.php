<?php
/**
 * @file
 * Prefetch all information from the database, so we can use it to populate our form.
 */

require_once '../vendor/autoload.php';

use ChrisCohen\Manager\EntityManager;

$em = new EntityManager();

echo json_encode($em->toJson());
