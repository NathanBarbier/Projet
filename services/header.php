<?php
// print_r($_COOKIE);
session_start();

require_once 'constants.php';
require_once 'globalFunctions.php';
require_once MODELS_PATH.'Modele.php';
require_once MODELS_PATH.'Team.php';
require_once MODELS_PATH.'Organization.php';
require_once MODELS_PATH.'Project.php';
require_once MODELS_PATH.'User.php';
require_once MODELS_PATH.'BelongsTo.php';
require_once MODELS_PATH.'Task.php';
require_once MODELS_PATH.'MapColumn.php';
require_once MODELS_PATH.'TaskComment.php';
require_once MODELS_PATH.'TaskMember.php';
require_once SERVICES_PATH.'Inscription.php';